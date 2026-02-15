# 🚀 QUICK FIX - Website Not Loading

## Problem: XAMPP Website Not Loading

## ✅ SOLUTION - Follow These Steps:

### Step 1: Check XAMPP Services
1. Open **XAMPP Control Panel**
2. Make sure these are RUNNING (green):
   - ✅ Apache
   - ✅ MySQL
3. If not running, click **Start** button

### Step 2: Verify File Location
Make sure your files are in the correct location:
```
C:\xampp\htdocs\IndianNGOWebsite\IndianNGOWebsite\
```

All your PHP files should be in this folder.

### Step 3: Create Database
You have TWO options:

#### Option A: Automatic (Recommended)
1. Open browser
2. Go to: `http://localhost/IndianNGOWebsite/test_connection.php`
3. This will test your connection
4. Then go to: `http://localhost/IndianNGOWebsite/setup.php`
5. This creates database "om" automatically
6. **IMPORTANT**: Delete setup.php after running it

#### Option B: Manual via phpMyAdmin
1. Go to: `http://localhost/phpmyadmin`
2. Click **Import** tab
3. Choose file: `database.sql`
4. Click **Go**

### Step 4: Access Website
Open browser and go to:
```
http://localhost/IndianNGOWebsite/index.php
```

### Step 5: Login
**Test Admin Account:**
- Email: `admin@ngo.com`
- Password: `admin123`

---

## 🔧 Still Not Working?

### Error: "Connection Failed"
**Fix:** 
- Check MySQL is running in XAMPP
- Check config.php has correct settings

### Error: "Database does not exist"
**Fix:**
- Run setup.php
- OR import database.sql in phpMyAdmin

### Error: "404 Not Found"
**Fix:**
- Check file path is correct
- Files must be in: `C:\xampp\htdocs\IndianNGOWebsite\IndianNGOWebsite\`
- Use correct URL: `http://localhost/IndianNGOWebsite/`

### Error: Blank White Page
**Fix:**
- Check Apache is running
- Look at error_log in: `C:\xampp\apache\logs\`
- Enable PHP errors in php.ini

---

## 📂 Project Files Overview

### Configuration Files
- `config.php` - Database settings (database name: **om**)
- `db_connection.php` - Database connection functions

### Database Files
- `database.sql` - Complete database with sample data
- `setup.php` - Auto-create database script
- `test_connection.php` - Test database connection

### Main Pages
- `index.php` - Home page
- `ngos.php` - NGO listings
- `projects.php` - Projects
- `donate.php` - Donations
- `products.php` - Product shop
- `volunteers.php` - Volunteer registration

### User Authentication
- `register.php` - New user registration
- `login.php` - User login
- `logout.php` - Logout

---

## ✨ Database Info

- **Database Name**: `om`
- **Host**: `localhost`
- **Username**: `root`
- **Password**: (empty - default XAMPP)

Database includes:
- 10 tables (users, ngos, projects, donations, volunteers, products, cart, orders, order_items, contact_messages)
- Sample NGOs and products
- Admin user account

---

## 🎯 Quick Test Checklist

- [ ] XAMPP Apache is running (green in control panel)
- [ ] XAMPP MySQL is running (green in control panel)
- [ ] Files are in C:\xampp\htdocs\IndianNGOWebsite\IndianNGOWebsite\
- [ ] Visited test_connection.php to check connection
- [ ] Database "om" is created (via setup.php or phpMyAdmin)
- [ ] Can access index.php in browser
- [ ] Can login with admin@ngo.com / admin123

---

## 💡 Pro Tips

1. **Always check XAMPP first** - Make sure Apache and MySQL are running
2. **Use test_connection.php** - This shows exactly what's wrong
3. **Clear browser cache** - Sometimes old pages are cached
4. **Check the URL** - Must include full path to IndianNGOWebsite
5. **Delete setup.php** - After database is created, for security

---

## 🆘 Need More Help?

If still not working:
1. Take a screenshot of the error
2. Check Apache error log: `C:\xampp\apache\logs\error.log`
3. Check what test_connection.php shows
4. Verify all files are in correct location

---

**Your website should now be working! 🎉**
