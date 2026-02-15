# Indian NGO Management System - PHP & MySQL Version

A comprehensive web-based platform for managing NGO operations, donations, volunteer management, and e-commerce for social causes.

## 🚀 QUICK START

**Database Name:** `om` (configured and ready)

1. **Start XAMPP**: Open XAMPP Control Panel → Start Apache & MySQL
2. **Test Connection**: Visit http://localhost/IndianNGOWebsite/test_connection.php
3. **Setup Database**: Visit http://localhost/IndianNGOWebsite/setup.php
4. **Access Website**: Visit http://localhost/IndianNGOWebsite/index.php
5. **Login**: admin@ngo.com / admin123

📖 **Need help?** See [START_HERE.md](START_HERE.md) for detailed instructions.

## Features

### User Management
- User registration and authentication
- Support for different user types: Donors, Volunteers, NGOs, and Admins
- Secure password hashing using PHP's password_hash()
- Session-based authentication

### Donation System
- Accept donations from registered and anonymous users
- Support multiple payment methods (Credit Card, Debit Card, Net Banking, UPI, PayPal)
- Track donations by cause
- Generate transaction IDs for each donation
- Store donation history in database

### NGO Management
- Browse and view details of registered NGOs
- NGO profiles with contact information and focus areas
- Display projects under each NGO
- Track NGO registration and founding details

### Projects
- View ongoing projects from different NGOs
- Project funding progress tracking
- Project details including location, category, and status
- Support multiple project statuses: ongoing, completed, upcoming

### Volunteer Management
- Volunteer registration system
- Volunteer opportunities listing
- Track volunteer skills, experience, and availability
- Support for different causes (Education, Healthcare, Women Empowerment, etc.)
- Volunteer approval workflow

### E-Commerce
- Product catalog for NGO merchandise
- Shopping cart functionality
- Order management system
- Checkout process with order confirmation
- Delivery tracking (pending, shipped, delivered)
- Stock management

### Contact & Communication
- Contact form for inquiries
- Message storage in database
- Message status tracking (new, read, replied)

## Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, Bootstrap 5.3, JavaScript
- **Icons**: Bootstrap Icons

## Project Structure

```
IndianNGOWebsite/
├── config.php                    # Database and site configuration
├── db_connection.php             # Database connection and helper functions
├── auth.php                      # Authentication and session management
├── setup.php                     # Database initialization script
├── test_connection.php           # Database connection test
│
├── index.php                     # Home page with featured NGOs
├── about.php                     # About the platform
├── ngos.php                      # List all NGOs
├── ngo_detail.php                # Individual NGO details
├── projects.php                  # List all projects
├── donate.php                    # Donation form
├── volunteers.php                # Volunteer registration and opportunities
├── products.php                  # Product catalog
├── cart.php                      # Shopping cart
├── add_to_cart.php               # Add product to cart
├── checkout.php                  # Checkout process
├── order_confirmation.php        # Order confirmation
├── contact.php                   # Contact form
│
├── login.php                     # User login
├── register.php                  # User registration
├── logout.php                    # User logout
│
├── css/
│   └── style.css                 # Custom CSS styles
└── docs/                         # Documentation
   └── README.md                 # This file
```

## Database Schema

### Tables

#### users
- User account information
- Stores email, password, phone, location
- User type: donor, volunteer, ngo, admin

#### ngos
- NGO organization details
- Contact information, registration number
- Focus areas and founding year

#### projects
- Projects run by NGOs
- Funding goals and progress tracking
- Project status and location

#### donations
- Donation transaction records
- User, amount, cause, and payment method
- Transaction ID and payment status

#### volunteers
- Volunteer profile information
- Skills, experience, availability
- Preferred location and cause
- Hours contributed

#### products
- Product catalog for merchandise
- Price, category, stock quantity
- Profit percentage for each product

#### cart
- Shopping cart items
- User and product associations
- Quantity tracking

#### orders
- Order records
- Total amount, payment and delivery status
- Order date and delivery date

#### contact_messages
- Contact form submissions
- Message status tracking
- Timestamp for each message

## Installation & Setup

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache Web Server (with mod_rewrite enabled)
- Composer (optional, for additional PHP libraries)

### Step-by-Step Setup

1. **Extract Files**
   - Extract the project files to your web server's root directory
   - Example: `C:\xampp\htdocs\IndianNGOWebsite\` (Windows with XAMPP)

2. **Configure Database**
   - Open `config.php`
   - Update database credentials if needed:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
   define('DB_NAME', 'om');
     ```

3. **Initialize Database**
   - Open your browser and navigate to:
     ```
   http://localhost/IndianNGOWebsite/setup.php
     ```
   - The script will create all necessary tables and insert sample data
   - You should see a success message
   - **Important**: Disable or delete setup.php after initialization for security

4. **Start the Application**
   - Navigate to the home page:
     ```
   http://localhost/IndianNGOWebsite/index.php
     ```

## Usage Guide

### For Donors
1. Register as a user (User Type: Donor)
2. Browse NGOs and Projects
3. Make donations through the Donate page
4. Buy merchandise from Product Selling page
5. Track your donations and orders in your profile

### For Volunteers
1. Register as a user (User Type: Volunteer)
2. Go to Volunteers page
3. Complete volunteer registration form
4. Submit for approval
5. View volunteer opportunities

### For NGO Partners
1. Register as a user (User Type: NGO)
2. Contact admin to set up your NGO profile
3. Add projects and manage funding
4. Accept and coordinate volunteers

### For Administrators
- Access user management and approval workflows
- Monitor donations and transactions
- Manage NGO and project listings
- Review contact messages and volunteer applications

## Key Features Explained

### Authentication System
- Users must register before accessing protected pages
- Passwords are securely hashed using `password_hash()` with bcrypt
- Sessions are used for user management
- Automatic logout on browser close (session-based)

### Shopping Cart
- Products are added to cart (only for logged-in users)
- Cart persists in the database
- Users can update quantities and remove items
- Checkout creates order records

### Donation Processing
- Donations can be anonymous or attributed to user
- Multiple payment methods supported (backend integration needed)
- Transaction IDs are generated automatically
- Donations are recorded in database

### Volunteer Management
- Volunteers fill out detailed profile
- Admin approval system for volunteer applications
- Volunteers can track hours contributed
- Match volunteers with opportunities

## Security Considerations

1. **Password Security**
   - All passwords are hashed using PHP's `password_hash()` function
   - Never store plain text passwords

2. **Input Validation**
   - Use `sanitize()` function to prevent SQL injection
   - Validate all user inputs

3. **Session Management**
   - Sessions expire after inactivity
   - Use `requireLogin()` for protected pages

4. **File Security**
   - Delete setup.php after initial setup
   - Keep config.php secure and not publicly accessible

5. **HTTPS**
   - Use HTTPS in production environments
   - Secure all sensitive transactions

## Common Issues & Solutions

### Database Connection Error
- Ensure MySQL server is running
- Check database credentials in config.php
- Verify database name matches

### Setup Page Errors
- Check PHP error logs for detailed messages
- Ensure MySQL user has CREATE TABLE privileges
- Try running individual CREATE TABLE statements

### Login Issues
- Clear browser cookies and cache
- Ensure email and password match exactly
- Check database has user records

### Cart Not Working
- Ensure user is logged in
- Clear browser cache
- Check cart table exists in database

## Payment Gateway Integration

The donation and checkout systems are ready for payment gateway integration:

1. **For Donations**: Update `donate.php` to integrate with services like:
   - Razorpay
   - PayU
   - Instamojo
   - PayPal

2. **For E-Commerce**: Update `checkout.php` to integrate with:
   - Stripe
   - Square
   - 2Checkout

## Future Enhancements

- User dashboard with donation history
- Admin panel for managing content
- Email notifications
- SMS notifications
- Advanced reporting and analytics
- Mobile app version
- Payment gateway integration
- Image upload for products and NGO profiles
- Multi-language support
- Blog/News section
- FAQ section

## File Permissions

Ensure proper file permissions:
```bash
chmod 755 /path/to/IndianNGOWebsite
chmod 644 /path/to/IndianNGOWebsite/*.php
chmod 644 /path/to/IndianNGOWebsite/style.css
```

## Browser Compatibility

- Chrome (Latest)
- Firefox (Latest)
- Safari (Latest)
- Edge (Latest)
- Internet Explorer 11 (Limited support)

## Support & Contact

For issues, questions, or contributions:
- Email: support@indianngo.org
- Website: www.indianngo.org

## License

This project is licensed under the MIT License - see LICENSE.md for details.

## Changelog

### Version 1.0.0 (Initial Release)
- Complete user authentication system
- NGO management and listing
- Donation system with multiple payment methods
- Volunteer management system
- E-commerce functionality for products
- Contact form integration
- Database schema with 10 tables
- Responsive design with Bootstrap 5

## Credits

Developed as a comprehensive solution for Indian NGO management and coordination.

---

**Last Updated**: January 2024
**Current Version**: 1.0.0
