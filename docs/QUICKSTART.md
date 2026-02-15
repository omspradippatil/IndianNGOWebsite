<?php
/**
 * QUICK START GUIDE
 * 
 * This file provides a quick reference for setting up and using the
 * Indian NGO Management System with PHP and MySQL
 */

/**
 * STEP 1: DATABASE SETUP
 * =====================
 * 
 * Option A: Using setup.php (Recommended)
 * 1. Open http://localhost/IndianNGOWebsite/IndianNGOWebsite/setup.php in browser
 * 2. Wait for success message
 * 3. Delete setup.php for security
 * 
 * Option B: Manual Setup
 * 1. Create MySQL database: CREATE DATABASE indian_ngo_db;
 * 2. Run SQL files from setup.php (copy and execute in phpMyAdmin)
 * 3. Insert sample data as shown in setup.php
 */

/**
 * STEP 2: CONFIGURATION
 * ====================
 * 
 * Edit config.php with your database details:
 * - DB_HOST: Database server (usually 'localhost')
 * - DB_USER: MySQL username (usually 'root' for local)
 * - DB_PASS: MySQL password
 * - DB_NAME: Database name (indian_ngo_db)
 * - SITE_URL: Your site URL
 */

/**
 * STEP 3: ACCESS THE WEBSITE
 * ==========================
 * 
 * Home Page:
 * http://localhost/IndianNGOWebsite/IndianNGOWebsite/index.php
 * 
 * Register New Account:
 * http://localhost/IndianNGOWebsite/IndianNGOWebsite/register.php
 * 
 * Test Credentials (after setup):
 * - Register as new user
 * - Or use any user created through registration form
 */

/**
 * STEP 4: TEST THE FEATURES
 * =========================
 */

// Test User Registration
/*
Go to register.php and create an account with:
- Name: Your Name
- Email: your.email@example.com
- Password: Your Password
- City: Select City
- State: Select State
- User Type: Select donor/volunteer/ngo
*/

// Test Login
/*
Go to login.php and login with:
- Email: your.email@example.com
- Password: Your Password
*/

// Test Donations
/*
1. Login as a user
2. Go to donate.php
3. Fill donation form
4. Submit (records to database)
*/

// Test Shopping
/*
1. Login as a user
2. Go to products.php
3. Click "Add to Cart"
4. Go to cart.php
5. Click "Proceed to Checkout"
6. Fill delivery info and place order
*/

// Test Volunteer Registration
/*
1. Login as a user with type 'volunteer'
2. Go to volunteers.php
3. Fill volunteer details
4. Submit registration
*/

/**
 * IMPORTANT SECURITY NOTES
 * =======================
 */

// 1. After setup, DELETE or DISABLE setup.php
// 2. Keep config.php private (not in web root if possible)
// 3. Use HTTPS in production
// 4. Regularly update PHP and MySQL
// 5. Backup database regularly
// 6. Use strong passwords
// 7. Monitor and validate all user inputs

/**
 * DATABASE TABLES OVERVIEW
 * =======================
 */

/*
1. users - User accounts and authentication
2. ngos - NGO organization information
3. projects - Projects run by NGOs
4. donations - Donation records
5. volunteers - Volunteer profiles and information
6. products - Product catalog
7. cart - Shopping cart items
8. orders - Order records
9. order_items - Items in each order
10. contact_messages - Contact form submissions
*/

/**
 * KEY PHP FILES
 * =============
 */

// config.php
// - Database and site configuration
// - Define database credentials and constants

// db_connection.php
// - Database connection
// - Helper functions for queries

// auth.php
// - User authentication functions
// - Session management
// - Login, logout, register functions

// index.php, about.php, ngos.php, etc.
// - Frontend pages
// - Display dynamic content from database

/**
 * API ENDPOINTS (Available for integration)
 * =========================================
 */

// Donations: donate.php
// Login: login.php
// Register: register.php
// Checkout: checkout.php
// Add to Cart: add_to_cart.php

/**
 * USEFUL SQL QUERIES FOR TESTING
 * ==============================
 */

// Check all users
// SELECT * FROM users;

// Check donations
// SELECT * FROM donations;

// Check orders
// SELECT * FROM orders;

// Check volunteers
// SELECT * FROM volunteers;

// Check cart items
// SELECT * FROM cart;

// Reset database (careful!)
// DROP DATABASE indian_ngo_db;
// Then run setup.php again

/**
 * TROUBLESHOOTING
 * ==============
 */

// If database connection fails:
// 1. Check if MySQL is running
// 2. Verify DB_HOST, DB_USER, DB_PASS in config.php
// 3. Check error logs

// If login fails:
// 1. Verify email and password are correct
// 2. Check if user exists in database
// 3. Clear browser cookies

// If cart doesn't work:
// 1. Check if user is logged in
// 2. Verify cart table exists
// 3. Check PHP error logs

// If donation fails:
// 1. Verify all required fields are filled
// 2. Check donation amount >= 100
// 3. Check database connection

/**
 * NEXT STEPS
 * ==========
 */

// 1. Run setup.php
// 2. Delete setup.php
// 3. Register a test account
// 4. Test all features
// 5. Customize as needed
// 6. Integrate payment gateway
// 7. Set up email notifications
// 8. Add admin panel
// 9. Deploy to production with HTTPS
// 10. Regular backups

/**
 * GETTING HELP
 * ============
 */

// Check README.md for detailed documentation
// Review code comments for explanations
// Check database structure in setup.php
// Use browser developer tools (F12) for debugging
// Check PHP error logs

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Start Guide - Indian NGO Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .guide-section { margin: 30px 0; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .step { margin: 15px 0; }
        .code { background: #f4f4f4; padding: 10px; border-radius: 4px; font-family: monospace; }
    </style>
</head>
<body>
    <div class="container mt-5 mb-5">
        <h1 class="text-center mb-5">Indian NGO Management System - Quick Start Guide</h1>
        
        <div class="guide-section">
            <h2>✅ Step 1: Database Setup</h2>
            <div class="step">
                <h5>Option A: Automatic Setup (Recommended)</h5>
                <ol>
                    <li>Open your browser and navigate to:
                        <div class="code">http://localhost/IndianNGOWebsite/IndianNGOWebsite/setup.php</div>
                    </li>
                    <li>Wait for the success message</li>
                    <li>Delete setup.php from your server for security</li>
                </ol>
            </div>
            <div class="step">
                <h5>Option B: Manual Setup</h5>
                <ol>
                    <li>Create a new database named <code>indian_ngo_db</code></li>
                    <li>Copy the SQL from setup.php and execute in phpMyAdmin</li>
                    <li>Insert sample data as shown in the setup script</li>
                </ol>
            </div>
        </div>
        
        <div class="guide-section">
            <h2>⚙️ Step 2: Configure Database</h2>
            <p>Edit <code>config.php</code> with your database credentials:</p>
            <div class="code">
                define('DB_HOST', 'localhost');<br>
                define('DB_USER', 'root');<br>
                define('DB_PASS', '');<br>
                define('DB_NAME', 'indian_ngo_db');
            </div>
        </div>
        
        <div class="guide-section">
            <h2>🌐 Step 3: Access the Website</h2>
            <ul>
                <li><strong>Home:</strong> http://localhost/IndianNGOWebsite/IndianNGOWebsite/</li>
                <li><strong>Register:</strong> http://localhost/IndianNGOWebsite/IndianNGOWebsite/register.php</li>
                <li><strong>Login:</strong> http://localhost/IndianNGOWebsite/IndianNGOWebsite/login.php</li>
            </ul>
        </div>
        
        <div class="guide-section">
            <h2>🧪 Step 4: Test Features</h2>
            <div class="row">
                <div class="col-md-6">
                    <h5>User Features</h5>
                    <ul>
                        <li>Register a new account</li>
                        <li>Login with credentials</li>
                        <li>View profile</li>
                        <li>Logout</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5>Donation Features</h5>
                    <ul>
                        <li>Go to Donate page</li>
                        <li>Fill donation form</li>
                        <li>Submit donation</li>
                        <li>Get transaction ID</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5>Shopping Features</h5>
                    <ul>
                        <li>Browse Products</li>
                        <li>Add to Cart</li>
                        <li>View Cart</li>
                        <li>Checkout</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5>Volunteer Features</h5>
                    <ul>
                        <li>View Opportunities</li>
                        <li>Register as Volunteer</li>
                        <li>Fill Details</li>
                        <li>Submit Application</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="guide-section">
            <h2>🔐 Security Checklist</h2>
            <ul>
                <li>✓ Delete or disable setup.php after initialization</li>
                <li>✓ Keep config.php secure</li>
                <li>✓ Use HTTPS in production</li>
                <li>✓ Keep PHP and MySQL updated</li>
                <li>✓ Use strong passwords</li>
                <li>✓ Regular database backups</li>
                <li>✓ Input validation for all forms</li>
                <li>✓ Sanitize all database queries</li>
            </ul>
        </div>
        
        <div class="guide-section">
            <h2>📊 Available Pages</h2>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Page</th>
                            <th>File</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Home</td>
                            <td>index.php</td>
                            <td>Main landing page with featured NGOs</td>
                        </tr>
                        <tr>
                            <td>About</td>
                            <td>about.php</td>
                            <td>Information about the platform</td>
                        </tr>
                        <tr>
                            <td>NGOs</td>
                            <td>ngos.php</td>
                            <td>List all registered NGOs</td>
                        </tr>
                        <tr>
                            <td>Projects</td>
                            <td>projects.php</td>
                            <td>View all ongoing projects</td>
                        </tr>
                        <tr>
                            <td>Donate</td>
                            <td>donate.php</td>
                            <td>Make donations to causes</td>
                        </tr>
                        <tr>
                            <td>Volunteers</td>
                            <td>volunteers.php</td>
                            <td>Volunteer opportunities and registration</td>
                        </tr>
                        <tr>
                            <td>Products</td>
                            <td>products.php</td>
                            <td>Browse merchandise</td>
                        </tr>
                        <tr>
                            <td>Cart</td>
                            <td>cart.php</td>
                            <td>Shopping cart</td>
                        </tr>
                        <tr>
                            <td>Checkout</td>
                            <td>checkout.php</td>
                            <td>Complete purchase</td>
                        </tr>
                        <tr>
                            <td>Contact</td>
                            <td>contact.php</td>
                            <td>Contact form</td>
                        </tr>
                        <tr>
                            <td>Register</td>
                            <td>register.php</td>
                            <td>Create new account</td>
                        </tr>
                        <tr>
                            <td>Login</td>
                            <td>login.php</td>
                            <td>User login</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="guide-section">
            <h2>🆘 Troubleshooting</h2>
            <div class="mb-3">
                <h5>Database Connection Error</h5>
                <p>Check if MySQL is running and verify credentials in config.php</p>
            </div>
            <div class="mb-3">
                <h5>Login Fails</h5>
                <p>Verify email and password are correct. Clear browser cookies.</p>
            </div>
            <div class="mb-3">
                <h5>Cart Not Working</h5>
                <p>Ensure you are logged in and cart table exists in database</p>
            </div>
            <div class="mb-3">
                <h5>Donation Not Saved</h5>
                <p>Check all required fields are filled and amount is ≥ ₹100</p>
            </div>
        </div>
        
        <div class="guide-section alert alert-info">
            <h4>📖 For Complete Documentation</h4>
            <p>Please refer to <strong>README.md</strong> for comprehensive documentation.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
