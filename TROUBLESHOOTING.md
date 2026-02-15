# Troubleshooting Guide

## Common Issues (Current Flat Structure)

### Issue #1: Files Not Found (Error 404)

**Error Message:**
```
Warning: include(auth.php): Failed to open stream
```

**Cause:** Include paths not using root-level files.

**Fix:**
```php
require_once 'config.php';
require_once 'db_connection.php';
require_once 'auth.php';
```

### Issue #2: CSS Not Loading

**Fix:** Ensure all pages use:
```html
<link rel="stylesheet" href="css/style.css">
```

### Issue #3: Database Connection Failed

**Fix:**
1. Verify MySQL is running in XAMPP.
2. Confirm `config.php` values:
   - DB_HOST: localhost
   - DB_USER: root
   - DB_PASS: (empty)
   - DB_NAME: om
3. Run: http://localhost/IndianNGOWebsite/setup.php

### Issue #4: URLs Not Working

Use these base URLs:
```
http://localhost/IndianNGOWebsite/index.php
http://localhost/IndianNGOWebsite/login.php
http://localhost/IndianNGOWebsite/donate.php
```

### Issue #5: Sessions Not Working

**Fix:** Ensure `auth.php` is required before using session data:
```php
require_once 'auth.php';
$current_user = getCurrentUser();
```

---

## Verification Checklist

- [ ] `index.php` loads at http://localhost/IndianNGOWebsite/index.php
- [ ] `css/style.css` exists and loads
- [ ] `config.php`, `db_connection.php`, `auth.php` exist in root
- [ ] `database.sql` exists in root
- [ ] `setup.php` creates database without errors
- [ ] `test_connection.php` shows success

---

## Debug Mode

Add this to the top of a PHP page to see errors:
```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

Windows XAMPP logs:
```
C:\xampp\apache\logs\error.log
C:\xampp\mysql\data\*.err
```

---

## Test Order

1. http://localhost/IndianNGOWebsite/test_connection.php
2. http://localhost/IndianNGOWebsite/setup.php
3. http://localhost/IndianNGOWebsite/index.php
4. http://localhost/IndianNGOWebsite/login.php

---

**Status:** ✅ Updated for current layout
