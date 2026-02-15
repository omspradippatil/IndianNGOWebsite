<?php
require_once 'auth.php';
require_once 'db_connection.php';
requireAdmin();

global $conn;
$action = $_GET['action'] ?? 'dashboard';
$page = intval($_GET['page'] ?? 1);
$per_page = 20;
$offset = ($page - 1) * $per_page;

$data = [];
$total_records = 0;
$message = '';
$alert_type = '';

// Handle delete operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $operation = $_POST['operation'] ?? '';
    
    if ($operation === 'delete_user') {
        $user_id = intval($_POST['user_id']);
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND user_type != 'admin'");
        if ($stmt) {
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()) {
                $message = "User deleted successfully";
                $alert_type = "success";
            } else {
                $message = "Error deleting user";
                $alert_type = "danger";
            }
            $stmt->close();
        }
    } elseif ($operation === 'delete_donation') {
        $donation_id = intval($_POST['donation_id']);
        $stmt = $conn->prepare("DELETE FROM donations WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $donation_id);
            if ($stmt->execute()) {
                $message = "Donation record deleted";
                $alert_type = "success";
            }
            $stmt->close();
        }
    } elseif ($operation === 'delete_message') {
        $msg_id = intval($_POST['message_id']);
        $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $msg_id);
            if ($stmt->execute()) {
                $message = "Message deleted";
                $alert_type = "success";
            }
            $stmt->close();
        }
    } elseif ($operation === 'mark_message_read') {
        $msg_id = intval($_POST['message_id']);
        $status = $_POST['status'] ?? 'read';
        $stmt = $conn->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("si", $status, $msg_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Get overall statistics
function getStats($conn) {
    $stats = [];
    
    $queries = [
        'total_users' => "SELECT COUNT(*) as count FROM users",
        'total_donations' => "SELECT COUNT(*) as count, SUM(amount) as total_amount FROM donations WHERE payment_status = 'completed'",
        'total_orders' => "SELECT COUNT(*) as count, SUM(total_amount) as total_amount FROM orders WHERE payment_status = 'completed'",
        'total_volunteers' => "SELECT COUNT(*) as count FROM volunteers WHERE status = 'active'",
        'total_ngos' => "SELECT COUNT(*) as count FROM ngos WHERE active = 1",
        'total_projects' => "SELECT COUNT(*) as count FROM projects WHERE status = 'ongoing'",
        'pending_messages' => "SELECT COUNT(*) as count FROM contact_messages WHERE status = 'new'",
        'low_stock_products' => "SELECT COUNT(*) as count FROM products WHERE stock_quantity < 10"
    ];
    
    foreach ($queries as $key => $query) {
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stats[$key] = $row;
            $stmt->close();
        }
    }
    
    return $stats;
}

$stats = getStats($conn);

// Load data based on action
if ($action === 'users') {
    $search = $_GET['search'] ?? '';
    $filter = $_GET['filter'] ?? 'all';
    
    $query = "SELECT id, name, email, user_type, city, created_at FROM users WHERE 1=1";
    if (!empty($search)) {
        $query .= " AND (name LIKE ? OR email LIKE ?)";
    }
    if ($filter !== 'all') {
        $query .= " AND user_type = ?";
    }
    $query .= " ORDER BY created_at DESC LIMIT ?, ?";
    
    if (!empty($search)) {
        $search_term = '%' . $search . '%';
        if ($filter !== 'all') {
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE (name LIKE ? OR email LIKE ?) AND user_type = ?");
            $stmt->bind_param("sss", $search_term, $search_term, $filter);
        } else {
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE name LIKE ? OR email LIKE ?");
            $stmt->bind_param("ss", $search_term, $search_term);
        }
    } else {
        if ($filter !== 'all') {
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE user_type = ?");
            $stmt->bind_param("s", $filter);
        } else {
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users");
        }
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $total_records = $result->fetch_assoc()['count'];
    $stmt->close();
    
    $stmt = $conn->prepare($query);
    if (!empty($search)) {
        if ($filter !== 'all') {
            $stmt->bind_param("sssii", $search_term, $search_term, $filter, $offset, $per_page);
        } else {
            $stmt->bind_param("ssii", $search_term, $search_term, $offset, $per_page);
        }
    } else {
        if ($filter !== 'all') {
            $stmt->bind_param("sii", $filter, $offset, $per_page);
        } else {
            $stmt->bind_param("ii", $offset, $per_page);
        }
    }
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
}

if ($action === 'donations') {
    $search = $_GET['search'] ?? '';
    $filter = $_GET['filter'] ?? 'all';
    
    $query = "SELECT d.id, d.user_id, u.name, d.amount, d.cause, d.payment_status, d.donation_date FROM donations d LEFT JOIN users u ON d.user_id = u.id WHERE 1=1";
    
    if (!empty($search)) {
        $query .= " AND (u.name LIKE ? OR d.cause LIKE ?)";
    }
    if ($filter !== 'all') {
        $query .= " AND d.payment_status = ?";
    }
    $query .= " ORDER BY d.donation_date DESC LIMIT ?, ?";
    
    if (!empty($search)) {
        $search_term = '%' . $search . '%';
        if ($filter !== 'all') {
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM donations d LEFT JOIN users u ON d.user_id = u.id WHERE (u.name LIKE ? OR d.cause LIKE ?) AND d.payment_status = ?");
            $stmt->bind_param("sss", $search_term, $search_term, $filter);
        } else {
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM donations d LEFT JOIN users u ON d.user_id = u.id WHERE u.name LIKE ? OR d.cause LIKE ?");
            $stmt->bind_param("ss", $search_term, $search_term);
        }
    } else {
        if ($filter !== 'all') {
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM donations WHERE payment_status = ?");
            $stmt->bind_param("s", $filter);
        } else {
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM donations");
        }
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $total_records = $result->fetch_assoc()['count'];
    $stmt->close();
    
    $stmt = $conn->prepare($query);
    if (!empty($search)) {
        if ($filter !== 'all') {
            $stmt->bind_param("sssii", $search_term, $search_term, $filter, $offset, $per_page);
        } else {
            $stmt->bind_param("ssii", $search_term, $search_term, $offset, $per_page);
        }
    } else {
        if ($filter !== 'all') {
            $stmt->bind_param("sii", $filter, $offset, $per_page);
        } else {
            $stmt->bind_param("ii", $offset, $per_page);
        }
    }
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
}

if ($action === 'orders') {
    $search = $_GET['search'] ?? '';
    $filter = $_GET['filter'] ?? 'all';
    
    $query = "SELECT o.id, o.user_id, u.name, u.email, o.total_amount, o.payment_status, o.delivery_status, o.order_date FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE 1=1";
    
    if (!empty($search)) {
        $query .= " AND (u.name LIKE ? OR u.email LIKE ?)";
    }
    if ($filter !== 'all') {
        if (strpos($filter, 'payment_') === 0) {
            $status = str_replace('payment_', '', $filter);
            $query .= " AND o.payment_status = ?";
        } else {
            $query .= " AND o.delivery_status = ?";
        }
    }
    $query .= " ORDER BY o.order_date DESC LIMIT ?, ?";
    
    if (!empty($search)) {
        $search_term = '%' . $search . '%';
        if ($filter !== 'all') {
            if (strpos($filter, 'payment_') === 0) {
                $status = str_replace('payment_', '', $filter);
                $stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE (u.name LIKE ? OR u.email LIKE ?) AND o.payment_status = ?");
                $stmt->bind_param("sss", $search_term, $search_term, $status);
            } else {
                $stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE (u.name LIKE ? OR u.email LIKE ?) AND o.delivery_status = ?");
                $stmt->bind_param("sss", $search_term, $search_term, $filter);
            }
        } else {
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE u.name LIKE ? OR u.email LIKE ?");
            $stmt->bind_param("ss", $search_term, $search_term);
        }
    } else {
        if ($filter !== 'all') {
            if (strpos($filter, 'payment_') === 0) {
                $status = str_replace('payment_', '', $filter);
                $stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE payment_status = ?");
                $stmt->bind_param("s", $status);
            } else {
                $stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders WHERE delivery_status = ?");
                $stmt->bind_param("s", $filter);
            }
        } else {
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders");
        }
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $total_records = $result->fetch_assoc()['count'];
    $stmt->close();
    
    $stmt = $conn->prepare($query);
    if (!empty($search)) {
        if ($filter !== 'all') {
            if (strpos($filter, 'payment_') === 0) {
                $status = str_replace('payment_', '', $filter);
                $stmt->bind_param("sssii", $search_term, $search_term, $status, $offset, $per_page);
            } else {
                $stmt->bind_param("sssii", $search_term, $search_term, $filter, $offset, $per_page);
            }
        } else {
            $stmt->bind_param("ssii", $search_term, $search_term, $offset, $per_page);
        }
    } else {
        if ($filter !== 'all') {
            if (strpos($filter, 'payment_') === 0) {
                $status = str_replace('payment_', '', $filter);
                $stmt->bind_param("sii", $status, $offset, $per_page);
            } else {
                $stmt->bind_param("sii", $filter, $offset, $per_page);
            }
        } else {
            $stmt->bind_param("ii", $offset, $per_page);
        }
    }
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
}

if ($action === 'messages') {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM contact_messages");
    $stmt->execute();
    $result = $stmt->get_result();
    $total_records = $result->fetch_assoc()['count'];
    $stmt->close();
    
    $stmt = $conn->prepare("SELECT id, name, email, subject, message, created_at, status FROM contact_messages ORDER BY created_at DESC LIMIT ?, ?");
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

if ($action === 'products') {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM products");
    $stmt->execute();
    $result = $stmt->get_result();
    $total_records = $result->fetch_assoc()['count'];
    $stmt->close();
    
    $stmt = $conn->prepare("SELECT id, name, price, stock_quantity, category, created_at FROM products ORDER BY created_at DESC LIMIT ?, ?");
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

$total_pages = ceil($total_records / $per_page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Indian NGO Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            color: #fff;
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 2px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h4 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sidebar-nav {
            padding: 20px 0;
        }
        
        .nav-item {
            list-style: none;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #ccc;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover {
            background-color: rgba(255,255,255,0.05);
            color: #fff;
            border-left-color: #0066ff;
        }
        
        .nav-link.active {
            background-color: rgba(0,102,255,0.1);
            color: #0066ff;
            border-left-color: #0066ff;
            font-weight: 600;
        }
        
        .nav-link i {
            font-size: 1.2rem;
            width: 20px;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
        }
        
        /* Top Bar */
        .topbar {
            background: #fff;
            padding: 20px 30px;
            border-bottom: 1px solid #e0e7ff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .topbar-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }
        
        .topbar-group {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .topbar-link {
            color: #666;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .topbar-link:hover {
            background-color: #f0f4ff;
            color: #0066ff;
        }
        
        /* Page Content */
        .page-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-left: 4px solid #0066ff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        
        .stat-card.success {
            border-left-color: #28a745;
        }
        
        .stat-card.danger {
            border-left-color: #dc3545;
        }
        
        .stat-card.warning {
            border-left-color: #ffc107;
        }
        
        .stat-card.info {
            border-left-color: #17a2b8;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        
        .stat-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .stat-detail {
            font-size: 0.875rem;
            color: #999;
        }
        
        /* Cards */
        .card {
            background: #fff;
            border: 1px solid #e0e7ff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .card-header {
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 1px solid #e0e7ff;
            border-radius: 10px 10px 0 0;
        }
        
        .card-header h5 {
            margin: 0;
            color: #2c3e50;
            font-weight: 600;
        }
        
        .card-body {
            padding: 20px;
        }
        
        /* Tables */
        .table {
            margin-bottom: 0;
            color: #2c3e50;
        }
        
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e0e7ff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            color: #666;
            padding: 12px;
        }
        
        .table tbody td {
            padding: 12px;
            border-bottom: 1px solid #f0f4ff;
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background-color: #fafbff;
        }
        
        /* Badges */
        .badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        /* Buttons */
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.85rem;
            border-radius: 5px;
        }
        
        .btn-action {
            padding: 6px 12px;
            margin: 0 3px;
            border-radius: 5px;
            border: none;
        }
        
        /* Search & Filter */
        .search-filter-bar {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .search-filter-bar input,
        .search-filter-bar select {
            flex: 1;
            min-width: 200px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.95rem;
        }
        
        .search-filter-bar button {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            background: #0066ff;
            color: white;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        
        .search-filter-bar button:hover {
            background: #0052cc;
        }
        
        /* Alert */
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            gap: 5px;
            justify-content: center;
            margin-top: 20px;
        }
        
        .pagination a, .pagination span {
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            color: #0066ff;
            border: 1px solid #ddd;
        }
        
        .pagination a:hover {
            background: #f0f4ff;
        }
        
        .pagination .active {
            background: #0066ff;
            color: white;
            border-color: #0066ff;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                position: fixed;
                left: 0;
                z-index: 1000;
            }
            
            .sidebar-header h4 span:not(.bi) {
                display: none;
            }
            
            .nav-link span:not(.bi) {
                display: none;
            }
            
            .nav-link {
                justify-content: center;
            }
            
            .main-content {
                margin-left: 70px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .search-filter-bar {
                flex-direction: column;
            }
            
            .search-filter-bar input,
            .search-filter-bar select {
                min-width: auto;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h4><i class="bi bi-house-heart"></i> <span>Admin</span></h4>
            </div>
            <ul class="sidebar-nav">
                <li class="nav-item">
                    <a href="admin.php?action=dashboard" class="nav-link <?php echo $action === 'dashboard' ? 'active' : ''; ?>">
                        <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?action=users" class="nav-link <?php echo $action === 'users' ? 'active' : ''; ?>">
                        <i class="bi bi-people"></i> <span>Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?action=donations" class="nav-link <?php echo $action === 'donations' ? 'active' : ''; ?>">
                        <i class="bi bi-heart-fill"></i> <span>Donations</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?action=orders" class="nav-link <?php echo $action === 'orders' ? 'active' : ''; ?>">
                        <i class="bi bi-bag-check"></i> <span>Orders</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?action=products" class="nav-link <?php echo $action === 'products' ? 'active' : ''; ?>">
                        <i class="bi bi-box"></i> <span>Products</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?action=messages" class="nav-link <?php echo $action === 'messages' ? 'active' : ''; ?>">
                        <i class="bi bi-chat-left-text"></i> <span>Messages</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?action=volunteers" class="nav-link <?php echo $action === 'volunteers' ? 'active' : ''; ?>">
                        <i class="bi bi-hand-thumbs-up"></i> <span>Volunteers</span>
                    </a>
                </li>
                <hr style="margin: 20px 0; opacity: 0.2;">
                <li class="nav-item">
                    <a href="profile.php" class="nav-link">
                        <i class="bi bi-person"></i> <span>Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">
                        <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="topbar">
                <h2 class="topbar-title">
                    <?php 
                    $titles = [
                        'dashboard' => 'Dashboard',
                        'users' => 'Manage Users',
                        'donations' => 'Donations',
                        'orders' => 'Orders',
                        'products' => 'Products',
                        'messages' => 'Messages',
                        'volunteers' => 'Volunteers'
                    ];
                    echo $titles[$action] ?? 'Admin Panel';
                    ?>
                </h2>
                <div class="topbar-group">
                    <a href="index.php" class="topbar-link"><i class="bi bi-globe"></i> Website</a>
                </div>
            </div>

            
            <!-- Page Content -->
            <div class="page-content">
                <!-- Alert Messages -->
                <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $alert_type; ?>" role="alert">
                    <i class="bi bi-info-circle"></i> <?php echo htmlspecialchars($message); ?>
                </div>
                <?php endif; ?>
                
                <!-- Dashboard -->
                <?php if ($action === 'dashboard'): ?>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-label">Total Users</div>
                            <div class="stat-value"><?php echo $stats['total_users']['count'] ?? 0; ?></div>
                            <div class="stat-detail"><i class="bi bi-arrow-up"></i> Registered members</div>
                        </div>
                        
                        <div class="stat-card success">
                            <div class="stat-label">Total Donations</div>
                            <div class="stat-value"><?php echo $stats['total_donations']['count'] ?? 0; ?></div>
                            <div class="stat-detail">₹<?php echo number_format($stats['total_donations']['total_amount'] ?? 0, 0); ?> raised</div>
                        </div>
                        
                        <div class="stat-card danger">
                            <div class="stat-label">Total Orders</div>
                            <div class="stat-value"><?php echo $stats['total_orders']['count'] ?? 0; ?></div>
                            <div class="stat-detail">₹<?php echo number_format($stats['total_orders']['total_amount'] ?? 0, 0); ?> revenue</div>
                        </div>
                        
                        <div class="stat-card warning">
                            <div class="stat-label">Active Projects</div>
                            <div class="stat-value"><?php echo $stats['total_projects']['count'] ?? 0; ?></div>
                            <div class="stat-detail">In progress</div>
                        </div>
                        
                        <div class="stat-card info">
                            <div class="stat-label">Active Volunteers</div>
                            <div class="stat-value"><?php echo $stats['total_volunteers']['count'] ?? 0; ?></div>
                            <div class="stat-detail">Registered</div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-label">Pending Messages</div>
                            <div class="stat-value"><?php echo $stats['pending_messages']['count'] ?? 0; ?></div>
                            <div class="stat-detail">Need attention</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="bi bi-people"></i> Recent Users</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Type</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $stmt = $conn->prepare("SELECT name, email, user_type FROM users ORDER BY created_at DESC LIMIT 8");
                                                if ($stmt) {
                                                    $stmt->execute();
                                                    $result = $stmt->get_result();
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo '<tr>';
                                                        echo '<td><strong>' . htmlspecialchars($row['name']) . '</strong></td>';
                                                        echo '<td><small>' . htmlspecialchars($row['email']) . '</small></td>';
                                                        echo '<td><span class="badge bg-primary">' . ucfirst($row['user_type']) . '</span></td>';
                                                        echo '</tr>';
                                                    }
                                                    $stmt->close();
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="bi bi-heart-fill"></i> Recent Donations</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Amount</th>
                                                    <th>Cause</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $stmt = $conn->prepare("SELECT amount, cause, payment_status FROM donations ORDER BY donation_date DESC LIMIT 8");
                                                if ($stmt) {
                                                    $stmt->execute();
                                                    $result = $stmt->get_result();
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo '<tr>';
                                                        echo '<td><strong>₹' . number_format($row['amount'], 2) . '</strong></td>';
                                                        echo '<td><small>' . htmlspecialchars($row['cause']) . '</small></td>';
                                                        $badge_color = $row['payment_status'] === 'completed' ? 'success' : ($row['payment_status'] === 'pending' ? 'warning' : 'danger');
                                                        echo '<td><span class="badge bg-' . $badge_color . '">' . ucfirst($row['payment_status']) . '</span></td>';
                                                        echo '</tr>';
                                                    }
                                                    $stmt->close();
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                <!-- Users Management -->
                <?php elseif ($action === 'users'): ?>
                    <div class="search-filter-bar">
                        <form method="GET" style="display: flex; gap: 10px; width: 100%; flex-wrap: wrap; align-items: center;">
                            <input type="hidden" name="action" value="users">
                            <input type="text" name="search" placeholder="Search by name or email..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" style="flex: 1; min-width: 250px;">
                            <select name="filter" style="min-width: 150px;">
                                <option value="all" <?php echo ($_GET['filter'] ?? 'all') === 'all' ? 'selected' : ''; ?>>All Types</option>
                                <option value="donor" <?php echo ($_GET['filter'] ?? '') === 'donor' ? 'selected' : ''; ?>>Donors</option>
                                <option value="volunteer" <?php echo ($_GET['filter'] ?? '') === 'volunteer' ? 'selected' : ''; ?>>Volunteers</option>
                                <option value="ngo" <?php echo ($_GET['filter'] ?? '') === 'ngo' ? 'selected' : ''; ?>>NGOs</option>
                            </select>
                            <button type="submit">Search</button>
                        </form>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
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
                                        <?php 
                                        if (count($data) > 0) {
                                            foreach ($data as $user): 
                                        ?>
                                        <tr>
                                            <td><small><?php echo $user['id']; ?></small></td>
                                            <td><strong><?php echo htmlspecialchars($user['name']); ?></strong></td>
                                            <td><small><?php echo htmlspecialchars($user['email']); ?></small></td>
                                            <td><span class="badge bg-primary"><?php echo ucfirst($user['user_type']); ?></span></td>
                                            <td><?php echo htmlspecialchars($user['city'] ?? 'N/A'); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                            <td>
                                                <?php if ($user['user_type'] !== 'admin') { ?>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="operation" value="delete_user">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Delete this user?');">Delete</button>
                                                </form>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php 
                                            endforeach;
                                        } else {
                                            echo '<tr><td colspan="7" class="text-center text-muted" style="padding: 30px;">No users found</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="admin.php?action=users&page=<?php echo $i; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&filter=<?php echo urlencode($_GET['filter'] ?? 'all'); ?>" 
                               class="<?php echo $i === $page ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                    <?php endif; ?>
                
                <!-- Donations Management -->
                <?php elseif ($action === 'donations'): ?>
                    <div class="search-filter-bar">
                        <form method="GET" style="display: flex; gap: 10px; width: 100%; flex-wrap: wrap; align-items: center;">
                            <input type="hidden" name="action" value="donations">
                            <input type="text" name="search" placeholder="Search by name or cause..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" style="flex: 1; min-width: 250px;">
                            <select name="filter" style="min-width: 150px;">
                                <option value="all" <?php echo ($_GET['filter'] ?? 'all') === 'all' ? 'selected' : ''; ?>>All Status</option>
                                <option value="completed" <?php echo ($_GET['filter'] ?? '') === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="pending" <?php echo ($_GET['filter'] ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="failed" <?php echo ($_GET['filter'] ?? '') === 'failed' ? 'selected' : ''; ?>>Failed</option>
                            </select>
                            <button type="submit">Search</button>
                        </form>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Donor</th>
                                            <th>Amount</th>
                                            <th>Cause</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if (count($data) > 0) {
                                            foreach ($data as $donation): 
                                        ?>
                                        <tr>
                                            <td><small><?php echo $donation['id']; ?></small></td>
                                            <td><?php echo $donation['user_id'] ? htmlspecialchars($donation['name'] ?? 'Anonymous') : 'Anonymous'; ?></td>
                                            <td><strong>₹<?php echo number_format($donation['amount'], 2); ?></strong></td>
                                            <td><?php echo htmlspecialchars($donation['cause'] ?? 'N/A'); ?></td>
                                            <td>
                                                <?php 
                                                $badge_color = $donation['payment_status'] === 'completed' ? 'success' : ($donation['payment_status'] === 'pending' ? 'warning' : 'danger');
                                                echo '<span class="badge bg-' . $badge_color . '">' . ucfirst($donation['payment_status']) . '</span>';
                                                ?>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($donation['donation_date'])); ?></td>
                                            <td>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="operation" value="delete_donation">
                                                    <input type="hidden" name="donation_id" value="<?php echo $donation['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Delete this donation record?');">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php 
                                            endforeach;
                                        } else {
                                            echo '<tr><td colspan="7" class="text-center text-muted" style="padding: 30px;">No donations found</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="admin.php?action=donations&page=<?php echo $i; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&filter=<?php echo urlencode($_GET['filter'] ?? 'all'); ?>" 
                               class="<?php echo $i === $page ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                    <?php endif; ?>
                
                <!-- Orders Management -->
                <?php elseif ($action === 'orders'): ?>
                    <div class="search-filter-bar">
                        <form method="GET" style="display: flex; gap: 10px; width: 100%; flex-wrap: wrap; align-items: center;">
                            <input type="hidden" name="action" value="orders">
                            <input type="text" name="search" placeholder="Search by customer..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" style="flex: 1; min-width: 250px;">
                            <select name="filter" style="min-width: 150px;">
                                <option value="all" <?php echo ($_GET['filter'] ?? 'all') === 'all' ? 'selected' : ''; ?>>All Orders</option>
                                <option value="pending" <?php echo ($_GET['filter'] ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="shipped" <?php echo ($_GET['filter'] ?? '') === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                <option value="delivered" <?php echo ($_GET['filter'] ?? '') === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="payment_completed" <?php echo ($_GET['filter'] ?? '') === 'payment_completed' ? 'selected' : ''; ?>>Payment Complete</option>
                            </select>
                            <button type="submit">Search</button>
                        </form>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Payment</th>
                                            <th>Delivery</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if (count($data) > 0) {
                                            foreach ($data as $order): 
                                        ?>
                                        <tr>
                                            <td><small><?php echo $order['id']; ?></small></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($order['name']); ?></strong><br>
                                                <small><?php echo htmlspecialchars($order['email']); ?></small>
                                            </td>
                                            <td><strong>₹<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                                            <td>
                                                <?php 
                                                $payment_color = $order['payment_status'] === 'completed' ? 'success' : ($order['payment_status'] === 'pending' ? 'warning' : 'danger');
                                                echo '<span class="badge bg-' . $payment_color . '">' . ucfirst($order['payment_status']) . '</span>';
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                $delivery_color = $order['delivery_status'] === 'delivered' ? 'success' : ($order['delivery_status'] === 'pending' ? 'warning' : 'info');
                                                echo '<span class="badge bg-' . $delivery_color . '">' . ucfirst($order['delivery_status']) . '</span>';
                                                ?>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                        </tr>
                                        <?php 
                                            endforeach;
                                        } else {
                                            echo '<tr><td colspan="6" class="text-center text-muted" style="padding: 30px;">No orders found</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="admin.php?action=orders&page=<?php echo $i; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&filter=<?php echo urlencode($_GET['filter'] ?? 'all'); ?>" 
                               class="<?php echo $i === $page ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                    <?php endif; ?>
                
                <!-- Messages Management -->
                <?php elseif ($action === 'messages'): ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>From</th>
                                            <th>Subject</th>
                                            <th>Message</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if (count($data) > 0) {
                                            foreach ($data as $msg): 
                                        ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($msg['name']); ?></strong><br>
                                                <small><?php echo htmlspecialchars($msg['email']); ?></small>
                                            </td>
                                            <td><?php echo htmlspecialchars($msg['subject'] ?? 'No Subject'); ?></td>
                                            <td><small><?php echo htmlspecialchars(substr($msg['message'], 0, 50)) . '...'; ?></small></td>
                                            <td>
                                                <?php 
                                                $status_color = $msg['status'] === 'new' ? 'warning' : ($msg['status'] === 'replied' ? 'success' : 'secondary');
                                                echo '<span class="badge bg-' . $status_color . '">' . ucfirst($msg['status']) . '</span>';
                                                ?>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($msg['created_at'])); ?></td>
                                            <td>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="operation" value="delete_message">
                                                    <input type="hidden" name="message_id" value="<?php echo $msg['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Delete this message?');">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php 
                                            endforeach;
                                        } else {
                                            echo '<tr><td colspan="6" class="text-center text-muted" style="padding: 30px;">No messages found</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="admin.php?action=messages&page=<?php echo $i; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                    <?php endif; ?>
                
                <!-- Products Management -->
                <?php elseif ($action === 'products'): ?>
                    <div class="card">
                        <div class="card-header">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <h5>Product Inventory</h5>
                                <small style="color: #666;">Low Stock: <?php echo $stats['low_stock_products']['count'] ?? 0; ?> items</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if (count($data) > 0) {
                                            foreach ($data as $product): 
                                                $stock_status = $product['stock_quantity'] < 10 ? 'warning' : 'success';
                                                $stock_text = $product['stock_quantity'] < 10 ? 'Low Stock' : 'In Stock';
                                        ?>
                                        <tr>
                                            <td><small><?php echo $product['id']; ?></small></td>
                                            <td><strong><?php echo htmlspecialchars($product['name']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                                            <td>₹<?php echo number_format($product['price'], 2); ?></td>
                                            <td><strong><?php echo $product['stock_quantity']; ?> units</strong></td>
                                            <td><span class="badge bg-<?php echo $stock_status; ?>"><?php echo $stock_text; ?></span></td>
                                        </tr>
                                        <?php 
                                            endforeach;
                                        } else {
                                            echo '<tr><td colspan="6" class="text-center text-muted" style="padding: 30px;">No products found</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="admin.php?action=products&page=<?php echo $i; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                    <?php endif; ?>
                
                <!-- Volunteers Management -->
                <?php elseif ($action === 'volunteers'): ?>
                    <div class="card">
                        <div class="card-header">
                            <h5>Volunteer Management</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Hours Contributed</th>
                                            <th>Since</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stmt = $conn->prepare("SELECT v.id, u.name, u.email, v.status, v.hours_contributed, v.volunteer_since FROM volunteers v JOIN users u ON v.user_id = u.id ORDER BY v.volunteer_since DESC LIMIT ?, ?");
                                        if ($stmt) {
                                            $stmt->bind_param("ii", $offset, $per_page);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $status_color = $row['status'] === 'active' ? 'success' : ($row['status'] === 'pending' ? 'warning' : 'secondary');
                                                    echo '<tr>';
                                                    echo '<td><small>' . $row['id'] . '</small></td>';
                                                    echo '<td><strong>' . htmlspecialchars($row['name']) . '</strong></td>';
                                                    echo '<td><small>' . htmlspecialchars($row['email']) . '</small></td>';
                                                    echo '<td><span class="badge bg-' . $status_color . '">' . ucfirst($row['status']) . '</span></td>';
                                                    echo '<td>' . ($row['hours_contributed'] ?? 0) . ' hours</td>';
                                                    echo '<td>' . ($row['volunteer_since'] ? date('M d, Y', strtotime($row['volunteer_since'])) : 'N/A') . '</td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="6" class="text-center text-muted" style="padding: 30px;">No volunteers found</td></tr>';
                                            }
                                            $stmt->close();
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card">
                        <div class="card-body">
                            <p class="text-center text-muted" style="padding: 40px;">This section is coming soon!</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

