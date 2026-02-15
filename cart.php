<?php
require_once 'auth.php';
requireLogin();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $user_id = $_SESSION['user_id'];
    
    // Check if product exists in cart
    $existing = getRow("SELECT id, quantity FROM cart WHERE user_id = $user_id AND product_id = $product_id");
    
    if ($existing) {
        // Update quantity
        executeQuery("UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id");
        $message = 'Product quantity updated in cart';
    } else {
        // Add to cart
        $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)";
        if (executeQuery($query)) {
            $message = 'Product added to cart';
        } else {
            $error = 'Failed to add to cart';
        }
    }
}

// Get cart items
$cart_items = getRows("SELECT c.*, p.name, p.price, p.stock_quantity FROM cart c 
                       JOIN products p ON c.product_id = p.id 
                       WHERE c.user_id = " . $_SESSION['user_id']);

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Handle remove from cart
if (isset($_GET['remove_id'])) {
    $remove_id = intval($_GET['remove_id']);
    executeQuery("DELETE FROM cart WHERE id = $remove_id AND user_id = " . $_SESSION['user_id']);
    header('Location: cart.php');
    exit;
}

// Handle update quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    foreach ($_POST['update_quantity'] as $cart_id => $quantity) {
        $cart_id = intval($cart_id);
        $quantity = intval($quantity);
        if ($quantity > 0) {
            executeQuery("UPDATE cart SET quantity = $quantity WHERE id = $cart_id AND user_id = " . $_SESSION['user_id']);
        }
    }
    header('Location: cart.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Indian NGO Management System</title>
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
                    <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="ngos.php">NGOs</a></li>
                    <li class="nav-item"><a class="nav-link" href="projects.php">Projects</a></li>
                    <li class="nav-item"><a class="nav-link" href="volunteers.php">Volunteers</a></li>
                    <li class="nav-item"><a class="nav-link" href="donate.php">Donate Now</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php">Product Selling</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <li class="nav-item">
                        <a class="nav-link position-relative active" href="cart.php">
                            <i class="bi bi-cart3"></i>
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" id="cart-count"><?php echo count($cart_items); ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="py-5">
        <div class="container">
            <h1 class="text-center mb-4">Shopping Cart</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (empty($cart_items)): ?>
                <div class="alert alert-info text-center">
                    Your cart is empty. <a href="products.php">Continue shopping</a>
                </div>
            <?php else: ?>
                <div class="table-responsive mb-4">
                    <form method="POST">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                    <td>
                                        <input type="number" name="update_quantity[<?php echo $item['id']; ?>]" 
                                               value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock_quantity']; ?>" 
                                               class="form-control" style="width: 80px;">
                                    </td>
                                    <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    <td>
                                        <a href="cart.php?remove_id=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Update Cart</button>
                    </form>
                </div>
                
                <div class="row">
                    <div class="col-md-6 ms-auto">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Order Summary</h5>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Subtotal:</span>
                                    <strong>₹<?php echo number_format($total, 2); ?></strong>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Delivery Fee:</span>
                                    <strong>₹50</strong>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-4">
                                    <span class="fs-5">Total:</span>
                                    <strong class="fs-5">₹<?php echo number_format($total + 50, 2); ?></strong>
                                </div>
                                <a href="checkout.php" class="btn btn-success w-100">Proceed to Checkout</a>
                                <a href="products.php" class="btn btn-secondary w-100 mt-2">Continue Shopping</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
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

