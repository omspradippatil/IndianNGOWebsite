<?php
require_once 'auth.php';

$ngos = getRows("SELECT * FROM ngos WHERE active = 1 ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGOs - Indian NGO Management System</title>
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
                    <li class="nav-item"><a class="nav-link active" href="ngos.php">NGOs</a></li>
                    <li class="nav-item"><a class="nav-link" href="projects.php">Projects</a></li>
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
            <h1 class="text-center mb-4">Partner NGOs</h1>
            <p class="text-center mb-5">Discover the NGOs working towards social change across India</p>
            
            <div class="row">
                <?php foreach ($ngos as $ngo): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($ngo['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($ngo['description']); ?></p>
                            
                            <ul class="list-unstyled">
                                <li><strong>Focus:</strong> <?php echo htmlspecialchars($ngo['focus_area']); ?></li>
                                <li><strong>Location:</strong> <?php echo htmlspecialchars($ngo['city'] . ', ' . $ngo['state']); ?></li>
                                <li><strong>Founded:</strong> <?php echo $ngo['founded_year']; ?></li>
                                <li><strong>Email:</strong> <?php echo htmlspecialchars($ngo['email']); ?></li>
                                <li><strong>Phone:</strong> <?php echo htmlspecialchars($ngo['phone']); ?></li>
                                <?php if ($ngo['website']): ?>
                                <li><strong>Website:</strong> <a href="<?php echo htmlspecialchars($ngo['website']); ?>" target="_blank"><?php echo htmlspecialchars($ngo['website']); ?></a></li>
                                <?php endif; ?>
                            </ul>
                            
                            <a href="ngo_detail.php?id=<?php echo $ngo['id']; ?>" class="btn btn-primary btn-sm mt-3">View Details</a>
                            <a href="donate.php" class="btn btn-success btn-sm mt-3">Donate</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (empty($ngos)): ?>
                <div class="alert alert-info text-center">
                    No NGOs found.
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

