-- ============================================
-- Indian NGO Management System Database
-- Database Name: om
-- Generated: February 14, 2026
-- ============================================

-- Create Database
CREATE DATABASE IF NOT EXISTS `om` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `om`;

-- ============================================
-- Table: users
-- Purpose: Store user accounts (donors, volunteers, NGOs, admins)
-- ============================================
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20),
    `city` VARCHAR(50),
    `state` VARCHAR(50),
    `user_type` ENUM('donor', 'volunteer', 'ngo', 'admin') DEFAULT 'donor',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_email` (`email`),
    INDEX `idx_user_type` (`user_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: ngos
-- Purpose: Store NGO organization details
-- ============================================
CREATE TABLE IF NOT EXISTS `ngos` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(150) NOT NULL UNIQUE,
    `description` TEXT,
    `email` VARCHAR(100),
    `phone` VARCHAR(20),
    `website` VARCHAR(255),
    `address` TEXT,
    `city` VARCHAR(50),
    `state` VARCHAR(50),
    `registration_number` VARCHAR(50) UNIQUE,
    `focus_area` VARCHAR(100),
    `founded_year` INT,
    `image_path` VARCHAR(255),
    `active` BOOLEAN DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_city` (`city`),
    INDEX `idx_state` (`state`),
    INDEX `idx_focus_area` (`focus_area`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: projects
-- Purpose: Store projects run by NGOs
-- ============================================
CREATE TABLE IF NOT EXISTS `projects` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `ngo_id` INT NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `description` TEXT,
    `category` VARCHAR(50),
    `target_amount` DECIMAL(12, 2),
    `raised_amount` DECIMAL(12, 2) DEFAULT 0,
    `start_date` DATE,
    `end_date` DATE,
    `location` VARCHAR(150),
    `status` ENUM('ongoing', 'completed', 'upcoming') DEFAULT 'ongoing',
    `image_path` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`ngo_id`) REFERENCES `ngos`(`id`) ON DELETE CASCADE,
    INDEX `idx_category` (`category`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: donations
-- Purpose: Store donation records
-- ============================================
CREATE TABLE IF NOT EXISTS `donations` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT,
    `project_id` INT,
    `amount` DECIMAL(12, 2) NOT NULL,
    `cause` VARCHAR(100),
    `payment_method` VARCHAR(50),
    `payment_status` ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    `transaction_id` VARCHAR(100) UNIQUE,
    `is_anonymous` BOOLEAN DEFAULT 0,
    `donation_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE SET NULL,
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_project_id` (`project_id`),
    INDEX `idx_donation_date` (`donation_date`),
    INDEX `idx_payment_status` (`payment_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: volunteers
-- Purpose: Store volunteer profiles and information
-- ============================================
CREATE TABLE IF NOT EXISTS `volunteers` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL UNIQUE,
    `experience` VARCHAR(500),
    `skills` TEXT,
    `availability` VARCHAR(100),
    `preferred_location` VARCHAR(100),
    `preferred_cause` VARCHAR(100),
    `certification_details` VARCHAR(255),
    `volunteer_since` DATE,
    `hours_contributed` INT DEFAULT 0,
    `status` ENUM('active', 'inactive', 'pending') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_status` (`status`),
    INDEX `idx_preferred_cause` (`preferred_cause`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: products
-- Purpose: Store NGO merchandise products
-- ============================================
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(150) NOT NULL,
    `description` TEXT,
    `category` VARCHAR(50),
    `price` DECIMAL(10, 2) NOT NULL,
    `image_path` VARCHAR(255),
    `stock_quantity` INT DEFAULT 0,
    `profit_percentage` DECIMAL(5, 2) DEFAULT 100,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_category` (`category`),
    INDEX `idx_price` (`price`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: cart
-- Purpose: Store shopping cart items
-- ============================================
CREATE TABLE IF NOT EXISTS `cart` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT DEFAULT 1,
    `added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_cart_item` (`user_id`, `product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: orders
-- Purpose: Store order records
-- ============================================
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `total_amount` DECIMAL(10, 2) NOT NULL,
    `payment_status` ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    `delivery_status` ENUM('pending', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `delivery_date` DATE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_payment_status` (`payment_status`),
    INDEX `idx_order_date` (`order_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: order_items
-- Purpose: Store individual items in orders
-- ============================================
CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL,
    `unit_price` DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    INDEX `idx_order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: contact_messages
-- Purpose: Store contact form submissions
-- ============================================
CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20),
    `subject` VARCHAR(200),
    `message` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('new', 'read', 'replied') DEFAULT 'new',
    INDEX `idx_created_at` (`created_at`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Sample Data: NGOs
-- ============================================
INSERT INTO `ngos` (`name`, `description`, `email`, `phone`, `website`, `address`, `city`, `state`, `registration_number`, `focus_area`, `founded_year`, `image_path`) VALUES
('Helping Hands NGO', 'Dedicated to education and healthcare for underprivileged communities', 'contact@helpinghands.org', '+91-9876543210', 'www.helpinghands.org', '123 Main Street, Andheri East', 'Mumbai', 'Maharashtra', 'REG-001', 'Education', 2005, ''),
('Rural India Foundation', 'Empowering rural communities through sustainable development', 'info@ruralindia.org', '+91-9876543211', 'www.ruralindia.org', '456 Gandhi Road, Connaught Place', 'Delhi', 'Delhi', 'REG-002', 'Rural Development', 2008, ''),
('Women Empowerment Trust', 'Supporting women entrepreneurs and skill development', 'contact@womenempowerment.org', '+91-9876543212', 'www.womenempowerment.org', '789 MG Road, Koramangala', 'Bangalore', 'Karnataka', 'REG-003', 'Women Empowerment', 2010, ''),
('Healthcare for All', 'Providing quality healthcare to remote and underserved areas', 'info@healthcareforall.org', '+91-9876543213', 'www.healthcareforall.org', '321 Park Street, Salt Lake', 'Kolkata', 'West Bengal', 'REG-004', 'Healthcare', 2012, ''),
('Green Earth Initiative', 'Environmental conservation and sustainability projects', 'contact@greenearth.org', '+91-9876543214', 'www.greenearth.org', '555 Beach Road, Banjara Hills', 'Hyderabad', 'Telangana', 'REG-005', 'Environment', 2015, '');

-- ============================================
-- Sample Data: Projects
-- ============================================
INSERT INTO `projects` (`ngo_id`, `name`, `description`, `category`, `target_amount`, `raised_amount`, `start_date`, `end_date`, `location`, `status`) VALUES
(1, 'Education for All', 'Providing free education to underprivileged children in Mumbai slums', 'Education', 500000.00, 250000.00, '2026-01-01', '2026-12-31', 'Mumbai, Maharashtra', 'ongoing'),
(1, 'Health Camp 2026', 'Free health checkup and medical camps in rural areas', 'Healthcare', 300000.00, 180000.00, '2026-02-01', '2026-06-30', 'Maharashtra', 'ongoing'),
(2, 'Clean Water Project', 'Installing water purification systems in villages', 'Rural Development', 750000.00, 400000.00, '2026-01-15', '2026-12-15', 'Uttar Pradesh', 'ongoing'),
(3, 'Women Skill Development', 'Training programs for women in tailoring and handicrafts', 'Women Empowerment', 400000.00, 350000.00, '2025-10-01', '2026-03-31', 'Bangalore, Karnataka', 'ongoing'),
(4, 'Mobile Health Clinics', 'Mobile medical units for remote tribal areas', 'Healthcare', 900000.00, 600000.00, '2026-01-01', '2026-12-31', 'West Bengal', 'ongoing'),
(5, 'Tree Plantation Drive', 'Planting 100,000 trees across urban and rural areas', 'Environment', 250000.00, 180000.00, '2026-03-01', '2026-08-31', 'Telangana', 'ongoing');

-- ============================================
-- Sample Data: Products
-- ============================================
INSERT INTO `products` (`name`, `description`, `category`, `price`, `image_path`, `stock_quantity`, `profit_percentage`) VALUES
('NGO T-Shirt', 'High-quality cotton t-shirt with NGO logo. Comfortable and eco-friendly.', 'Apparel', 299.00, '', 100, 100.00),
('NGO Cap', 'Branded cap supporting our cause. Adjustable strap for perfect fit.', 'Apparel', 199.00, '', 75, 100.00),
('NGO Mug', 'Coffee mug with NGO branding. Ceramic material, dishwasher safe.', 'Merchandise', 149.00, '', 50, 100.00),
('Educational Books Set', 'Set of books for children in rural areas. Contains 5 story books.', 'Books', 599.00, '', 30, 100.00),
('NGO Notebook', 'Eco-friendly notebook made from recycled paper. 100 pages.', 'Stationery', 99.00, '', 200, 100.00),
('NGO Pen Pack', 'Pack of 10 quality pens. Blue ink, smooth writing.', 'Stationery', 149.00, '', 150, 100.00),
('NGO Tote Bag', 'Reusable cotton tote bag with NGO logo. Eco-friendly alternative to plastic.', 'Merchandise', 249.00, '', 80, 100.00),
('NGO Water Bottle', 'Stainless steel water bottle. 750ml capacity, keeps water cool.', 'Merchandise', 399.00, '', 60, 100.00);

-- ============================================
-- Sample Admin User (Password: admin123)
-- Password is hashed using PHP password_hash()
-- ============================================
INSERT INTO `users` (`name`, `email`, `password`, `phone`, `city`, `state`, `user_type`) VALUES
('Admin User', 'admin@ngo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+91-9999999999', 'Mumbai', 'Maharashtra', 'admin');

-- ============================================
-- End of Database Setup
-- ============================================
