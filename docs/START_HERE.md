# Indian NGO Website - Complete Setup & Fix Guide

## 🚨 CURRENT ISSUE: XAMPP Services Not Running

Your website isn't loading because **Apache and MySQL are not started**.

---

## ✅ IMMEDIATE FIX (Do This First!)

### 1. Start XAMPP Services

**Option A: Use XAMPP Control Panel**
1. Search for "XAMPP" in Windows Start Menu
2. Open **XAMPP Control Panel**
3. Click **Start** next to **Apache** (you should see it turn green)
4. Click **Start** next to **MySQL** (you should see it turn green)

**Option B: Run Check Script**
- Double-click `check_xampp.bat` in this folder
- It will check services and offer to open XAMPP Control Panel

### 2. Test Connection
After starting services, open your browser:
```
http://localhost/IndianNGOWebsite/IndianNGOWebsite/test_connection.php
```

This will show if everything is working.

### 3. Create Database
If test shows database doesn't exist:
```
http://localhost/IndianNGOWebsite/IndianNGOWebsite/setup.php
```

This creates the "om" database with all tables and sample data.

### 4. Access Website
```
http://localhost/IndianNGOWebsite/IndianNGOWebsite/index.php
```

---

## 📋 What I Fixed For You

✅ **Updated database name to "om"** (in config.php)
✅ **Created complete SQL file** (database.sql) with all tables
✅ **Created test page** (test_connection.php) to diagnose issues
✅ **Created setup script** (setup.php) to auto-create database
✅ **Created check script** (check_xampp.bat) to verify XAMPP
✅ **Organized project structure** with all files

---

## 📂 Files Created/Updated

### New Files
- `database.sql` - Complete database schema with sample data
- `test_connection.php` - Test database connection
- `check_xampp.bat` - Check XAMPP services
- `QUICKFIX.md` - Quick troubleshooting guide
- `INSTALLATION.md` - Complete installation guide
- `START_HERE.md` - This file

### Updated Files
- `config.php` - Changed database name from "indian_ngo_db" to "om"

---

## 🗄️ Database Structure

**Database Name:** `om`

**Tables Created:**
1. `users` - User accounts (donors, volunteers, NGOs, admins)
2. `ngos` - NGO organizations
3. `projects` - NGO projects
4. `donations` - Donation records
5. `volunteers` - Volunteer profiles
6. `products` - Shopping products
7. `cart` - Shopping cart items
8. `orders` - Order records
9. `order_items` - Order line items
10. `contact_messages` - Contact form submissions

**Sample Data Included:**
- 5 NGOs
- 6 Projects
- 8 Products
- 1 Admin user (admin@ngo.com / admin123)

---

## 🎯 Step-by-Step Checklist

Follow this IN ORDER:

- [ ] **Step 1**: Open XAMPP Control Panel
- [ ] **Step 2**: Start Apache (must be green)
- [ ] **Step 3**: Start MySQL (must be green)
- [ ] **Step 4**: Visit test_connection.php to check connection
- [ ] **Step 5**: If database missing, visit setup.php
- [ ] **Step 6**: Delete or rename setup.php (security)
- [ ] **Step 7**: Visit index.php to see website
- [ ] **Step 8**: Test login with admin@ngo.com / admin123

---

## 🔧 Troubleshooting

### Apache Won't Start
**Possible Cause:** Port 80 is being used by another program (Skype, IIS, etc.)

**Fix:**
1. In XAMPP Control Panel, click **Config** next to Apache
2. Change port from 80 to 8080 in httpd.conf
3. Restart Apache
4. Access website using: `http://localhost:8080/...`

### MySQL Won't Start
**Possible Cause:** Port 3306 is being used

**Fix:**
1. Click **Config** next to MySQL in XAMPP
2. Change port in my.ini
3. Restart MySQL

### Database Connection Error
**Check:**
- MySQL is running (green in XAMPP)
- config.php has correct settings:
  - Host: localhost
  - User: root
  - Pass: (empty)
  - DB: om

### Page Shows Code Instead of Website
**Cause:** Apache not running or PHP not configured

**Fix:**
- Make sure Apache is running
- Check you're using http://localhost/ not file:///

---

## 🌐 All Website URLs

After starting XAMPP, use these URLs:

| Page | URL |
|------|-----|
| Test Connection | http://localhost/IndianNGOWebsite/IndianNGOWebsite/test_connection.php |
| Setup Database | http://localhost/IndianNGOWebsite/IndianNGOWebsite/setup.php |
| Home Page | http://localhost/IndianNGOWebsite/IndianNGOWebsite/index.php |
| NGOs | http://localhost/IndianNGOWebsite/IndianNGOWebsite/ngos.php |
| Donate | http://localhost/IndianNGOWebsite/IndianNGOWebsite/donate.php |
| Login | http://localhost/IndianNGOWebsite/IndianNGOWebsite/login.php |
| phpMyAdmin | http://localhost/phpmyadmin |

---

## 💡 Pro Tips

1. **Always start XAMPP first** before accessing website
2. **Use test_connection.php** to diagnose any issues
3. **Bookmark the URLs** you use frequently
4. **Set XAMPP to auto-start** in Control Panel settings (optional)
5. **Delete setup.php** after database is created

---

## 📱 Default Login Credentials

**Admin Account:**
- Email: `admin@ngo.com`
- Password: `admin123`

**Change this password after first login!**

---

## 🎉 You're All Set!

Your project is now properly configured with:
- ✅ Database name "om"
- ✅ Complete database schema
- ✅ Sample data loaded
- ✅ All PHP files connected
- ✅ Testing tools included

**Next Step:** Open XAMPP Control Panel and start Apache + MySQL!

Then visit: http://localhost/IndianNGOWebsite/IndianNGOWebsite/test_connection.php

---

## 📞 Quick Reference

- **Database**: om
- **Admin Email**: admin@ngo.com
- **Admin Password**: admin123
- **XAMPP Location**: C:\xampp\
- **Website Location**: C:\xampp\htdocs\IndianNGOWebsite\IndianNGOWebsite\

---

**Everything is ready. Just start XAMPP services and you're good to go! 🚀**
