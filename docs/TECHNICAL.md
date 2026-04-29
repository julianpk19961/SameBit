# SameBit - Technical Reference

## Architecture

SameBit is a vanilla PHP application (no framework) with an AJAX-driven architecture. The backend exposes POST endpoints in `config/` that return JSON; jQuery in `Js/` handles all frontend interactions.

### Entry Point

`index.php` checks for an active session and redirects to either `pages/login.php` or `pages/dashboard.php`.

### Request Flow

```
Browser -> jQuery AJAX POST -> config/*.php -> MySQL -> JSON Response -> jQuery DOM Update
```

### Key Patterns

- **Authentication**: PHP session-based with secure cookies (HttpOnly, Secure, SameSite=Strict)
- **Authorization**: RBAC via `PermissionManager` class with profiles, modules, and granular permissions
- **Security**: bcrypt hashing, prepared statements, CSRF tokens, security headers
- **Database**: All tables use UUID primary keys (VARCHAR 36, MySQL `UUID()` default)
- **Soft Delete**: Records use `z_xOne` column (1=active, 0=inactive)

---

## API Endpoints

All endpoints accept POST requests and return JSON.

### Authentication

| Endpoint | Purpose | Parameters |
|---|---|---|
| `config/conection.php` | User login | `user`, `password` |
| `config/logout.php` | Session termination | (session-based) |

### Patients

| Endpoint | Purpose | Parameters |
|---|---|---|
| `config/dniverification.php` | Lookup patient by DNI | `dni` |
| `config/usepatient.php` | Create/update patient | Full patient fields |
| `config/getPatients.php` | Search patients | `search`, `page` |

### Medications

| Endpoint | Purpose | Parameters |
|---|---|---|
| `config/medicines.php` | List medicines | `status` (active/inactive) |
| `config/medicinestored.php` | Create/update medicine | Full medicine fields |
| `config/medicinedown.php` | Deactivate medicine | `id` |

### Inventory (Kardex)

| Endpoint | Purpose | Parameters |
|---|---|---|
| `config/getkardexmov.php` | Movement history | `medicine_id` |
| `config/getLastKardex.php` | Latest kardex entry | `medicine_id` |
| `config/newkardexmov.php` | Create movement | `medicine_id`, `category_id`, `quantity`, etc. |

### Calls & Priorities

| Endpoint | Purpose | Parameters |
|---|---|---|
| `config/Commit.php` | Create new call/priority | Full call fields (transactional) |
| `config/getPriorities.php` | Priority reports | `date_from`, `date_to`, `user_id`, `patient_id` |
| `config/getTodayPriorities.php` | Today's priorities | (none) |
| `config/getCalls.php` | Call history | `date_from`, `date_to` |
| `config/getCall.php` | Single call details | `call_id` |
| `config/UpdateCall.php` | Update call record | `call_id`, fields to update |
| `config/calldiagnosis.php` | Diagnosis association | `call_id`, `diagnosis_id` |
| `config/callEps.php` | List EPS entities | (none) |
| `config/callips.php` | List IPS entities | (none) |
| `config/callhistory.php` | Call history report | `date_from`, `date_to` |

### Reports

| Endpoint | Purpose | Parameters |
|---|---|---|
| `config/report.php` | Generate report (Excel/PDF) | `type`, `format`, date filters |

### User Management (Admin)

| Endpoint | Purpose | Parameters |
|---|---|---|
| `config/save_user.php` | Create/update user | `user_id`, `username`, `password`, `first_name`, `last_name`, `profile_id`, `active` |
| `config/get_user.php` | Get user data | `user_id` |
| `config/delete_user.php` | Soft-delete user | `user_id` |
| `config/get_user_permissions.php` | Get user permissions | `user_id` |
| `config/update_password.php` | Update password | `current_password`, `new_password` |

### Permissions (Admin)

| Endpoint | Purpose | Parameters |
|---|---|---|
| `config/update_permission.php` | Update profile permission | `profile_id`, `module_permission_id`, `can_access` |

### Utilities

| Endpoint | Purpose | Parameters |
|---|---|---|
| `config/phpMail.php` | Send email | `to`, `subject`, `body` |
| `config/set_language.php` | Switch language | `lang` |

---

## Database Schema

### Core Tables

```sql
-- Patients
CREATE TABLE patients (
    id VARCHAR(36) DEFAULT (UUID()) PRIMARY KEY,
    document_number VARCHAR(50),
    document_type VARCHAR(20),
    names VARCHAR(200),
    surnames VARCHAR(200),
    eps_id VARCHAR(36) REFERENCES entities(id),
    ips_id VARCHAR(36) REFERENCES entities(id),
    range_level VARCHAR(50),
    z_xOne TINYINT DEFAULT 1
);

-- Medicines
CREATE TABLE medicines (
    id VARCHAR(36) DEFAULT (UUID()) PRIMARY KEY,
    name VARCHAR(200),
    reference VARCHAR(100),
    notes TEXT,
    active TINYINT DEFAULT 1
);

-- Kardex (inventory movements)
CREATE TABLE kardex (
    id INT AUTO_INCREMENT PRIMARY KEY,
    medicine_id VARCHAR(36) REFERENCES medicines(id),
    patient_id VARCHAR(36) REFERENCES patients(id),
    category_id VARCHAR(36) REFERENCES movement_categories(id),
    quantity_in DECIMAL(10,2),
    quantity_out DECIMAL(10,2),
    balance DECIMAL(10,2),
    movement_date DATETIME,
    z_xOne TINYINT DEFAULT 1
);

-- Priorities (calls, appointments)
CREATE TABLE priorities (
    id VARCHAR(36) DEFAULT (UUID()) PRIMARY KEY,
    patient_id VARCHAR(36) REFERENCES patients(id),
    eps_id VARCHAR(36) REFERENCES entities(id),
    ips_id VARCHAR(36) REFERENCES entities(id),
    diagnosis_id VARCHAR(36) REFERENCES diagnoses(id),
    contact_type VARCHAR(50),
    appointment_date DATE,
    response_time VARCHAR(100),
    notes TEXT,
    z_xOne TINYINT DEFAULT 1
);
```

### RBAC Tables

```sql
CREATE TABLE modules (
    id VARCHAR(36) DEFAULT (UUID()) PRIMARY KEY,
    name VARCHAR(100),
    slug VARCHAR(100) UNIQUE,
    description TEXT,
    active TINYINT DEFAULT 1
);

CREATE TABLE permissions (
    id VARCHAR(36) DEFAULT (UUID()) PRIMARY KEY,
    name VARCHAR(100),
    slug VARCHAR(100) UNIQUE
);

CREATE TABLE module_permissions (
    id VARCHAR(36) DEFAULT (UUID()) PRIMARY KEY,
    module_id VARCHAR(36) REFERENCES modules(id),
    permission_id VARCHAR(36) REFERENCES permissions(id)
);

CREATE TABLE profiles (
    id VARCHAR(36) DEFAULT (UUID()) PRIMARY KEY,
    name VARCHAR(100),
    slug VARCHAR(100) UNIQUE,
    description TEXT,
    active TINYINT DEFAULT 1
);

CREATE TABLE profile_permissions (
    id VARCHAR(36) DEFAULT (UUID()) PRIMARY KEY,
    profile_id VARCHAR(36) REFERENCES profiles(id),
    module_permission_id VARCHAR(36) REFERENCES module_permissions(id),
    can_access TINYINT DEFAULT 0
);

CREATE TABLE users (
    id VARCHAR(36) DEFAULT (UUID()) PRIMARY KEY,
    username VARCHAR(150) UNIQUE,
    password VARCHAR(255),
    names VARCHAR(200),
    profile_id VARCHAR(36) REFERENCES profiles(id),
    active TINYINT DEFAULT 1
);
```

---

## Code Conventions

### PHP

- Always include `config/setup.php` at the top of every page/endpoint
- Output JSON for AJAX endpoints: `echo json_encode($response);`
- Use prepared statements for all SQL queries
- Use the `PermissionManager` class for authorization checks

```php
require_once 'config/setup.php';
require_once 'config/PermissionManager.php';

$pm = new PermissionManager($pdo, $_SESSION['user_id']);
requirePermission('module_slug', 'action', $pdo, $_SESSION['user_id']);
```

### JavaScript

- Use jQuery 3.5+ for all DOM manipulation
- Use SweetAlert2 for dialogs: `Swal.fire({...})`
- Use DataTables for paginated tables
- CSRF token is automatically injected in AJAX requests via `Js/security.js`

### Response Format

```php
// Success
['status' => 'success', 'data' => [...], 'message' => 'Operation completed']

// Error
['status' => 'error', 'message' => 'Error description']
```

---

## Docker Configuration

### Services

| Service | Image | Port | Purpose |
|---|---|---|---|
| `app` | php:8.1-apache | 8081 | PHP application |
| `db` | mysql:8.0 | 3306 | MySQL database |

### Environment

| Variable | Default | Description |
|---|---|---|
| `DB_HOST` | `db` | Database host (Docker service name or localhost) |
| `DB_NAME` | `bit_medical` | Database name |
| `DB_USER` | `usrconect` | Database user |
| `DB_PASS` | `toor` | Database password |

### Database Initialization

The `database/` directory contains:

| File | Purpose |
|---|---|
| `schema.sql` | Table definitions (auto-executed on first start) |
| `seed.sql` | Initial data (users, profiles, permissions, entities) |
| `seed-privileges.sql` | RBAC tables and default permission matrix |
| `seed-test.sql` | Test data (5000 medicines, 10000 calls) |

---

## Suggested Improvements

| Area | Improvement | Priority |
|---|---|---|
| Database | Add indexes for frequently queried columns | High |
| Caching | Implement Redis for session/query caching | Medium |
| Performance | Lazy loading for large DataTables | Medium |
| Frontend | JS/CSS minification and Gzip compression | Low |
| CDN | Serve Bootstrap/jQuery/DataTables from CDN | Low |
| Reports | Migrate from PHPExcel to PhpSpreadsheet | Medium |
| API | Standardize to REST conventions with proper HTTP methods | Medium |
| Testing | Add PHPUnit tests for critical business logic | High |
| Audit | Create `audit_log` table for tracking changes | Medium |
| Security | Implement brute-force login protection | High |
