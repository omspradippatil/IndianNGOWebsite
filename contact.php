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
        $name = $_POST['contactName'] ?? '';
        $email = $_POST['contactEmail'] ?? '';
        $phone = $_POST['contactPhone'] ?? '';
        $subject = $_POST['contactSubject'] ?? '';
        $message_text = $_POST['contactMessage'] ?? '';
        
        // Validate inputs
        if (empty($name) || strlen($name) < 2) {
            $error = 'Name must be at least 2 characters';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email address';
        } elseif (empty($subject) || strlen($subject) < 5) {
            $error = 'Subject must be at least 5 characters';
        } elseif (empty($message_text) || strlen($message_text) < 10) {
            $error = 'Message must be at least 10 characters';
        } else {
            global $conn;
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
            
            if ($stmt) {
                $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message_text);
                
                if ($stmt->execute()) {
                    $message = 'Thank you for reaching out! We will get back to you soon.';
                    $stmt->close();
                } else {
                    $error = 'Failed to send message. Please try again.';
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
    <title>Contact Us - Indian NGO Management System</title>
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
                    <li class="nav-item"><a class="nav-link active" href="contact.php">Contact Us</a></li>
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
            <h1 class="text-center mb-4">Contact Us</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h3>Get in Touch</h3>
                    <div class="card">
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <div class="mb-3">
                                    <label for="contactName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="contactName" name="contactName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="contactEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="contactEmail" name="contactEmail" required>
                                </div>
                                <div class="mb-3">
                                    <label for="contactPhone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="contactPhone" name="contactPhone">
                                </div>
                                <div class="mb-3">
                                    <label for="contactSubject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="contactSubject" name="contactSubject" required>
                                </div>
                                <div class="mb-3">
                                    <label for="contactMessage" class="form-label">Message</label>
                                    <textarea class="form-control" id="contactMessage" name="contactMessage" rows="5" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h3>Contact Information</h3>
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-4">
                                <h5><i class="bi bi-geo-alt"></i> Address</h5>
                                <p>Indian NGO Management System<br>
                                New Delhi, India<br>
                                India 110001</p>
                            </div>
                            <div class="mb-4">
                                <h5><i class="bi bi-telephone"></i> Phone</h5>
                                <p>+91-9876543210<br>
                                +91-9876543211</p>
                            </div>
                            <div class="mb-4">
                                <h5><i class="bi bi-envelope"></i> Email</h5>
                                <p><a href="mailto:contact@indianngo.org">contact@indianngo.org</a><br>
                                <a href="mailto:support@indianngo.org">support@indianngo.org</a></p>
                            </div>
                            <div>
                                <h5><i class="bi bi-clock"></i> Working Hours</h5>
                                <p>Monday - Friday: 9:00 AM - 6:00 PM<br>
                                Saturday: 10:00 AM - 4:00 PM<br>
                                Sunday: Closed</p>
                            </div>
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

