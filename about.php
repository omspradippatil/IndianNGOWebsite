<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Indian NGO Management System</title>
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
                    <li class="nav-item"><a class="nav-link active" href="about.php">About Us</a></li>
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
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" id="cart-count">0</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="py-5">
        <div class="container">
            <h1 class="text-center mb-5">About Indian NGO Management System</h1>
            
            <div class="row mb-5">
                <div class="col-md-8 mx-auto">
                    <p class="lead text-center">We are a unified platform connecting NGOs, donors, volunteers, and communities to create meaningful social impact across India.</p>
                </div>
            </div>
            
            <div class="row mb-5">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-eye text-primary"></i> Our Vision</h5>
                            <p>To create a transparent ecosystem where NGOs can effectively deliver services, donors can contribute with confidence, and communities can access help when needed.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-target text-success"></i> Our Mission</h5>
                            <p>To empower civil society by providing a digital platform that facilitates collaboration, transparency, and accountability in the social sector.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <h2 class="mb-4">Our Core Values</h2>
            <div class="row mb-5">
                <div class="col-md-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="bi bi-hand-thumbs-up display-4 text-primary mb-3"></i>
                            <h5 class="card-title">Transparency</h5>
                            <p class="card-text">We believe in complete openness and honesty in all our operations.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="bi bi-people display-4 text-success mb-3"></i>
                            <h5 class="card-title">Accountability</h5>
                            <p class="card-text">We are answerable to our stakeholders for the impact we create.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="bi bi-heart display-4 text-danger mb-3"></i>
                            <h5 class="card-title">Impact</h5>
                            <p class="card-text">Every action we take is aimed at creating positive social change.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="bi bi-gear display-4 text-warning mb-3"></i>
                            <h5 class="card-title">Efficiency</h5>
                            <p class="card-text">We optimize resources to maximize the value delivered to communities.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <h2 class="mb-4">What We Do</h2>
            <div class="row mb-5">
                <div class="col-md-6 mb-4">
                    <h5>For Donors</h5>
                    <ul>
                        <li>Connect with vetted and transparent NGOs</li>
                        <li>Track the impact of your donations</li>
                        <li>Support specific projects and causes</li>
                        <li>Purchase socially-conscious products</li>
                    </ul>
                </div>
                
                <div class="col-md-6 mb-4">
                    <h5>For NGOs</h5>
                    <ul>
                        <li>Reach a wider audience of potential donors</li>
                        <li>Manage projects and volunteers efficiently</li>
                        <li>Track funding and impact metrics</li>
                        <li>Build credibility through our platform</li>
                    </ul>
                </div>
                
                <div class="col-md-6 mb-4">
                    <h5>For Volunteers</h5>
                    <ul>
                        <li>Find meaningful volunteer opportunities</li>
                        <li>Connect with like-minded individuals</li>
                        <li>Build experience and skills</li>
                        <li>Make a direct impact in communities</li>
                    </ul>
                </div>
                
                <div class="col-md-6 mb-4">
                    <h5>For Communities</h5>
                    <ul>
                        <li>Access quality services and support</li>
                        <li>Participate in development initiatives</li>
                        <li>Build sustainable livelihoods</li>
                        <li>Create positive social change</li>
                    </ul>
                </div>
            </div>
            
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="card-title">Join Us in Making a Difference</h5>
                    <p>Whether you're a donor, volunteer, NGO partner, or community member, there's a place for you in our ecosystem. Together, we can create lasting change across India.</p>
                    <a href="register.php" class="btn btn-primary">Get Started Today</a>
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

