# Project Structure

## Current Layout

```
IndianNGOWebsite/
├── config.php                    # Database and site configuration
├── db_connection.php             # Database connection and helpers
├── auth.php                      # Authentication and session management
├── setup.php                     # Database initialization script
├── test_connection.php           # Database connection test
├── database.sql                  # Full schema + sample data
│
├── index.php                     # Home page
├── about.php                     # About page
├── ngos.php                      # NGO listing
├── ngo_detail.php                # NGO details
├── projects.php                  # Projects listing
├── donate.php                    # Donation form
├── volunteers.php                # Volunteer registration
├── products.php                  # Product catalog
├── cart.php                      # Shopping cart
├── add_to_cart.php               # Add to cart handler
├── checkout.php                  # Checkout
├── order_confirmation.php        # Order confirmation
├── contact.php                   # Contact form
├── login.php                     # User login
├── register.php                  # User registration
├── logout.php                    # User logout
├── admin.php                     # Admin panel
├── profile.php                   # User profile
│
├── css/
│   └── style.css                 # Main stylesheet
│
└── docs/                         # Documentation
    ├── README.md
    ├── START_HERE.md
    ├── QUICK_REFERENCE.md
    ├── INSTALLATION.md
    ├── QUICKSTART.md
    ├── QUICKFIX.md
    ├── FILE_STRUCTURE.md
    ├── SECURITY_GUIDELINES.md
    ├── ENHANCEMENTS.md
    ├── COMPLETION_REPORT.md
    └── SUMMARY.txt
```

## Accessing Pages

```
http://localhost/IndianNGOWebsite/index.php
http://localhost/IndianNGOWebsite/login.php
http://localhost/IndianNGOWebsite/donate.php
http://localhost/IndianNGOWebsite/admin.php
```

## Notes

- This project uses a flat PHP structure (no public/src split).
- CSS is served from css/style.css.
- The database setup uses database.sql and setup.php in the project root.
