# SameBit - Security Documentation

## Overview

This document covers all security measures implemented in SameBit as part of the critical security hardening phases.

### Security Metrics

| Metric | Before | After |
|---|---|---|
| Password hashing | MD5 (100%) | bcrypt (100% new, MD5 fallback) |
| SQL Injection vulnerable files | 8 | 0 |
| CSRF protection | 0% | 100% |
| Prepared statements | 0% | 100% |
| Security headers | 0% | 100% |
| Security logging | 0% | 100% |

---

## Phase 1.1: Password Migration (MD5 to bcrypt)

### What Changed

- Passwords are now hashed using `password_hash()` with bcrypt (cost 12)
- MD5 passwords still work during migration (automatic detection)
- On successful login with an MD5 hash, the password is silently upgraded to bcrypt
- New passwords require minimum 8 characters, at least 1 uppercase letter, and 1 number

### Files

| File | Type | Description |
|---|---|---|
| `config/security.php` | New | Centralized security functions |
| `config/conection.php` | Modified | Login with prepared statements and `password_verify()` |
| `config/setup.php` | Modified | Includes security.php and initializes security |
| `config/update_password.php` | New | Secure password update endpoint |

### Migration Steps

```bash
# 1. Backup database
mysqldump -u usrconect -p bit_medical > backup_before_security.sql

# 2. Run migration script
php database/migrate_passwords.php

# 3. Verify login works with existing users

# 4. Delete migration script
rm database/migrate_passwords.php
```

### Password Requirements

- Minimum 8 characters
- At least 1 uppercase letter (A-Z)
- At least 1 digit (0-9)
- Maximum 128 characters

---

## Phase 1.2: SQL Injection Prevention

### Technique: Prepared Statements

All database queries use PDO prepared statements:

```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
```

### Migrated Files

| File | Vulnerability | Fix |
|---|---|---|
| `config/conection.php` | SQL Injection in login | Prepared statements |
| `config/dniverification.php` | SQL Injection in DNI search | Prepared statements + validation |
| `config/usepatient.php` | SQL Injection in patient query | Prepared statements + UUID validation |
| `config/medicines.php` | Unsanitized output | `htmlspecialchars` + `intval` |
| `config/medicinestored.php` | SQL Injection in INSERT | Prepared statements + validation |
| `config/getPatients.php` | SQL Injection in LIKE | Prepared statements + validation |
| `config/getPriorities.php` | Multiple SQL Injection | Prepared statements + date validation |
| `config/Commit.php` | Critical SQL Injection | Prepared statements + transactions |

### Input Validation

- Type checking: `ctype_alnum()`, `is_numeric()`
- Maximum length validation
- Format validation: UUID, email, dates
- Output sanitization: `htmlspecialchars()`, `intval()`, `trim()`

### Transactions

Critical operations use database transactions:

```php
$pdo->beginTransaction();
try {
    // Multiple queries
    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
}
```

---

## Phase 1.3: CSRF Protection

### How It Works

1. A unique CSRF token is generated per session (32 bytes / 256 bits)
2. The token is embedded as a `<meta>` tag and a JavaScript variable
3. `Js/security.js` automatically attaches the token to all AJAX POST requests
4. The server validates the token on every POST request using `hash_equals()`
5. The token is regenerated after each successful validation

### Files

| File | Type | Description |
|---|---|---|
| `Js/security.js` | New | CSRF token handling in AJAX |
| `pages/generales/header.php` | Modified | Includes CSRF meta tag and security.js |
| `config/security.php` | Modified | CSRF generate, validate, regenerate functions |

### Usage in PHP

```php
// Generate a CSRF token field for forms
echo csrf_field();

// Validate received token
if (validate_csrf_token($_POST['csrf_token'])) {
    regenerate_csrf_token();
    // Process form
}
```

### Usage in JavaScript (automatic)

The `ajaxSend` handler in `security.js` automatically adds the token:

```javascript
$.ajax({
    url: 'config/endpoint.php',
    method: 'POST',
    data: { key: 'value' }, // csrf_token added automatically
});
```

---

## Session Security

| Setting | Value | Purpose |
|---|---|---|
| `cookie_httponly` | `On` | Prevents JavaScript access to session cookie |
| `cookie_secure` | `On` | Cookie only sent over HTTPS |
| `cookie_samesite` | `Strict` | Prevents CSRF via cross-site requests |
| `use_strict_mode` | `On` | Rejects uninitialized session IDs |
| Session regeneration | After login | Prevents session fixation |
| Timeout | 30 minutes | Auto-logout on inactivity |

---

## Security Headers

| Header | Value | Purpose |
|---|---|---|
| `X-Content-Type-Options` | `nosniff` | Prevents MIME type sniffing |
| `X-Frame-Options` | `DENY` | Prevents clickjacking |
| `X-XSS-Protection` | `1; mode=block` | Enables browser XSS filter |
| `Content-Security-Policy` | Custom | Restricts resource loading to trusted sources |

---

## Security Logging

All security events are logged to `logs/security.log` in JSON format:

```php
security_log('EVENT_TYPE', 'Description', 'user_identifier');
```

Logged events include:
- Successful/failed login attempts
- CSRF token validation failures
- Password changes
- Permission denied attempts

---

## Security Testing

### SQL Injection Test

```
Input: ' OR '1'='1' --
Expected: System rejects input, query returns no results
```

### XSS Test

```
Input: <script>alert('XSS')</script>
Expected: Output is HTML-escaped, script does not execute
```

### CSRF Test

```
1. Submit POST request without csrf_token parameter
Expected: System returns 403 Forbidden
```

---

## Troubleshooting

| Issue | Solution |
|---|---|
| "Invalid CSRF token" | Verify `Js/security.js` is loaded; reload the page |
| "Not authenticated" | Verify session is active; check `config/setup.php` inclusion |
| Login fails after migration | Verify migration script ran; check `logs/security.log` |
| "Headers already sent" | Ensure no output before `setup.php` inclusion |
| Session expires too quickly | Adjust `SESSION_TIMEOUT` in `config/security.php` |

---

## Future Security Phases

| Phase | Description | Status |
|---|---|---|
| Phase 2 | Advanced session management | Planned |
| Phase 3 | Input validation class | Planned |
| Phase 4 | Security headers hardening | Planned |
| Phase 5 | Brute-force login protection | Planned |
| Phase 6 | Audit log table | Planned |

---

## References

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Password Hashing](https://www.php.net/manual/en/function.password-hash.php)
- [CSRF Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Cross-Site_Request_Forgery_Prevention_Cheat_Sheet.html)
