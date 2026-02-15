# Installation Guide - Indian NGO Website

## Quick Start for XAMPP

### Step 1: Start XAMPP Services
1. Open XAMPP Control Panel
2. Start **Apache** server
3. Start **MySQL** server

### Step 2: Import Database
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Click on "Import" tab
3. Choose file: `database.sql`
4. Click "Go" to import

**OR** you can create database automatically:
- Visit: http://localhost/IndianNGOWebsite/IndianNGOWebsite/setup.php
- This will create database "om" and all tables with sample data
- **IMPORTANT**: Delete or rename `setup.php` after running it for security

### Step 3: Access the Website
Open your browser and go to:
```
http://localhost/IndianNGOWebsite/IndianNGOWebsite/index.php
```

### Step 4: Test Login
**Admin Login:**
- Email: admin@ngo.com
- Password: admin123

## Troubleshooting

### Website Not Loading
- Check if Apache is running in XAMPP
- Check URL is correct: `http://localhost/IndianNGOWebsite/IndianNGOWebsite/`
- Check if PHP files are in: `C:\xampp\htdocs\IndianNGOWebsite\IndianNGOWebsite\`

### Database Connection Error
1. Check MySQL is running in XAMPP
2. Verify `config.php` settings:
   - DB_HOST: localhost
   - DB_USER: root
   - DB_PASS: (empty)
   - DB_NAME: om

3. Check database exists in phpMyAdmin

### Cannot Import SQL File
- Make sure file size is under upload limit
- Check for syntax errors
- Try creating database manually then import

## Database Configuration

The database is configured in `config.php`:
- **Database Name**: om
- **Host**: localhost  
- **Username**: root
- **Password**: (empty - default XAMPP)

## Project Structure

```
IndianNGOWebsite/
├── config.php              # Database configuration
├── db_connection.php       # Database connection
├── auth.php                # Authentication
├── database.sql            # Complete database schema
├── setup.php               # Auto database setup
│
├── index.php               # Home page
├── about.php               # About page
├── ngos.php                # NGO listing
├── ngo_detail.php         # NGO details
├── projects.php            # Projects listing
├── donate.php              # Donation page
├── volunteers.php          # Volunteers page
├── products.php            # Products shop
├── cart.php                # Shopping cart
├── checkout.php            # Checkout
├── contact.php             # Contact form
│
├── login.php               # User login
├── register.php            # User registration
├── logout.php              # Logout
│
└── style.css               # Styles
```

## Features

✅ User Registration & Login
✅ NGO Listings with Details
✅ Project Management
✅ Donation System
✅ Volunteer Registration
✅ Product Shop with Cart
✅ Order Management
✅ Contact Form

## Default Admin Account

- **Email**: admin@ngo.com
- **Password**: admin123

## Security Notes

1. **Delete setup.php** after initial database setup
2. Change admin password after first login
3. Use HTTPS in production
4. Update database credentials for production
5. Enable `.htaccess` file for URL rewriting

## Need Help?

If website still not loading:
1. Check Apache error logs in XAMPP
2. Ensure all PHP files are in correct directory
3. Check file permissions
4. Clear browser cache
5. Try different browser

## Database Import via phpMyAdmin

1. Open: http://localhost/phpmyadmin
2. Click "New" to create database
3. Name: `om`
4. Click "Import" tab
5. Choose `database.sql`
6. Click "Go"

Done! Your website should now be working.
