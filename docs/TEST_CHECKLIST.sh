#!/bin/bash
# Website Security & Enhancement Testing Script

echo "========================================"
echo "  Website Testing Checklist"
echo "========================================"
echo ""

# Test functions
test_file_exists() {
    if [ -f "$1" ]; then
        echo "✅ $1 exists"
    else
        echo "❌ $1 is missing"
    fi
}

test_contains() {
    if grep -q "$2" "$1" 2>/dev/null; then
        echo "✅ $1 contains $2"
    else
        echo "⚠️  $1 does not contain $2"
    fi
}

# Test core files
echo "=== Core Files ==="
test_file_exists "config.php"
test_file_exists "db_connection.php"
test_file_exists "auth.php"
test_file_exists "index.php"
test_file_exists "profile.php"
test_file_exists "admin.php"
echo ""

# Test security implementation
echo "=== Security Checks ==="
test_contains "auth.php" "mysqli"
test_contains "auth.php" "prepare"
test_contains "auth.php" "password_hash"
test_contains "auth.php" "bind_param"
test_contains "login.php" "csrf_token"
test_contains "register.php" "FILTER_VALIDATE_EMAIL"
test_contains "donate.php" "prepared"
echo ""

# Test database files
echo "=== Database Files ==="
test_file_exists "database.sql"
test_file_exists "setup.php"
test_file_exists "test_connection.php"
echo ""

# Test documentation
echo "=== Documentation ==="
test_file_exists "README.md"
test_file_exists "ENHANCEMENTS.md"
test_file_exists "START_HERE.md"
test_file_exists "FILE_STRUCTURE.md"
echo ""

echo "========================================"
echo "  Manual Testing Checklist"
echo "========================================"
echo ""
echo "Registration Tests:"
echo "  [ ] Register with valid email"
echo "  [ ] Try registering with duplicate email"
echo "  [ ] Try password mismatch"
echo "  [ ] Verify email validation"
echo ""

echo "Login Tests:"
echo "  [ ] Login with correct credentials"
echo "  [ ] Login with wrong password"
echo "  [ ] Login with non-existent email"
echo "  [ ] Session persists after login"
echo "  [ ] Logout clears session"
echo ""

echo "Form Security Tests:"
echo "  [ ] All forms have CSRF tokens"
echo "  [ ] CSRF token validation works"
echo "  [ ] Invalid token is rejected"
echo "  [ ] Forms validate before submission"
echo ""

echo "Database Tests:"
echo "  [ ] Database name is 'om'"
echo "  [ ] All 10 tables exist"
echo "  [ ] Sample data is loaded"
echo "  [ ] User can donate"
echo "  [ ] User can place order"
echo "  [ ] User can register as volunteer"
echo ""

echo "Feature Tests:"
echo "  [ ] Profile page displays correctly"
echo "  [ ] Admin dashboard loads"
echo "  [ ] Donation history shows"
echo "  [ ] Order history shows"
echo "  [ ] Notifications appear correctly"
echo ""

echo "Security Tests:"
echo "  [ ] SQL injection prevented"
echo "  [ ] XSS attacks prevented"
echo "  [ ] CSRF tokens working"
echo "  [ ] Input validation working"
echo "  [ ] Password hashing verified"
echo ""

echo "========================================"
echo "  Testing Complete"
echo "========================================"
