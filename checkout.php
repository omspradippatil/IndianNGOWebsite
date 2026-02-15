<?php
require_once 'auth.php';
requireLogin();

$user = getCurrentUser();

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header('Location: cart.php?error=Security token mismatch');
        exit;
    }
    
    $user_id = intval($_SESSION['user_id']);
    global $conn;
    
    // Get cart items with prepared statement
    $stmt = $conn->prepare("SELECT c.*, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
    if (!$stmt) {
        header('Location: cart.php?error=Database error');
        exit;
    }
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_items = [];
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }
    $stmt->close();
    
    if (empty($cart_items)) {
        header('Location: cart.php?error=Your cart is empty');
        exit;
    }
    
    // Calculate total
    $total = 0;
    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    $delivery_fee = 50;
    $grand_total = $total + $delivery_fee;
    
    // Create order with prepared statement
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, payment_status, delivery_status) VALUES (?, ?, 'pending', 'pending')");
    if (!$stmt) {
        header('Location: cart.php?error=Failed to create order');
        exit;
    }
    
    $stmt->bind_param("id", $user_id, $grand_total);
    
    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;
        $stmt->close();
        
        // Add order items
        $items_added = true;
        foreach ($cart_items as $item) {
            $insert_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
            if (!$insert_stmt) {
                $items_added = false;
                break;
            }
            
            $product_id = intval($item['product_id']);
            $quantity = intval($item['quantity']);
            $price = floatval($item['price']);
            
            $insert_stmt->bind_param("iiii", $order_id, $product_id, $quantity, $price);
            
            if (!$insert_stmt->execute()) {
                $items_added = false;
                $insert_stmt->close();
                break;
            }
            $insert_stmt->close();
        }
        
        if ($items_added) {
            // Clear cart
            $delete_stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            if ($delete_stmt) {
                $delete_stmt->bind_param("i", $user_id);
                $delete_stmt->execute();
                $delete_stmt->close();
            }
            
            header('Location: order_confirmation.php?order_id=' . $order_id);
            exit;
        } else {
            header('Location: cart.php?error=Failed to add order items');
            exit;
        }
    } else {
        header('Location: cart.php?error=Failed to create order');
        exit;
    }
}

// Get cart items
$stmt = $conn->prepare("SELECT c.*, p.name, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$user_id = intval($_SESSION['user_id']);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}
$stmt->close();

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}
$delivery_fee = 50;
$grand_total = $total + $delivery_fee;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Indian NGO Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="bi bi-house-heart"></i> Indian NGO MS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php">Product Selling</a></li>
                    <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="py-5">
        <div class="container">
            <h1 class="text-center mb-4">Checkout</h1>
            
            <div class="row">
                <div class="col-md-6">
                    <h3>Order Summary</h3>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-sm">
                                <tbody>
                                    <?php foreach ($cart_items as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?></td>
                                        <td class="text-end">₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr class="table-active">
                                        <td><strong>Subtotal</strong></td>
                                        <td class="text-end"><strong>₹<?php echo number_format($total, 2); ?></strong></td>
                                    </tr>
                                    <tr class="table-active">
                                        <td><strong>Delivery Fee</strong></td>
                                        <td class="text-end"><strong>₹<?php echo number_format($delivery_fee, 2); ?></strong></td>
                                    </tr>
                                    <tr class="table-active">
                                        <td><strong>Total</strong></td>
                                        <td class="text-end"><strong class="fs-5">₹<?php echo number_format($grand_total, 2); ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h3>Delivery Information</h3>
                    <div class="card">
                        <div class="card-body">
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                            <p><strong>City:</strong> <?php echo htmlspecialchars($user['city']); ?></p>
                            <p><strong>State:</strong> <?php echo htmlspecialchars($user['state']); ?></p>
                            
                            <hr>
                            
                            <h4>Payment Method</h4>
                            <p>Please pay using any of these methods:</p>
                            <ul>
                                <li>Credit/Debit Card</li>
                                <li>Net Banking</li>
                                <li>UPI</li>
                                <li>Wallet</li>
                            </ul>
                            
                            <form method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <button type="submit" class="btn btn-success w-100 btn-lg">
                                    <i class="bi bi-check-circle"></i> Place Order
                                </button>
                            </form>
                            
                            <a href="cart.php" class="btn btn-secondary w-100 mt-2">Back to Cart</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p>&copy; 2024 Indian NGO Management System. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

