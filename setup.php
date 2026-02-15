<?php
require_once 'config.php';

// Create connection (without database name first)
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// Create database if it doesn't exist
$sql_db = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql_db) === true) {
    echo "Database created successfully or already exists.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select database
$conn->select_db(DB_NAME);

// Create tables
$tables = [
    // Users Table
    "CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        phone VARCHAR(20),
        city VARCHAR(50),
        state VARCHAR(50),
        user_type ENUM('donor', 'volunteer', 'ngo', 'admin') DEFAULT 'donor',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_email (email),
        INDEX idx_user_type (user_type)
    )",
    
    // NGOs Table
    "CREATE TABLE IF NOT EXISTS ngos (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(150) NOT NULL UNIQUE,
        description TEXT,
        email VARCHAR(100),
        phone VARCHAR(20),
        website VARCHAR(255),
        address TEXT,
        city VARCHAR(50),
        state VARCHAR(50),
        registration_number VARCHAR(50) UNIQUE,
        focus_area VARCHAR(100),
        founded_year INT,
        image_path VARCHAR(255),
        active BOOLEAN DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_city (city),
        INDEX idx_state (state),
        INDEX idx_focus_area (focus_area)
    )",
    
    // Projects Table
    "CREATE TABLE IF NOT EXISTS projects (
        id INT PRIMARY KEY AUTO_INCREMENT,
        ngo_id INT NOT NULL,
        name VARCHAR(150) NOT NULL,
        description TEXT,
        category VARCHAR(50),
        target_amount DECIMAL(12, 2),
        raised_amount DECIMAL(12, 2) DEFAULT 0,
        start_date DATE,
        end_date DATE,
        location VARCHAR(150),
        status ENUM('ongoing', 'completed', 'upcoming') DEFAULT 'ongoing',
        image_path VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (ngo_id) REFERENCES ngos(id) ON DELETE CASCADE,
        INDEX idx_category (category),
        INDEX idx_status (status)
    )",
    
    // Donations Table
    "CREATE TABLE IF NOT EXISTS donations (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT,
        project_id INT,
        amount DECIMAL(12, 2) NOT NULL,
        cause VARCHAR(100),
        payment_method VARCHAR(50),
        payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
        transaction_id VARCHAR(100) UNIQUE,
        is_anonymous BOOLEAN DEFAULT 0,
        donation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
        FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL,
        INDEX idx_user_id (user_id),
        INDEX idx_project_id (project_id),
        INDEX idx_donation_date (donation_date),
        INDEX idx_payment_status (payment_status)
    )",
    
    // Volunteers Table
    "CREATE TABLE IF NOT EXISTS volunteers (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL UNIQUE,
        experience VARCHAR(500),
        skills TEXT,
        availability VARCHAR(100),
        preferred_location VARCHAR(100),
        preferred_cause VARCHAR(100),
        certification_details VARCHAR(255),
        volunteer_since DATE,
        hours_contributed INT DEFAULT 0,
        status ENUM('active', 'inactive', 'pending') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_status (status),
        INDEX idx_preferred_cause (preferred_cause)
    )",
    
    // Products Table
    "CREATE TABLE IF NOT EXISTS products (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(150) NOT NULL,
        description TEXT,
        category VARCHAR(50),
        price DECIMAL(10, 2) NOT NULL,
        image_path VARCHAR(255),
        stock_quantity INT DEFAULT 0,
        profit_percentage DECIMAL(5, 2) DEFAULT 100,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_category (category),
        INDEX idx_price (price)
    )",
    
    // Cart Table
    "CREATE TABLE IF NOT EXISTS cart (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT DEFAULT 1,
        added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        UNIQUE KEY unique_cart_item (user_id, product_id)
    )",
    
    // Orders Table
    "CREATE TABLE IF NOT EXISTS orders (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        total_amount DECIMAL(10, 2) NOT NULL,
        payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
        delivery_status ENUM('pending', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        delivery_date DATE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_user_id (user_id),
        INDEX idx_payment_status (payment_status),
        INDEX idx_order_date (order_date)
    )",
    
    // Order Items Table
    "CREATE TABLE IF NOT EXISTS order_items (
        id INT PRIMARY KEY AUTO_INCREMENT,
        order_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        unit_price DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        INDEX idx_order_id (order_id)
    )",
    
    // Contact Messages Table
    "CREATE TABLE IF NOT EXISTS contact_messages (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        subject VARCHAR(200),
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status ENUM('new', 'read', 'replied') DEFAULT 'new',
        INDEX idx_created_at (created_at),
        INDEX idx_status (status)
    )"
];

// Execute table creation
foreach ($tables as $sql) {
    if ($conn->query($sql) === true) {
        echo "Table created/exists successfully.<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
}

// Insert sample data if tables are empty
echo "<br><h3>Inserting Sample Data...</h3>";

// Check if ngos table is empty
$result = $conn->query("SELECT COUNT(*) as count FROM ngos");
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    // Insert sample NGOs
    $ngos = [
        ["Helping Hands NGO", "Dedicated to education and healthcare", "contact@helpinghands.org", "+91-9876543210", "www.helpinghands.org", "Mumbai, Maharashtra", "Mumbai", "Maharashtra", "REG-001", "Education", 2005, ""],
        ["Rural India Foundation", "Empowering rural communities", "info@ruralind.org", "+91-9876543211", "www.ruralind.org", "Delhi", "Delhi", "Delhi", "REG-002", "Rural Development", 2008, ""],
        ["Women Empowerment Trust", "Supporting women entrepreneurs", "contact@womenempowerment.org", "+91-9876543212", "www.womenempowerment.org", "Bangalore", "Bangalore", "Karnataka", "REG-003", "Women Empowerment", 2010, ""],
        ["Healthcare for All", "Providing healthcare to remote areas", "info@healthcareforall.org", "+91-9876543213", "www.healthcareforall.org", "Kolkata", "Kolkata", "West Bengal", "REG-004", "Healthcare", 2012, ""]
    ];
    
    foreach ($ngos as $ngo) {
        $sql = "INSERT INTO ngos (name, description, email, phone, website, address, city, state, registration_number, focus_area, founded_year, image_path) 
                VALUES ('$ngo[0]', '$ngo[1]', '$ngo[2]', '$ngo[3]', '$ngo[4]', '$ngo[5]', '$ngo[6]', '$ngo[7]', '$ngo[8]', '$ngo[9]', $ngo[10], '')";
        $conn->query($sql);
    }
    echo "Sample NGOs inserted.<br>";
}

// Check if products table is empty
$result = $conn->query("SELECT COUNT(*) as count FROM products");
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    // Insert sample products
    $products = [
        ["NGO T-Shirt", "High-quality cotton t-shirt with NGO logo", "Apparel", 299, "", 100, 100],
        ["NGO Cap", "Branded cap supporting our cause", "Apparel", 199, "", 75, 100],
        ["NGO Mug", "Coffee mug with NGO branding", "Merchandise", 149, "", 50, 100],
        ["Educational Books Set", "Set of books for children in rural areas", "Books", 599, "", 30, 100],
        ["NGO Notebook", "Eco-friendly notebook", "Stationery", 99, "", 200, 100],
        ["NGO Pen Pack", "Pack of 10 quality pens", "Stationery", 149, "", 150, 100]
    ];
    
    foreach ($products as $product) {
        $sql = "INSERT INTO products (name, description, category, price, image_path, stock_quantity, profit_percentage) 
                VALUES ('$product[0]', '$product[1]', '$product[2]', $product[3], '$product[4]', $product[5], $product[6])";
        $conn->query($sql);
    }
    echo "Sample products inserted.<br>";
}

echo "<br><h3>Database Setup Complete!</h3>";
echo "<p>You can now delete or disable this setup file for security.</p>";

$conn->close();
?>
