# 🔧 Troubleshooting Guide

## ⚠️ Common Issues After Reorganization

### ❌ Issue #1: Files Not Found (Error 404)

**Error Message:**
```
Warning: include(auth.php): Failed to open stream
```

**Cause:** Include paths not updated correctly

**Solution:**
```php
// ❌ WRONG:
require_once 'auth.php';

// ✅ CORRECT (in src/pages/ files):
require_once '../includes/auth.php';

// ✅ CORRECT (in public/ files):
require_once '../src/includes/auth.php';
```

### ❌ Issue #2: CSS Not Loading (No Styling)

**Problem:** Pages appear without CSS styling

**Solution:**
```html
<!-- ❌ WRONG:
<link rel="stylesheet" href="style.css">

<!-- ✅ CORRECT (from src/pages/):
<link rel="stylesheet" href="../../assets/css/style.css">

<!-- ✅ CORRECT (from public/):
<link rel="stylesheet" href="../assets/css/style.css">
```

### ❌ Issue #3: Database Connection Failed

**Error Message:**
```
Connection failed: No such file or directory
```

**Cause:** Config.php not found

**Solution:**
1. Verify src/includes/config.php exists
2. Check include path: `require_once '../src/includes/config.php'`
3. Run: `php database/setup.php`

### ❌ Issue #4: URLs Not Working Correctly

**Problem:** Can't access pages with proper URLs

**Current URLs:**
```
http://localhost/IndianNGOWebsite/public/index.php
http://localhost/IndianNGOWebsite/src/pages/login.php
```

**Solution (Recommended):** Configure web server to use `public/` as root

**For XAMPP:**
1. Edit `C:\xampp\apache\conf\httpd.conf`
2. Find the line: `DocumentRoot "..."`
3. Change to: `DocumentRoot "C:/xampp/htdocs/IndianNGOWebsite/public"`
4. Restart Apache
5. Now URLs become: `http://localhost/index.php`

### ❌ Issue #5: Pages Can't Include Each Other

**Error:**
```
Fatal error: include(): Failed opening required
```

**Cause:** Wrong relative paths for cross-includes

**Solution:**
```php
// In src/pages/donate.php, to include another page:
require_once '../pages/some_other_page.php';  // ✅ From same level

// In src/pages/donate.php, to include backend:
require_once '../includes/auth.php';          // ✅ Up one level
```

### ❌ Issue #6: Session Not Working

**Problem:** Login not persistent, "not logged in"

**Check:**
1. `src/includes/config.php` exists and properly configured
2. `src/includes/db_connection.php` exists and connects
3. Database has `users` table
4. Try: `php public/test_connection.php`

**Solution:**
```php
require_once '../src/includes/auth.php';  // Must be included first
$current_user = getCurrentUser();         // Then get current user
```

---

## ✅ Verification Checklist

### Quick Checks

- [ ] `public/index.php` exists and loads
- [ ] `src/includes/config.php` exists
- [ ] `src/includes/auth.php` exists
- [ ] `src/pages/login.php` exists
- [ ] `assets/css/style.css` exists
- [ ] `database/database.sql` exists
- [ ] `docs/START_HERE.md` exists
- [ ] `archive/` folder has old HTML files

### File Path Tests

**In public/index.php:**
```php
<?php
require_once '../src/includes/auth.php';
// If no error above, paths are correct ✅
echo "✅ Includes working!";
?>
```

**In src/pages/login.php:**
```php
<?php
require_once '../includes/auth.php';
// If no error above, paths are correct ✅
echo "✅ Includes working!";
?>
```

**In database/setup.php:**
```php
<?php
require_once '../src/includes/config.php';
// If no error above, paths are correct ✅
echo "✅ Database setup ready!";
?>
```

### URL Tests

```bash
# Test 1: Web root access
curl http://localhost/IndianNGOWebsite/public/index.php

# Test 2: Page access
curl http://localhost/IndianNGOWebsite/src/pages/login.php

# Test 3: Database
curl http://localhost/IndianNGOWebsite/public/test_connection.php
```

---

## 🐛 Debug Mode

### Enable PHP Errors

**Option 1: In config.php**
```php
<?php
// At the top of src/includes/config.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
// ... rest of config
?>
```

**Option 2: During development**
```php
<?php
// At the top of any page to debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if includes are working
echo "Starting...";
require_once '../src/includes/auth.php';
echo "✅ Auth included";
?>
```

### Log File Check

Windows XAMPP error log:
```
C:\xampp\apache\logs\error.log
C:\xampp\mysql\data\*.err
```

---

## 🔍 File Organization Verification

### Verify Structure Script

Save as `verify_structure.php` in root:

```php
<?php
$required_files = [
    'public/index.php',
    'public/logout.php',
    'public/test_connection.php',
    'src/includes/config.php',
    'src/includes/auth.php',
    'src/includes/db_connection.php',
    'src/pages/login.php',
    'src/pages/register.php',
    'src/pages/donate.php',
    'assets/css/style.css',
    'database/database.sql',
    'database/setup.php',
];

echo "<h2>📁 Structure Verification</h2>";
foreach ($required_files as $file) {
    if (file_exists($file)) {
        echo "✅ $file<br>";
    } else {
        echo "❌ MISSING: $file<br>";
    }
}
?>
```

Run: `php verify_structure.php`

---

## 🛠️ Recovery Steps

### If Something Breaks

1. **Check Error Logs:**
   ```
   C:\xampp\apache\logs\error.log
   C:\xampp\mysql\data\*.err
   ```

2. **Verify All Folders Exist:**
   ```
   public/ → ✅
   src/includes/ → ✅
   src/pages/ → ✅
   assets/css/ → ✅
   database/ → ✅
   docs/ → ✅
   ```

3. **Check Include Paths:**
   - public/ files: `../src/includes/`
   - src/pages/ files: `../includes/`
   - database/setup.php: `../src/includes/`

4. **Restart Services:**
   ```bash
   # XAMPP Control Panel → Stop All
   # Wait 5 seconds
   # XAMPP Control Panel → Start All
   ```

5. **Clear PHP Cache:**
   - Close browser
   - Clear browser cache (Ctrl+Shift+Delete)
   - Reload page

6. **Test Connection:**
   - Visit: `http://localhost/IndianNGOWebsite/public/test_connection.php`
   - Check if database connects

---

## 📞 Still Having Issues?

### Diagnostic Steps

1. **Check PHP Version:**
   ```php
   <?php phpinfo(); ?>
   ```
   Should be 7.4 or higher

2. **Check Extensions:**
   - mysqli should be enabled
   - Check `phpinfo()` for `mysqli`

3. **Check MySQL:**
   ```php
   <?php
   $conn = new mysqli('localhost', 'root', '');
   echo $conn->connect_error ? "❌ Failed" : "✅ Connected";
   ?>
   ```

4. **Check Database:**
   ```php
   <?php
   $conn = new mysqli('localhost', 'root', '', 'om');
   echo $conn->connect_error ? "❌ Failed" : "✅ Database 'om' exists";
   ?>
   ```

5. **Check File Permissions:**
   ```bash
   # All folders should be readable/writable
   # src/ → readable
   # public/ → readable/writable
   # database/ → readable/writable
   ```

---

## ✅ Success Indicators

When everything is working:

- ✅ `http://localhost/IndianNGOWebsite/public/index.php` loads without errors
- ✅ CSS styling is applied (page looks good)
- ✅ Navigation links work
- ✅ Login page loads
- ✅ Database connection succeeds
- ✅ No error messages in console
- ✅ Donate button works
- ✅ Cart functionality works
- ✅ Admin page accessible
- ✅ User profile page shows

---

## 🚀 Test in This Order

1. **Homepage Test:**
   ```
   http://localhost/IndianNGOWebsite/public/index.php
   Expected: Loads, has styling, navigation visible
   ```

2. **Database Test:**
   ```
   http://localhost/IndianNGOWebsite/public/test_connection.php
   Expected: "✅ All tests passed"
   ```

3. **Login Test:**
   ```
   http://localhost/IndianNGOWebsite/src/pages/login.php
   Expected: Login form appears with styling
   Credentials: admin@ngo.com / admin123
   ```

4. **Admin Test:**
   ```
   http://localhost/IndianNGOWebsite/src/pages/admin.php
   Expected: Admin dashboard appears (after login)
   ```

5. **Donation Test:**
   ```
   http://localhost/IndianNGOWebsite/src/pages/donate.php
   Expected: Donation form appears (may require login)
   ```

---

## 📝 Notes

- **Test files to delete before production:**
  - `public/test_connection.php`
  - `database/setup.php`

- **Sensitive files to protect:**
  - `src/includes/config.php`
  - `src/includes/auth.php`
  - `database/database.sql`

- **Archives (safe to delete):**
  - `archive/` folder (old HTML files)

---

**Status:** ✅ Organized & Ready
**Support:** 🆘 Use this guide for troubleshooting
