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
    } elseif (!isLoggedIn()) {
        header('Location: login.php?redirect=volunteers.php');
        exit;
    } else {
        $user_id = intval($_SESSION['user_id']);
        global $conn;
        
        // Check if user is already registered as volunteer
        $check_stmt = $conn->prepare("SELECT id FROM volunteers WHERE user_id = ?");
        if (!$check_stmt) {
            $error = 'Database error';
        } else {
            $check_stmt->bind_param("i", $user_id);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            $existing = $result->fetch_assoc();
            $check_stmt->close();
            
            if ($existing) {
                $error = 'You are already registered as a volunteer';
            } else {
                $experience = $_POST['experience'] ?? '';
                $skills = $_POST['skills'] ?? '';
                $availability = $_POST['availability'] ?? '';
                $preferred_location = $_POST['preferred_location'] ?? '';
                $preferred_cause = $_POST['preferred_cause'] ?? '';
                
                // Validate inputs
                if (strlen($experience) < 10) {
                    $error = 'Experience must be at least 10 characters';
                } elseif (strlen($skills) < 5) {
                    $error = 'Skills must be at least 5 characters';
                } else {
                    // Insert volunteer registration
                    $insert_stmt = $conn->prepare("INSERT INTO volunteers (user_id, experience, skills, availability, preferred_location, preferred_cause, volunteer_since, status) VALUES (?, ?, ?, ?, ?, ?, NOW(), 'pending')");
                    
                    if (!$insert_stmt) {
                        $error = 'Database error';
                    } else {
                        $insert_stmt->bind_param("isssss", $user_id, $experience, $skills, $availability, $preferred_location, $preferred_cause);
                        
                        if ($insert_stmt->execute()) {
                            $message = 'Your volunteer registration is submitted and pending approval!';
                            $insert_stmt->close();
                        } else {
                            $error = 'Failed to register as volunteer: ' . $insert_stmt->error;
                            $insert_stmt->close();
                        }
                    }
                }
            }
        }
    }
}

$volunteer_opportunities = [
    ['title' => 'Teaching Assistant', 'location' => 'Mumbai', 'description' => 'Help educate children in Mumbai slums', 'hours' => '10-15 hours/week'],
    ['title' => 'Healthcare Volunteer', 'location' => 'Rural Areas', 'description' => 'Assist in healthcare camps and awareness programs', 'hours' => 'flexible'],
    ['title' => 'Women Empowerment Trainer', 'location' => 'Pan-India', 'description' => 'Train women in skill development programs', 'hours' => '8-12 hours/week'],
    ['title' => 'Field Coordinator', 'location' => 'Various States', 'description' => 'Coordinate rural development projects', 'hours' => '20 hours/week']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteers - Indian NGO Management System</title>
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
                    <li class="nav-item"><a class="nav-link active" href="volunteers.php">Volunteers</a></li>
                    <li class="nav-item"><a class="nav-link" href="donate.php">Donate Now</a></li>
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
            <h1 class="text-center mb-4">Become a Volunteer</h1>
            <p class="text-center mb-4">Join our community of volunteers making a difference across India.</p>
            
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-6">
                    <h3>Volunteer Opportunities</h3>
                    <div class="list-group">
                        <?php foreach ($volunteer_opportunities as $opp): ?>
                        <div class="list-group-item">
                            <h5><?php echo htmlspecialchars($opp['title']); ?></h5>
                            <p class="mb-2"><strong>Location:</strong> <?php echo htmlspecialchars($opp['location']); ?></p>
                            <p class="mb-2"><strong>Description:</strong> <?php echo htmlspecialchars($opp['description']); ?></p>
                            <p class="mb-0"><strong>Hours:</strong> <?php echo htmlspecialchars($opp['hours']); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h3>Register as Volunteer</h3>
                    <div class="card">
                        <div class="card-body">
                            <?php if (!isLoggedIn()): ?>
                                <p class="text-center">Please <a href="login.php">login</a> or <a href="register.php">register</a> first</p>
                            <?php else: ?>
                                <form method="POST">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                    <div class="mb-3">
                                        <label for="experience" class="form-label">Professional Experience</label>
                                        <textarea class="form-control" id="experience" name="experience" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="skills" class="form-label">Skills</label>
                                        <textarea class="form-control" id="skills" name="skills" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="availability" class="form-label">Availability</label>
                                        <select class="form-select" id="availability" name="availability" required>
                                            <option value="">Select Availability</option>
                                            <option>Full Time</option>
                                            <option>Part Time</option>
                                            <option>Weekends Only</option>
                                            <option>Flexible</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="preferred_location" class="form-label">Preferred Location</label>
                                        <input type="text" class="form-control" id="preferred_location" name="preferred_location" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="preferred_cause" class="form-label">Preferred Cause</label>
                                        <select class="form-select" id="preferred_cause" name="preferred_cause" required>
                                            <option value="">Select Cause</option>
                                            <option>Education</option>
                                            <option>Healthcare</option>
                                            <option>Women Empowerment</option>
                                            <option>Rural Development</option>
                                            <option>Environmental</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Register as Volunteer</button>
                                </form>
                            <?php endif; ?>
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

