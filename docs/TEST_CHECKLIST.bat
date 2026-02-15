@echo off
REM Website Security & Enhancement Testing Script for Windows

cls
echo ========================================
echo   Website Testing Checklist
echo ========================================
echo.

REM Test core files
echo === Core Files ===
if exist "config.php" (
    echo [OK] config.php exists
) else (
    echo [MISSING] config.php
)

if exist "auth.php" (
    echo [OK] auth.php exists
) else (
    echo [MISSING] auth.php
)

if exist "profile.php" (
    echo [OK] profile.php exists
) else (
    echo [MISSING] profile.php
)

if exist "admin.php" (
    echo [OK] admin.php exists
) else (
    echo [MISSING] admin.php
)

echo.
echo === Documentation Files ===
if exist "README.md" (
    echo [OK] README.md exists
) else (
    echo [MISSING] README.md
)

if exist "ENHANCEMENTS.md" (
    echo [OK] ENHANCEMENTS.md exists
) else (
    echo [MISSING] ENHANCEMENTS.md
)

echo.
echo === Database Files ===
if exist "database.sql" (
    echo [OK] database.sql exists
) else (
    echo [MISSING] database.sql
)

if exist "setup.php" (
    echo [OK] setup.php exists
) else (
    echo [MISSING] setup.php
)

echo.
echo ========================================
echo   Manual Testing Checklist
echo ========================================
echo.
echo REGISTRATION TESTS:
echo  [ ] Register with valid email
echo  [ ] Try registering with duplicate email
echo  [ ] Try password mismatch
echo  [ ] Verify email validation
echo.

echo LOGIN TESTS:
echo  [ ] Login with correct credentials
echo  [ ] Login with wrong password
echo  [ ] Session persists after login
echo  [ ] Logout clears session
echo.

echo FORM SECURITY TESTS:
echo  [ ] All forms have CSRF tokens
echo  [ ] Forms validate before submission
echo  [ ] Error messages display correctly
echo.

echo DATABASE TESTS:
echo  [ ] Database name is 'om'
echo  [ ] Can access database
echo  [ ] Sample data loaded
echo.

echo FEATURE TESTS:
echo  [ ] Profile page displays correctly
echo  [ ] Admin dashboard loads
echo  [ ] Donation history shows
echo  [ ] Can donate successfully
echo.

echo SECURITY TESTS:
echo  [ ] SQL injection prevented
echo  [ ] XSS attacks prevented
echo  [ ] CSRF tokens working
echo  [ ] Input validation working
echo.

echo ========================================
echo   Quick Test URLs
echo ========================================
echo.
echo Test Connection:
echo   http://localhost/IndianNGOWebsite/IndianNGOWebsite/test_connection.php
echo.
echo Home Page:
echo   http://localhost/IndianNGOWebsite/IndianNGOWebsite/index.php
echo.
echo Register:
echo   http://localhost/IndianNGOWebsite/IndianNGOWebsite/register.php
echo.
echo Login:
echo   http://localhost/IndianNGOWebsite/IndianNGOWebsite/login.php
echo.
echo Profile (logged-in only):
echo   http://localhost/IndianNGOWebsite/IndianNGOWebsite/profile.php
echo.
echo Admin Panel (admin only):
echo   http://localhost/IndianNGOWebsite/IndianNGOWebsite/admin.php
echo.
echo Test Credentials:
echo   Email: admin@ngo.com
echo   Password: admin123
echo.

pause
