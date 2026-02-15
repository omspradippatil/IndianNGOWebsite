# 🔐 Security Guidelines & Best Practices

## Production Deployment Checklist

### 1. Database Security
- [ ] Change admin password
- [ ] Set strong database passwords
- [ ] Restrict database user permissions (no DROP, ALTER for web user)
- [ ] Regular database backups (daily minimum)
- [ ] Enable binary logging for recovery
- [ ] Encrypt sensitive data (especially financial)

### 2. File Security
- [ ] Remove/restrict setup.php access
- [ ] Remove/restrict test_connection.php access
- [ ] Set proper file permissions:
  ```
  chmod 644 *.php      # Readable by web server
  chmod 700 uploads/   # Upload directory restricted
  chmod 600 config.php # Config file very restrictive
  ```
- [ ] Disable directory listing
- [ ] Move config.php outside web root if possible

### 3. PHP Configuration
- [ ] Disable error display to users
- [ ] Log errors to file instead
- [ ] Set `display_errors = Off`
- [ ] Set `log_errors = On`
- [ ] Increase `post_max_size` if needed
- [ ] Set `max_execution_time` appropriately

### 4. Web Server Security
- [ ] Enable HTTPS/SSL (critical)
- [ ] Use .htaccess to prevent directory browsing
- [ ] Disable unnecessary HTTP methods
- [ ] Configure security headers:
  ```
  X-Content-Type-Options: nosniff
  X-Frame-Options: SAMEORIGIN
  X-XSS-Protection: 1; mode=block
  Strict-Transport-Security: max-age=31536000
  ```

### 5. Application Security

#### Authentication
- [ ] Update default admin password
- [ ] Implement password complexity requirements
- [ ] Add account lockout after failed attempts
- [ ] Implement session timeout (30 minutes)
- [ ] Use HTTPS for all login/password pages

#### Input Validation
- [x] Validate all user inputs
- [x] Use prepared statements for all queries
- [x] Escape output using htmlspecialchars()
- [x] Use whitelists for dropdowns
- [ ] Add server-side file upload validation

#### CSRF Protection
- [x] All forms have CSRF tokens
- [x] Token validation on submission
- [ ] Refresh token after sensitive operations

#### Session Management
- [x] Regenerate session ID after login
- [ ] Implement session timeout
- [ ] Clear sensitive data on logout
- [x] Use secure session cookies

### 6. Monitoring & Logging

#### Application Logging
```php
// Log failed login attempts
error_log("Failed login for: " . $email);

// Log sensitive operations
error_log("User " . $user_id . " made donation of ₹" . $amount);

// Log potential attacks
error_log("Possible SQL injection attempt: " . $_SERVER['QUERY_STRING']);
```

#### Regular Monitoring
- [ ] Monitor error logs daily
- [ ] Check for unusual database activity
- [ ] Monitor for brute force attempts
- [ ] Track file modifications
- [ ] Monitor server resources

### 7. Data Protection

#### User Data
- [ ] Never store passwords in plain text
- [ ] Hash passwords using PASSWORD_DEFAULT
- [ ] Encrypt sensitive data at rest
- [ ] Use HTTPS for all data transmission
- [ ] Implement data retention policies

#### Financial Data
- [ ] PCI DSS compliance for payments
- [ ] Never store credit card numbers
- [ ] Use payment gateway tokenization
- [ ] Encrypt transaction records
- [ ] Regular security audits

### 8. Access Control

#### Admin Panel
- [ ] Restrict /admin paths to admin IPs
- [ ] Implement 2FA for admin accounts
- [ ] Audit admin actions
- [ ] Separate admin accounts from regular users
- [ ] Change admin passwords regularly

#### User Roles
```
Define roles clearly:
- Admin: Full access
- NGO: Manage own projects
- Donor: Donate, view history
- Volunteer: Register, view opportunities
```

### 9. Regular Maintenance

#### Updates
- [ ] Keep PHP updated
- [ ] Keep server OS updated
- [ ] Keep libraries updated
- [ ] Review and test updates

#### Testing
- [ ] Run security vulnerability scans
- [ ] Penetration testing (quarterly)
- [ ] Code review (quarterly)
- [ ] Dependency scanning
- [ ] Backup restoration tests

#### Documentation
- [ ] Document all security measures
- [ ] Create incident response plan
- [ ] Document user roles and permissions
- [ ] Keep change log

### 10. Backup & Disaster Recovery

#### Backups
- [ ] Daily database backups
- [ ] Weekly file backups
- [ ] Store backups off-site
- [ ] Test backup restoration monthly
- [ ] Retention policy (3 months minimum)

#### Disaster Recovery
- [ ] Recovery time objective (RTO): 4 hours
- [ ] Recovery point objective (RPO): 1 hour
- [ ] Documented recovery procedures
- [ ] Regular recovery drills

---

## Common Vulnerabilities & Prevention

### SQL Injection
**Vulnerability:**
```php
// BAD - Vulnerable to SQL injection
$query = "SELECT * FROM users WHERE email = '$email'";
```

**Prevention:** ✅ IMPLEMENTED
```php
// GOOD - Using prepared statements
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
```

### Cross-Site Scripting (XSS)
**Vulnerability:**
```php
// BAD
echo $_GET['name']; // Could execute JavaScript
```

**Prevention:** ✅ IMPLEMENTED
```php
// GOOD
echo htmlspecialchars($name); // Escapes HTML characters
```

### Cross-Site Request Forgery (CSRF)
**Vulnerability:**
```html
<!-- Attacker's website -->
<img src="http://yoursite.com/donate.php?amount=1000" />
```

**Prevention:** ✅ IMPLEMENTED
```php
// Generate token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Verify on submission
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF attack detected');
}
```

### Weak Authentication
**Vulnerability:**
```php
// BAD - Storing plain text passwords
$password = $_POST['password'];
$query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
```

**Prevention:** ✅ IMPLEMENTED
```php
// GOOD - Using password hashing
$hashed = password_hash($password, PASSWORD_DEFAULT);
if (password_verify($password, $hashed)) {
    // Login successful
}
```

### Insecure Direct Object References
**Vulnerability:**
```php
// BAD - User can change ID in URL
$user = getRow("SELECT * FROM users WHERE id = " . $_GET['id']);
```

**Prevention:**
```php
// GOOD - Verify ownership
$user_id = intval($_SESSION['user_id']);
$order = getRow("SELECT * FROM orders WHERE id = ? AND user_id = ?", [$_GET['id'], $user_id]);
```

---

## Security Headers

Add to your .htaccess or nginx config:

```
# Prevent MIME type sniffing
Header set X-Content-Type-Options "nosniff"

# Clickjacking protection
Header set X-Frame-Options "SAMEORIGIN"

# XSS protection
Header set X-XSS-Protection "1; mode=block"

# Enforce HTTPS
Header set Strict-Transport-Security "max-age=31536000; includeSubDomains"

# Content Security Policy
Header set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'"

# Disable caching for sensitive pages
Header set Cache-Control "no-cache, no-store, must-revalidate"
```

---

## Rate Limiting (To Implement)

```php
// Prevent brute force attacks
function rateLimit($ip, $action = 'login', $max_attempts = 5, $time_window = 300) {
    $cache_key = "rate_limit:" . $ip . ":" . $action;
    
    // Check attempts
    $attempts = apcu_fetch($cache_key) ?: 0;
    
    if ($attempts >= $max_attempts) {
        return false; // Too many attempts
    }
    
    // Increment counter
    apcu_store($cache_key, $attempts + 1, $time_window);
    return true;
}

// Usage in login
if (!rateLimit($_SERVER['REMOTE_ADDR'], 'login')) {
    die('Too many login attempts. Try again later.');
}
```

---

## Regular Security Audits

### Monthly
- [ ] Check error logs
- [ ] Verify backups
- [ ] Review user logins
- [ ] Check file integrity

### Quarterly
- [ ] Security scanning tools
- [ ] Code review
- [ ] Penetration testing
- [ ] Dependency updates

### Annually
- [ ] Full security audit
- [ ] Compliance check (if applicable)
- [ ] Update security policies
- [ ] Staff training

---

## Incident Response

If you detect suspicious activity:

1. **Isolate:** Take affected system offline
2. **Assess:** Determine scope and impact
3. **Contain:** Prevent further damage
4. **Eradicate:** Remove the threat
5. **Recover:** Restore from clean backup
6. **Learn:** Update security measures

---

## Resources & Tools

### Security Testing Tools
- OWASP ZAP (Free vulnerability scanner)
- Burp Suite Community (Web security testing)
- SQLMap (SQL injection testing)
- Nmap (Network scanning)

### Security Standards
- OWASP Top 10
- NIST Cybersecurity Framework
- PCI DSS (for payment processing)
- CIS Controls

### PHP Security
- PHP Security Manual: https://www.php.net/manual/en/security.php
- OWASP PHP Configuration Guide
- PHPStan (Static analysis)
- PHP-CS-Fixer (Code standards)

---

## Compliance

If handling payments or sensitive data:

### PCI DSS (Payment Card Industry)
- Minimum required for credit card processing
- Regular compliance audits (quarterly)
- Network segmentation
- Penetration testing annually

### GDPR (General Data Protection)
- Data minimization
- User consent
- Right to be forgotten
- Data breach notification

### India's DPDP Act
- Data localization
- User consent
- Security standards
- Regular audits

---

## Questions to Ask Before Production

- [ ] Is HTTPS enabled on all pages?
- [ ] Are all passwords hashed?
- [ ] Are all queries using prepared statements?
- [ ] Do all forms have CSRF tokens?
- [ ] Are all user inputs validated?
- [ ] Is error logging enabled?
- [ ] Are sensitive files protected?
- [ ] Is there a backup and recovery plan?
- [ ] Have security scans been run?
- [ ] Is there a security policy document?

---

**Last Updated:** February 14, 2026
**Status:** Ready for Review
