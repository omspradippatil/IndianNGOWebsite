<?php
require_once 'auth.php';

$id = intval($_GET['id'] ?? 0);
$ngo = getRow("SELECT * FROM ngos WHERE id = $id");

if (!$ngo) {
    header('Location: ngos.php');
    exit;
}

$projects = getRows("SELECT * FROM projects WHERE ngo_id = $id ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($ngo['name']); ?> - Indian NGO Management System</title>
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
                    <li class="nav-item"><a class="nav-link" href="ngos.php">NGOs</a></li>
                    <li class="nav-item"><a class="nav-link" href="donate.php">Donate</a></li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <section class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-8">
                    <h1><?php echo htmlspecialchars($ngo['name']); ?></h1>
                    <p class="lead"><?php echo htmlspecialchars($ngo['description']); ?></p>
                </div>
                <div class="col-md-4">
                    <a href="donate.php" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-heart"></i> Donate Now
                    </a>
                </div>
            </div>
            
            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Organization Details</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><strong>Focus Area:</strong> <?php echo htmlspecialchars($ngo['focus_area']); ?></li>
                                <li class="mb-2"><strong>Location:</strong> <?php echo htmlspecialchars($ngo['address']); ?></li>
                                <li class="mb-2"><strong>City/State:</strong> <?php echo htmlspecialchars($ngo['city'] . ', ' . $ngo['state']); ?></li>
                                <li class="mb-2"><strong>Founded:</strong> <?php echo $ngo['founded_year']; ?></li>
                                <li class="mb-2"><strong>Registration #:</strong> <?php echo htmlspecialchars($ngo['registration_number']); ?></li>
                                <li class="mb-2"><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($ngo['email']); ?>"><?php echo htmlspecialchars($ngo['email']); ?></a></li>
                                <li class="mb-2"><strong>Phone:</strong> <?php echo htmlspecialchars($ngo['phone']); ?></li>
                                <?php if ($ngo['website']): ?>
                                <li class="mb-2"><strong>Website:</strong> <a href="<?php echo htmlspecialchars($ngo['website']); ?>" target="_blank"><?php echo htmlspecialchars($ngo['website']); ?></a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">About This Organization</h5>
                            <p>This NGO is dedicated to making a meaningful impact in the area of <strong><?php echo htmlspecialchars($ngo['focus_area']); ?></strong>. With a team of passionate individuals, they have been working since <?php echo $ngo['founded_year']; ?> to bring positive change to communities across <?php echo htmlspecialchars($ngo['state']); ?>.</p>
                            <p>Your support helps them continue their mission and expand their reach to more people in need.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <h2 class="mb-4">Active Projects</h2>
            <div class="row">
                <?php foreach ($projects as $project): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($project['name']); ?></h5>
                            <p class="card-text"><?php echo substr(htmlspecialchars($project['description']), 0, 100); ?>...</p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($project['location']); ?></p>
                            <a href="donate.php" class="btn btn-primary btn-sm">Support This Project</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php if (empty($projects)): ?>
                <div class="col-12">
                    <div class="alert alert-info">No active projects at the moment.</div>
                </div>
                <?php endif; ?>
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

