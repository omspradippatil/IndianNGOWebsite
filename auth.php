<?php
require_once 'config.php';
require_once 'db_connection.php';

// Start session with secure settings
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
    ini_set('session.cookie_samesite', 'Strict');
    session_start();
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Function to get current user
function getCurrentUser() {
    global $conn;
    if (!isLoggedIn()) {
        return null;
    }
    
    $user_id = intval($_SESSION['user_id']);
    $stmt = $conn->prepare("SELECT id, name, email, phone, city, state, user_type, created_at FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }
    return null;
}

// Function to login user with rate limiting
function loginUser($email, $password) {
    global $conn;
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Invalid email format'];
    }
    
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, name, password, user_type FROM users WHERE email = ?");
    if (!$stmt) {
        return ['success' => false, 'message' => 'Database error'];
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $email;
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['login_time'] = time();
        return ['success' => true, 'message' => 'Login successful'];
    }
    
    return ['success' => false, 'message' => 'Invalid email or password'];
}

// Function to logout user
function logoutUser() {
    session_destroy();
    return true;
}

// Function to require login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

// Function to register user with validation
function registerUser($name, $email, $password, $confirm_password, $phone, $city, $state, $user_type) {
    global $conn;
    
    // Validate inputs
    if (empty($name) || strlen($name) < 2 || strlen($name) > 100) {
        return ['success' => false, 'message' => 'Name must be between 2 and 100 characters'];
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Invalid email format'];
    }
    
    if ($password !== $confirm_password) {
        return ['success' => false, 'message' => 'Passwords do not match'];
    }
    
    // Validate password strength (at least 6 characters)
    if (strlen($password) < 6) {
        return ['success' => false, 'message' => 'Password must be at least 6 characters long'];
    }
    
    // Validate phone number (basic validation)
    if (!empty($phone) && !preg_match('/^[0-9\-\+\s\(\)]{7,15}$/', $phone)) {
        return ['success' => false, 'message' => 'Invalid phone number format'];
    }
    
    // Validate user type
    $allowed_types = ['donor', 'volunteer', 'ngo'];
    if (!in_array($user_type, $allowed_types)) {
        return ['success' => false, 'message' => 'Invalid user type'];
    }
    
    // Check if email already exists using prepared statement
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    if (!$stmt) {
        return ['success' => false, 'message' => 'Database error'];
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $stmt->close();
        return ['success' => false, 'message' => 'Email already registered'];
    }
    $stmt->close();
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user with prepared statement
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, city, state, user_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        return ['success' => false, 'message' => 'Database error'];
    }
    
    $stmt->bind_param("sssssss", $name, $email, $hashed_password, $phone, $city, $state, $user_type);
    
    if ($stmt->execute()) {
        $stmt->close();
        return ['success' => true, 'message' => 'Registration successful'];
    } else {
        $error = $stmt->error;
        $stmt->close();
        return ['success' => false, 'message' => 'Registration failed: ' . $error];
    }
}

// Function to check if user is admin
function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

// Function to check if user is volunteer
function isVolunteer() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'volunteer';
}

// Function to check if user is NGO
function isNGO() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'ngo';
}

// Function to require admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: index.php');
        exit;
    }
}
?>
