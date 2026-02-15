<?php
require_once 'auth.php';

$message = '';
$error = '';

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Security token mismatch. Please try again.';
    } else {
        $donor_name = $_POST['donorName'] ?? '';
        $donor_email = $_POST['donorEmail'] ?? '';
        $donor_phone = $_POST['donorPhone'] ?? '';
        $donation_amount = floatval($_POST['donationAmount'] ?? 0);
        $donation_cause = $_POST['donationCause'] ?? '';
        $payment_method = $_POST['paymentMethod'] ?? '';
        $is_anonymous = isset($_POST['anonymous']) ? 1 : 0;
        
        // Validate inputs
        if ($donation_amount < 100) {
            $error = 'Minimum donation amount is ₹100';
        } elseif ($donation_amount > 1000000) {
            $error = 'Maximum donation amount is ₹1,000,000';
        } elseif (!filter_var($donor_email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email address';
        } elseif (empty($donation_cause)) {
            $error = 'Please select a cause';
        } else {
            $user_id = isLoggedIn() ? intval($_SESSION['user_id']) : null;
            $transaction_id = 'TXN-' . time() . '-' . rand(10000, 99999);
            
            // Use prepared statement
            global $conn;
            $stmt = $conn->prepare("INSERT INTO donations (user_id, amount, cause, payment_method, payment_status, transaction_id, is_anonymous, donation_date) VALUES (?, ?, ?, ?, 'completed', ?, ?, NOW())");
            
            if ($stmt) {
                $stmt->bind_param("idsssi", $user_id, $donation_amount, $donation_cause, $payment_method, $transaction_id, $is_anonymous);
                
                if ($stmt->execute()) {
                    $message = 'Thank you for your generous donation of ₹' . number_format($donation_amount, 2) . '! Transaction ID: ' . htmlspecialchars($transaction_id);
                    $stmt->close();
                } else {
                    $error = 'Failed to process donation. Please try again.';
                    $stmt->close();
                }
            } else {
                $error = 'Database error. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate Now - Indian NGO Management System</title>
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
                    <li class="nav-item"><a class="nav-link active" href="donate.php">Donate Now</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php">Product Selling</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item"><a class="nav-link" href="#">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="cart.php">
                            <i class="bi bi-cart3"></i>
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" id="cart-count">0</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="py-5">
        <div class="container">
            <h1 class="text-center mb-4">Make a Donation</h1>
            <p class="text-center mb-4">Your generous donation helps us support education, healthcare, women empowerment, and rural development initiatives across India.</p>
            
            <?php if ($message): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Donation Form</h5>
                            <form method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <div class="mb-3">
                                    <label for="donorName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="donorName" name="donorName" value="<?php echo isLoggedIn() ? htmlspecialchars($_SESSION['user_name']) : ''; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="donorEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="donorEmail" name="donorEmail" value="<?php echo isLoggedIn() ? htmlspecialchars($_SESSION['user_email']) : ''; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="donorPhone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="donorPhone" name="donorPhone" required>
                                </div>
                                <div class="mb-3">
                                    <label for="donationAmount" class="form-label">Donation Amount (₹ INR)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" class="form-control" id="donationAmount" name="donationAmount" min="100" required>
                                    </div>
                                    <div class="form-text">Minimum donation: ₹100</div>
                                </div>
                                <div class="mb-3">
                                    <label for="donationCause" class="form-label">Cause</label>
                                    <select class="form-select" id="donationCause" name="donationCause" required>
                                        <option value="">Select Cause</option>
                                        <option>Education</option>
                                        <option>Healthcare</option>
                                        <option>Women Empowerment</option>
                                        <option>Rural Development</option>
                                        <option>General Fund</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="paymentMethod" class="form-label">Payment Method</label>
                                    <select class="form-select" id="paymentMethod" name="paymentMethod" required>
                                        <option value="">Select Payment Method</option>
                                        <option>Credit Card</option>
                                        <option>Debit Card</option>
                                        <option>Net Banking</option>
                                        <option>UPI</option>
                                        <option>PayPal</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="anonymous" name="anonymous">
                                        <label class="form-check-label" for="anonymous">
                                            Make this donation anonymous
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Donate Now</button>
                            </form>
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

