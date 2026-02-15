<?php
require_once 'auth.php';

$error = '';
$success = '';

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Security token mismatch. Please try again.';
    } else {
        $name = $_POST['regName'] ?? '';
        $email = $_POST['regEmail'] ?? '';
        $phone = $_POST['regPhone'] ?? '';
        $password = $_POST['regPassword'] ?? '';
        $confirm_password = $_POST['regConfirmPassword'] ?? '';
        $city = $_POST['regCity'] ?? '';
        $state = $_POST['regState'] ?? '';
        $user_type = $_POST['regType'] ?? 'donor';
        
        $result = registerUser($name, $email, $password, $confirm_password, $phone, $city, $state, $user_type);
        
        if ($result['success']) {
            $success = $result['message'];
            echo '<script>setTimeout(function() { window.location.href = "login.php"; }, 2000);</script>';
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Indian NGO Management System</title>
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
                    <li class="nav-item"><a class="nav-link active" href="register.php">Register</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
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
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="card-title text-center mb-4">Register</h1>
                            
                            <?php if ($error): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo htmlspecialchars($error); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($success): ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo htmlspecialchars($success); ?> Redirecting to login...
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="regName" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="regName" name="regName" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="regEmail" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="regEmail" name="regEmail" required>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="regPhone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="regPhone" name="regPhone" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="regPassword" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="regPassword" name="regPassword" required>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="regConfirmPassword" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" id="regConfirmPassword" name="regConfirmPassword" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="regCity" class="form-label">City</label>
                                        <select class="form-select" id="regCity" name="regCity" required>
                                            <option value="">Select City</option>
                                            <option>Mumbai</option>
                                            <option>Delhi</option>
                                            <option>Kolkata</option>
                                            <option>Chennai</option>
                                            <option>Bangalore</option>
                                            <option>Jaipur</option>
                                            <option>Hyderabad</option>
                                            <option>Pune</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="regState" class="form-label">State</label>
                                        <select class="form-select" id="regState" name="regState" required>
                                            <option value="">Select State</option>
                                            <option>Maharashtra</option>
                                            <option>Delhi</option>
                                            <option>West Bengal</option>
                                            <option>Tamil Nadu</option>
                                            <option>Karnataka</option>
                                            <option>Rajasthan</option>
                                            <option>Telangana</option>
                                            <option>Gujarat</option>
                                            <option>Uttar Pradesh</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="regType" class="form-label">I am registering as</label>
                                        <select class="form-select" id="regType" name="regType" required>
                                            <option value="donor">Donor</option>
                                            <option value="volunteer">Volunteer</option>
                                            <option value="ngo">NGO</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100">Register</button>
                            </form>
                            
                            <hr>
                            <p class="text-center">Already have an account? <a href="login.php">Login here</a></p>
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

