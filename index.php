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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;600;700&family=Manrope:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="page-home">
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

    <section class="hero-home">
        <div class="hero-bg" aria-hidden="true">
            <span class="hero-orb orb-1"></span>
            <span class="hero-orb orb-2"></span>
            <span class="hero-orb orb-3"></span>
            <span class="hero-grid"></span>
        </div>
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="hero-tag">Transparent giving • Local impact</div>
                    <h1>Build brighter futures with India’s most trusted NGO network.</h1>
                    <p class="hero-lead">Support verified causes, track impact, and join a community that turns generosity into measurable change.</p>
                    <div class="hero-actions">
                        <a href="donate.php" class="btn btn-hero-primary">Donate Now</a>
                        <a href="volunteers.php" class="btn btn-hero-ghost">Become a Volunteer</a>
                    </div>
                    <div class="hero-trust">
                        <div>
                            <div class="trust-value">500+</div>
                            <div class="trust-label">Trusted NGOs</div>
                        </div>
                        <div>
                            <div class="trust-value">100k+</div>
                            <div class="trust-label">Lives Impacted</div>
                        </div>
                        <div>
                            <div class="trust-value">₹10Cr+</div>
                            <div class="trust-label">Funds Delivered</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-panel">
                        <div class="panel-header">Impact Snapshot</div>
                        <div class="panel-item">
                            <div>
                                <div class="panel-title">Education Kits</div>
                                <div class="panel-note">Rural Maharashtra</div>
                            </div>
                            <div class="panel-value">8,240</div>
                        </div>
                        <div class="panel-item">
                            <div>
                                <div class="panel-title">Health Camps</div>
                                <div class="panel-note">Uttar Pradesh</div>
                            </div>
                            <div class="panel-value">312</div>
                        </div>
                        <div class="panel-item">
                            <div>
                                <div class="panel-title">Women Trained</div>
                                <div class="panel-note">Karnataka</div>
                            </div>
                            <div class="panel-value">4,560</div>
                        </div>
                        <div class="panel-footer">
                            Updated weekly from partner NGOs.
                        </div>
                    </div>
                </div>
            </div>
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

    <section class="impact-band">
        <div class="container text-center">
            <h2 class="mb-4">Our Impact</h2>
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <div class="impact-stat">
                        <div class="stat-value">500+</div>
                        <div class="stat-label">NGOs Supported</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="impact-stat">
                        <div class="stat-value">100k+</div>
                        <div class="stat-label">Lives Impacted</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="impact-stat">
                        <div class="stat-value">₹10Cr+</div>
                        <div class="stat-label">Funds Distributed</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="impact-stat">
                        <div class="stat-value">5000+</div>
                        <div class="stat-label">Active Volunteers</div>
                    </div>
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
