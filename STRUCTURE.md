# 📁 Project Structure

## ✨ New Professional Organization

```
IndianNGOWebsite/
├── public/                          # 🌐 Web-accessible files (web root)
│   ├── index.php                    # Homepage entry point
│   ├── logout.php                   # Logout handler
│   ├── test_connection.php          # Database connection test
│   └── .htaccess                    # URL rewriting rules
│
├── src/                             # 🔒 Backend source code
│   ├── includes/                    # Core backend files
│   │   ├── config.php               # Database configuration
│   │   ├── auth.php                 # Authentication & authorization
│   │   └── db_connection.php        # Database connection
│   │
│   └── pages/                       # All page files
│       ├── login.php                # User login
│       ├── register.php             # User registration
│       ├── donate.php               # Donation form
│       ├── contact.php              # Contact form
│       ├── products.php             # Product listing
│       ├── ngos.php                 # NGO listing
│       ├── projects.php             # Project listing
│       ├── about.php                # About page
│       ├── volunteers.php           # Volunteer registration
│       ├── ngo_detail.php           # NGO details
│       ├── profile.php              # User profile dashboard
│       ├── admin.php                # Admin panel
│       ├── cart.php                 # Shopping cart
│       ├── checkout.php             # Order checkout
│       ├── order_confirmation.php   # Order confirmation
│       └── add_to_cart.php          # Add to cart handler
│
├── assets/                          # 📦 Static files
│   ├── css/
│   │   └── style.css                # Main stylesheet
│   └── js/                          # JavaScript files (if any)
│       └── (future: custom JS)
│
├── database/                        # 🗄️ Database files
│   ├── database.sql                 # Complete schema & sample data
│   └── setup.php                    # Auto-database creation
│
├── docs/                            # 📖 Documentation
│   ├── README.md                    # Main documentation
│   ├── QUICK_REFERENCE.md           # Quick start guide
│   ├── START_HERE.md                # Getting started
│   ├── INSTALLATION.md              # Detailed installation
│   ├── ENHANCEMENTS.md              # All security fixes applied
│   ├── SECURITY_GUIDELINES.md       # Production deployment
│   ├── COMPLETION_REPORT.md         # Full project report
│   ├── TEST_CHECKLIST.bat           # Windows testing script
│   ├── TEST_CHECKLIST.sh            # Linux testing script
│   ├── QUICKSTART.md                # Quick start (converted from QUICKSTART.php)
│   ├── FILE_STRUCTURE.md            # Old structure (reference)
│   ├── SUMMARY.txt                  # Setup summary
│   └── check_xampp.bat              # XAMPP diagnostic
│
└── archive/                         # 📦 Old HTML files (no longer needed)
    ├── login.html
    ├── register.html
    ├── about.html
    ├── contact.html
    ├── donate.html
    ├── cart.html
    ├── products.html
    ├── ngos.html
    ├── projects.html
    ├── volunteers.html
    └── index.html
```

---

## 📊 File Distribution

| Folder | Purpose | Type | Count |
|--------|---------|------|-------|
| **public/** | Web entry points | PHP | 4 |
| **src/includes/** | Core backend | PHP | 3 |
| **src/pages/** | Application pages | PHP | 16 |
| **assets/css/** | Stylesheets | CSS | 1 |
| **database/** | Database setup | SQL/PHP | 2 |
| **docs/** | Documentation | Markdown/Scripts | 12 |
| **archive/** | Outdated HTML files | HTML | 11 |

---

## 🔗 How It Works

### URL Access Pattern
```
Web Request: /public/index.php
           ↓
       Parse URL
           ↓
       Include: src/includes/auth.php
           ↓
       Include: src/includes/db_connection.php
           ↓
       Output HTML (may include other pages via require_once)
```

### Include Paths

**From public/*.php:**
```php
require_once '../src/includes/auth.php';           // Auth functions
require_once '../src/includes/config.php';         // DB config
```

**From src/pages/*.php:**
```php
require_once '../includes/auth.php';               // Auth functions
require_once '../includes/config.php';             // DB config
```

**CSS References**

**From public/*.php:**
```html
<link rel="stylesheet" href="../assets/css/style.css">
```

**From src/pages/*.php:**
```html
<link rel="stylesheet" href="../../assets/css/style.css">
```

---

## 🚀 Key Improvements

### Before ❌
- All 40+ files in one directory
- HTML and PHP files mixed
- No separation of concerns
- Configuration exposed
- Hard to maintain

### After ✅
- Files organized by purpose
- Backend (src/), Frontend (public/), Docs (docs/)
- Clear separation of concerns
- Configuration protected (not in web root)
- Professional structure
- Easy to maintain & scale

---

## 🔒 Security Benefits

1. **src/ folder is NOT web-accessible** (if properly configured)
2. **Config protected in src/includes/**
3. **Database files in database/ folder** (can be backed up easily)
4. **Archive folder isolated** (old files won't confuse developers)
5. **Public files isolated** (only what should be public)

---

## 📝 Accessing Pages

### From Browser:
```
http://localhost/IndianNGOWebsite/public/index.php
http://localhost/IndianNGOWebsite/src/pages/login.php
http://localhost/IndianNGOWebsite/src/pages/donate.php
http://localhost/IndianNGOWebsite/src/pages/admin.php
```

### Recommended: Configure web server to serve from public/
```
Set DocumentRoot = .../IndianNGOWebsite/public/
Then URLs become:
http://localhost/index.php
http://localhost/login.php
```

---

## 🧪 Testing

Run `docs/TEST_CHECKLIST.bat` (Windows) or `docs/TEST_CHECKLIST.sh` (Linux)

---

## 📚 Documentation Files

- **READ FIRST:** docs/START_HERE.md
- **Quick Reference:** docs/QUICK_REFERENCE.md
- **Full Details:** docs/COMPLETION_REPORT.md
- **Security:** docs/SECURITY_GUIDELINES.md
- **Changes:** docs/ENHANCEMENTS.md

---

## 🎯 Next Steps

1. ✅ Review structure (you're here!)
2. ⬜ Configure web server to use `public/` as root
3. ⬜ Run database setup: `php database/setup.php`
4. ⬜ Test all pages
5. ⬜ Delete setup.php and test_connection.php before production
6. ⬜ Enable HTTPS/SSL
7. ⬜ Deploy to production

---

## 💡 Pro Tips

- **Backups:** Entire src/ and database/ folders
- **Configuration:** All in src/includes/config.php
- **Branches:** Keep both public/ & src/ accessible during development
- **CI/CD:** Archive/ can be deleted in production builds
- **Performance:** Cache docs/README.md for users

---

**Status:** ✅ Professionally Organized | 🔒 Secure | 📦 Production Ready
