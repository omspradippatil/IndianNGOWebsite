<?php
require_once 'auth.php';
$current_user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indian NGO Management System - Home</title>
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
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" id="cart-count">
                                <?php 
                                if (isLoggedIn()) {
                                    $cart_count = 0;
                                    $result = executeQuery("SELECT SUM(quantity) as total FROM cart WHERE user_id = " . $_SESSION['user_id']);
                                    if ($result) {
                                        $row = $result->fetch_assoc();
                                        if ($row && $row['total']) {
                                            $cart_count = $row['total'];
                                        }
                                    }
                                    echo $cart_count;
                                } else {
                                    echo "0";
                                }
                                ?>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero text-center">
        <div class="container">
            <h1>Welcome to Indian NGO Management System</h1>
            <p class="lead">Empowering communities across India through education, healthcare, women empowerment, and rural development.</p>
            <a href="donate.php" class="btn btn-light btn-lg">Donate Now</a>
            <a href="volunteers.php" class="btn btn-outline-light btn-lg ms-2">Become a Volunteer</a>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Our Key Causes</h2>
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card cause-card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-book display-4 text-primary"></i>
                            <h5 class="card-title">Education</h5>
                            <p class="card-text">Providing quality education to underprivileged children in rural India.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card cause-card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-heart-pulse display-4 text-danger"></i>
                            <h5 class="card-title">Healthcare</h5>
                            <p class="card-text">Improving healthcare access in remote villages across Indian states.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card cause-card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-people display-4 text-success"></i>
                            <h5 class="card-title">Women Empowerment</h5>
                            <p class="card-text">Empowering women through skill development and entrepreneurship programs.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card cause-card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-tree display-4 text-warning"></i>
                            <h5 class="card-title">Rural Development</h5>
                            <p class="card-text">Sustainable development initiatives for rural communities in India.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
        <div class="container text-center">
            <h2 class="mb-4">Our Impact</h2>
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="display-4 fw-bold">500+</div>
                    <p>NGOs Supported</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="display-4 fw-bold">100k+</div>
                    <p>Lives Impacted</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="display-4 fw-bold">₹10Cr+</div>
                    <p>Funds Distributed</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="display-4 fw-bold">5000+</div>
                    <p>Active Volunteers</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Featured NGOs</h2>
            <div class="row">
                <?php
                $ngos = getRows("SELECT * FROM ngos WHERE active = 1 LIMIT 3");
                foreach ($ngos as $ngo):
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($ngo['name']); ?></h5>
                            <p class="card-text"><?php echo substr(htmlspecialchars($ngo['description']), 0, 100) . '...'; ?></p>
                            <p><strong>Focus:</strong> <?php echo htmlspecialchars($ngo['focus_area']); ?></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($ngo['city'] . ', ' . $ngo['state']); ?></p>
                            <a href="ngo_detail.php?id=<?php echo $ngo['id']; ?>" class="btn btn-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4">
                <a href="ngos.php" class="btn btn-primary">View All NGOs</a>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p>&copy; 2024 Indian NGO Management System. All rights reserved.</p>
            <p>Empowering communities through transparency and collaboration.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
