# Indian NGO Website Platform

A comprehensive web platform built with PHP and MySQL designed to connect NGOs (Non-Governmental Organizations) across India. The platform enables NGOs to showcase their work, manage volunteers, process donations, and coordinate projects.

## Features

- **NGO Directory**: Browse and discover NGOs by cause, location, and impact area
- **User Authentication**: Secure login and registration for volunteers and donors
- **Volunteer Management**: Register as a volunteer and track volunteer activities
- **Donation System**: Secure donation processing and fund management
- **Project Tracking**: NGOs can create and manage projects with milestones
- **Shopping Cart**: Donation cart system for multiple contributions
- **Profile Management**: NGO and user profile customization
- **Admin Dashboard**: Comprehensive admin panel for platform management
- **Contact System**: Direct communication with NGOs

## Technologies Used

- **Backend**: PHP 7.0+
- **Database**: MySQL 5.6+
- **Frontend**: HTML5, CSS3, JavaScript
- **Server**: Apache/Nginx

## Project Structure

```
IndianNGOWebsite/
 index.php                    # Homepage
 about.php                    # About page
 ngos.php                     # NGO listing
 ngo_detail.php              # Individual NGO details
 projects.php                # Project showcase
 volunteers.php              # Volunteer portal
 cart.php                    # Donation cart
 checkout.php                # Payment processing
 donate.php                  # Donation page
 admin.php                   # Admin panel
 auth.php                    # Authentication logic
 profile.php                 # User profiles
 config.php                  # Configuration
 db_connection.php           # Database connection
 database.sql                # Database schema
 css/                        # Stylesheets
 docs/                       # Documentation
 docs/*.md                   # Admin guides and references
```

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/omspradippatil/IndianNGOWebsite.git
   ```

2. **Set up the database**:
   ```bash
   mysql -u username -p < database.sql
   ```

3. **Configure database connection**:
   - Edit `config.php` with your database credentials

4. **Upload to web server**:
   - Place files in your web server root (htdocs for XAMPP)

5. **Access the application**:
   ```
   http://localhost/IndianNGOWebsite/
   ```

## Admin Access

The admin panel can be accessed at:
```
http://localhost/IndianNGOWebsite/admin.php
```

Refer to `ADMIN_GUIDE.md` for detailed admin instructions.

## Database Setup

The project includes a complete database schema in `database.sql`. Import this file to set up:
- NGO profiles
- User accounts
- Volunteer records
- Project information
- Donation transactions
- Order management

## Documentation

Complete documentation is available in the `docs/` directory:
- `ADMIN_GUIDE.md` - Comprehensive admin guide
- `ADMIN_QUICK_REFERENCE.md` - Quick command reference
- `STRUCTURE.md` - Platform architecture overview
- `TROUBLESHOOTING.md` - Common issues and solutions

## Contributing

Contributions are welcome! Please submit pull requests with:
- Clear description of changes
- Bug fixes or new features
- Updated documentation where applicable

## License

This project is licensed under the MIT License - see the LICENSE file for details.

##  Support

If you find this project helpful, consider [supporting me](https://om-patil.com/donate).

---

##  Contact the Developer

**Developed by OM Patil**

- **Portfolio**: [ompradippatil.netlify.app](https://ompradippatil.netlify.app/)
- **GitHub**: [@omspradippatil](https://github.com/omspradippatil)
- **LinkedIn**: [OM Pradip Patil](https://in.linkedin.com/in/om-pradip-patil)
- **Email**: [omspradippatil@gmail.com](mailto:omspradippatil@gmail.com)
