<?php
require_once 'auth.php';

$projects = getRows("SELECT p.*, n.name as ngo_name FROM projects p 
                     JOIN ngos n ON p.ngo_id = n.id 
                     WHERE p.status != 'completed' 
                     ORDER BY p.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - Indian NGO Management System</title>
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
                    <li class="nav-item"><a class="nav-link active" href="projects.php">Projects</a></li>
                    <li class="nav-item"><a class="nav-link" href="volunteers.php">Volunteers</a></li>
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
            <h1 class="text-center mb-4">Ongoing Projects</h1>
            <p class="text-center mb-5">Support our initiatives creating real impact across India</p>
            
            <div class="row">
                <?php foreach ($projects as $project): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($project['name']); ?></h5>
                            <p class="text-muted">by <?php echo htmlspecialchars($project['ngo_name']); ?></p>
                            <p class="card-text"><?php echo htmlspecialchars($project['description']); ?></p>
                            
                            <ul class="list-unstyled small mb-3">
                                <li><strong>Category:</strong> <?php echo htmlspecialchars($project['category']); ?></li>
                                <li><strong>Location:</strong> <?php echo htmlspecialchars($project['location']); ?></li>
                                <li><strong>Status:</strong> 
                                    <span class="badge bg-info"><?php echo htmlspecialchars($project['status']); ?></span>
                                </li>
                            </ul>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small>Funding Progress</small>
                                    <small>₹<?php echo number_format($project['raised_amount'], 0); ?>/₹<?php echo number_format($project['target_amount'], 0); ?></small>
                                </div>
                                <div class="progress">
                                    <?php 
                                    $percentage = ($project['target_amount'] > 0) ? ($project['raised_amount'] / $project['target_amount']) * 100 : 0;
                                    ?>
                                    <div class="progress-bar" role="progressbar" style="width: <?php echo min($percentage, 100); ?>%"></div>
                                </div>
                            </div>
                            
                            <a href="donate.php" class="btn btn-primary btn-sm">Donate Now</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (empty($projects)): ?>
                <div class="alert alert-info text-center">
                    No active projects found.
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

