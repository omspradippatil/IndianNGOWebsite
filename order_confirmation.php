<?php
require_once 'auth.php';
requireLogin();

$order_id = intval($_GET['order_id'] ?? 0);
$order = getRow("SELECT * FROM orders WHERE id = $order_id AND user_id = " . $_SESSION['user_id']);

if (!$order) {
    header('Location: index.php');
    exit;
}

$order_items = getRows("SELECT oi.*, p.name FROM order_items oi 
                        JOIN products p ON oi.product_id = p.id 
                        WHERE oi.order_id = $order_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Indian NGO Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="bi bi-house-heart"></i> Indian NGO MS</a>
        </div>
    </nav>

    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <i class="bi bi-check-circle text-success display-1"></i>
                <h1 class="mt-3">Order Confirmed!</h1>
                <p class="text-muted">Thank you for your purchase. Your order has been received.</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Details</h5>
                            <hr>
                            <p><strong>Order ID:</strong> #<?php echo $order['id']; ?></p>
                            <p><strong>Order Date:</strong> <?php echo date('d M Y H:i', strtotime($order['order_date'])); ?></p>
                            <p><strong>Status:</strong> <span class="badge bg-warning"><?php echo htmlspecialchars($order['payment_status']); ?></span></p>
                            
                            <h6 class="mt-4 mb-3">Items Ordered</h6>
                            <table class="table table-sm">
                                <tbody>
                                    <?php foreach ($order_items as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?></td>
                                        <td class="text-end">₹<?php echo number_format($item['unit_price'] * $item['quantity'], 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total Amount:</strong>
                                <strong>₹<?php echo number_format($order['total_amount'], 2); ?></strong>
                            </div>
                            
                            <div class="alert alert-info mt-4">
                                <i class="bi bi-info-circle"></i> An order confirmation has been sent to your registered email address. You can track your order status in your account.
                            </div>
                            
                            <a href="index.php" class="btn btn-primary w-100">Continue Shopping</a>
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

