<?php
require_once 'auth.php';
requireLogin();

$user = getCurrentUser();
$donations = [];
$orders = [];
$volunteer_info = null;
global $conn;

// Get user donations
$stmt = $conn->prepare("SELECT id, amount, cause, payment_status, transaction_id, donation_date FROM donations WHERE user_id = ? ORDER BY donation_date DESC LIMIT 10");
if ($stmt) {
    $user_id = intval($user['id']);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $donations[] = $row;
    }
    $stmt->close();
}

// Get user orders
$stmt = $conn->prepare("SELECT id, total_amount, payment_status, delivery_status, order_date FROM orders WHERE user_id = ? ORDER BY order_date DESC LIMIT 10");
if ($stmt) {
    $user_id = intval($user['id']);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    $stmt->close();
}

// Get volunteer info if user is volunteer
if ($user['user_type'] === 'volunteer') {
    $stmt = $conn->prepare("SELECT * FROM volunteers WHERE user_id = ?");
    if ($stmt) {
        $user_id = intval($user['id']);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $volunteer_info = $result->fetch_assoc();
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Indian NGO Management System</title>
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
                    <li class="nav-item"><a class="nav-link" href="ngos.php">NGOs</a></li>
                    <li class="nav-item"><a class="nav-link active" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <i class="bi bi-person-circle" style="font-size: 4rem; color: #007bff;"></i>
                            <h5 class="card-title mt-3"><?php echo htmlspecialchars($user['name']); ?></h5>
                            <p class="card-text text-muted"><?php echo ucfirst($user['user_type']); ?></p>
                            <hr>
                            <p class="small">
                                <strong>Email:</strong><br>
                                <?php echo htmlspecialchars($user['email']); ?>
                            </p>
                            <p class="small">
                                <strong>Phone:</strong><br>
                                <?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?>
                            </p>
                            <p class="small">
                                <strong>Location:</strong><br>
                                <?php echo htmlspecialchars(($user['city'] ?? '') . ', ' . ($user['state'] ?? '')); ?>
                            </p>
                            <p class="small">
                                <strong>Member Since:</strong><br>
                                <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="donations-tab" data-bs-toggle="tab" data-bs-target="#donations" type="button" role="tab">
                                <i class="bi bi-gift"></i> Donations
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">
                                <i class="bi bi-bag"></i> Orders
                            </button>
                        </li>
                        <?php if ($volunteer_info): ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="volunteer-tab" data-bs-toggle="tab" data-bs-target="#volunteer" type="button" role="tab">
                                <i class="bi bi-people"></i> Volunteer Info
                            </button>
                        </li>
                        <?php endif; ?>
                    </ul>

                    <div class="tab-content">
                        <!-- Donations Tab -->
                        <div class="tab-pane fade show active" id="donations" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Your Donations</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($donations)): ?>
                                        <p class="text-muted">No donations yet. <a href="donate.php">Make a donation</a></p>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Amount</th>
                                                        <th>Cause</th>
                                                        <th>Status</th>
                                                        <th>Transaction ID</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($donations as $donation): ?>
                                                    <tr>
                                                        <td><?php echo date('M d, Y', strtotime($donation['donation_date'])); ?></td>
                                                        <td><strong>₹<?php echo number_format($donation['amount'], 2); ?></strong></td>
                                                        <td><?php echo htmlspecialchars($donation['cause']); ?></td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $donation['payment_status'] === 'completed' ? 'success' : 'warning'; ?>">
                                                                <?php echo ucfirst($donation['payment_status']); ?>
                                                            </span>
                                                        </td>
                                                        <td><code><?php echo htmlspecialchars($donation['transaction_id']); ?></code></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Orders Tab -->
                        <div class="tab-pane fade" id="orders" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Your Orders</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($orders)): ?>
                                        <p class="text-muted">No orders yet. <a href="products.php">Shop now</a></p>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Order ID</th>
                                                        <th>Date</th>
                                                        <th>Amount</th>
                                                        <th>Payment</th>
                                                        <th>Delivery</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($orders as $order): ?>
                                                    <tr>
                                                        <td>#<?php echo $order['id']; ?></td>
                                                        <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                                        <td><strong>₹<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $order['payment_status'] === 'completed' ? 'success' : 'warning'; ?>">
                                                                <?php echo ucfirst($order['payment_status']); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-<?php echo in_array($order['delivery_status'], ['shipped', 'delivered']) ? 'info' : 'secondary'; ?>">
                                                                <?php echo ucfirst($order['delivery_status']); ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Volunteer Info Tab -->
                        <?php if ($volunteer_info): ?>
                        <div class="tab-pane fade" id="volunteer" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Volunteer Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Status:</strong> <span class="badge bg-<?php echo $volunteer_info['status'] === 'active' ? 'success' : 'warning'; ?>"><?php echo ucfirst($volunteer_info['status']); ?></span></p>
                                            <p><strong>Volunteer Since:</strong> <?php echo $volunteer_info['volunteer_since'] ? date('M d, Y', strtotime($volunteer_info['volunteer_since'])) : 'Pending'; ?></p>
                                            <p><strong>Hours Contributed:</strong> <?php echo $volunteer_info['hours_contributed']; ?> hours</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Preferred Location:</strong> <?php echo htmlspecialchars($volunteer_info['preferred_location']); ?></p>
                                            <p><strong>Preferred Cause:</strong> <?php echo htmlspecialchars($volunteer_info['preferred_cause']); ?></p>
                                            <p><strong>Availability:</strong> <?php echo htmlspecialchars($volunteer_info['availability']); ?></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <p><strong>Experience:</strong></p>
                                    <p><?php echo htmlspecialchars($volunteer_info['experience']); ?></p>
                                    <hr>
                                    <p><strong>Skills:</strong></p>
                                    <p><?php echo htmlspecialchars($volunteer_info['skills']); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
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

