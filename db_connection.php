<?php
require_once 'config.php';

// Create connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
} catch (mysqli_sql_exception $e) {
    $host = DB_HOST . ':' . DB_PORT;
    $message = "Database connection failed. "
        . "Please ensure MySQL is running in XAMPP and the host/port are correct (" . $host . "). "
        . "Error: " . $e->getMessage();
    die($message);
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");

// Function to sanitize input
function sanitize($input) {
    global $conn;
    return $conn->real_escape_string(trim($input));
}

// Function to execute query
function executeQuery($query) {
    global $conn;
    return $conn->query($query);
}

// Function to get single row
function getRow($query) {
    global $conn;
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

// Function to get all rows
function getRows($query) {
    global $conn;
    $result = $conn->query($query);
    $rows = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    return $rows;
}

// Function to insert and get last ID
function insertData($query) {
    global $conn;
    if ($conn->query($query) === true) {
        return $conn->insert_id;
    }
    return 0;
}

// Improved function using prepared statements
function executeStmt($query, $types, $values) {
    global $conn;
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param($types, ...$values);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// Get row using prepared statement
function getRowStmt($query, $types, $values) {
    global $conn;
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return null;
    }
    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = null;
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
    $stmt->close();
    return $row;
}

// Get rows using prepared statement
function getRowsStmt($query, $types, $values) {
    global $conn;
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return [];
    }
    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    $stmt->close();
    return $rows;
}

// Validate email format
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Validate phone number (Indian format)
function validatePhone($phone) {
    return preg_match('/^[6-9]\d{9}$/', $phone);
}

// Validate password strength
function validatePassword($password) {
    return strlen($password) >= 6;
}
?>
