<?php
require_once 'auth.php';
requireAdmin();

global $conn;
$action = $_GET['action'] ?? 'dashboard';
$page = intval($_GET['page'] ?? 1);
$per_page = 20;
$offset = ($page - 1) * $per_page;

$total_users = 0;
$total_donations = 0;
$total_orders = 0;
$data = [];

// Get statistics
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM users");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total_users = $row['count'];
    $stmt->close();
}

$stmt = $conn->prepare("SELECT COUNT(*) as count, SUM(amount) as total_amount FROM donations");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total_donations = $row['count'];
    $total_donation_amount = $row['total_amount'] ?? 0;
    $stmt->close();
}

$stmt = $conn->prepare("SELECT COUNT(*) as count, SUM(total_amount) as total_amount FROM orders");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total_orders = $row['count'];
    $total_order_amount = $row['total_amount'] ?? 0;
    $stmt->close();
}

// Load data based on action
if ($action === 'users') {
    $stmt = $conn->prepare("SELECT id, name, email, user_type, city, created_at FROM users ORDER BY created_at DESC LIMIT ?, ?");
    if ($stmt) {
        $stmt->bind_param("ii", $offset, $per_page);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Indian NGO Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .sidebar {
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            min-height: calc(100vh - 56px);
            padding: 20px;
        }
        .stat-card {
            border-left: 4px solid #007bff;
            padding: 20px;
            background: white;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .stat-card.danger {
            border-left-color: #dc3545;
        }
        .stat-card.success {
            border-left-color: #28a745;
        }
        .stat-card.warning {
            border-left-color: #ffc107;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><i class="bi bi-house-heart"></i> Indian NGO MS - Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Website</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <h5 class="mb-4">Admin Menu</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a href="admin.php?action=dashboard" class="text-decoration-none">Dashboard</a></li>
                    <li class="list-group-item"><a href="admin.php?action=users" class="text-decoration-none">Users</a></li>
                    <li class="list-group-item"><a href="admin.php?action=donations" class="text-decoration-none">Donations</a></li>
                    <li class="list-group-item"><a href="admin.php?action=orders" class="text-decoration-none">Orders</a></li>
                    <li class="list-group-item"><a href="admin.php?action=volunteers" class="text-decoration-none">Volunteers</a></li>
                    <li class="list-group-item"><a href="admin.php?action=messages" class="text-decoration-none">Messages</a></li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 p-4">
                <?php if ($action === 'dashboard'): ?>
                    <h2 class="mb-4">Dashboard</h2>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h6 class="text-muted mb-2">Total Users</h6>
                                <h3><?php echo $total_users; ?></h3>
                                <small class="text-muted">Registered members</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card danger">
                                <h6 class="text-muted mb-2">Total Donations</h6>
                                <h3><?php echo $total_donations; ?></h3>
                                <small class="text-muted">₹<?php echo number_format($total_donation_amount, 0); ?></small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card success">
                                <h6 class="text-muted mb-2">Total Orders</h6>
                                <h3><?php echo $total_orders; ?></h3>
                                <small class="text-muted">₹<?php echo number_format($total_order_amount, 0); ?></small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card warning">
                                <h6 class="text-muted mb-2">Revenue</h6>
                                <h3>₹<?php echo number_format($total_order_amount + $total_donation_amount, 0); ?></h3>
                                <small class="text-muted">Total raised</small>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Recent Users</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Type</th>
                                                <th>Joined</th>
                                            </tr>
                                            <?php
                                            $stmt = $conn->prepare("SELECT name, email, user_type, created_at FROM users ORDER BY created_at DESC LIMIT 5");
                                            if ($stmt) {
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<tr>';
                                                    echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                                                    echo '<td><small>' . htmlspecialchars($row['email']) . '</small></td>';
                                                    echo '<td><span class="badge bg-primary">' . ucfirst($row['user_type']) . '</span></td>';
                                                    echo '<td><small>' . date('M d', strtotime($row['created_at'])) . '</small></td>';
                                                    echo '</tr>';
                                                }
                                                $stmt->close();
                                            }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Recent Donations</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <tr>
                                                <th>Amount</th>
                                                <th>Cause</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                            <?php
                                            $stmt = $conn->prepare("SELECT amount, cause, payment_status, donation_date FROM donations ORDER BY donation_date DESC LIMIT 5");
                                            if ($stmt) {
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<tr>';
                                                    echo '<td><strong>₹' . number_format($row['amount'], 2) . '</strong></td>';
                                                    echo '<td><small>' . htmlspecialchars($row['cause']) . '</small></td>';
                                                    echo '<td><span class="badge bg-' . ($row['payment_status'] === 'completed' ? 'success' : 'warning') . '">' . ucfirst($row['payment_status']) . '</span></td>';
                                                    echo '<td><small>' . date('M d', strtotime($row['donation_date'])) . '</small></td>';
                                                    echo '</tr>';
                                                }
                                                $stmt->close();
                                            }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php elseif ($action === 'users'): ?>
                    <h2 class="mb-4">Manage Users</h2>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Type</th>
                                            <th>City</th>
                                            <th>Joined</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data as $user): ?>
                                        <tr>
                                            <td><?php echo $user['id']; ?></td>
                                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td><span class="badge bg-primary"><?php echo ucfirst($user['user_type']); ?></span></td>
                                            <td><?php echo htmlspecialchars($user['city'] ?? 'N/A'); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-info" disabled>View</button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <h2 class="mb-4">Coming Soon</h2>
                    <p>This section is under development.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

