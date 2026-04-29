# SameBit - AI Agent Instructions

**SameBit** is a medical/hospital web application for patient, medication, and treatment management. Built with PHP 7+, MySQL 8.0, jQuery, Bootstrap, and Docker.

## General Purpose

Web platform for:
- Patient registration and management (ID, EPS, IPS, range levels)
- Medication management (create, update, activate/deactivate)
- Diagnosis and medical appointment tracking
- Treatment follow-up (TOP - Treatment Outcome Profile)
- Activity reports and auditing

## Tech Stack

- **Backend**: PHP 7+, MySQL 8.0
- **Frontend**: Bootstrap 5, jQuery 3.5+, DataTables
- **Infrastructure**: Docker + Docker Compose
- **Libraries**: PHPExcel (reports), PHPMailer (emails)

## Project Structure

```
/config/        - Connection, authentication, business logic (*.php)
/pages/         - Main views (dashboard, login, patients, medications)
/Js/            - Frontend JavaScript (jQuery)
/css/           - Styles (Bootstrap + custom)
/database/      - SQL schema and seed data
/img/           - Visual assets
/PHPMailer/     - Email library
/PHPExcel/      - Spreadsheet library
```

## Key Features

### 1. Authentication
- Secure login at `pages/login.php`
- PHP sessions with validation
- Logout at `config/logout.php`

### 2. Patient Management
- Search by ID (`config/dniverification.php`)
- Register new patients (`config/usepatient.php`)
- Data: document type, EPS, IPS, range
- Main view: `pages/dashboard.php`

### 3. Medication Management
- Full CRUD (`pages/medicines_l.php`)
- Active/inactive status (`config/medicines.php`)
- Storage (`config/medicinestored.php`)
- Kardex - movement ledger (`config/newkardexmov.php`)

### 4. Reports and Priorities
- Filter by date, user, ID
- Priority calculation (`config/getPriorities.php`, `config/getTodayPriorities.php`)
- Associated diagnoses (`config/calldiagnosis.php`)

### 5. Treatment Follow-Up (TOP)
- Drug consumption tracking
- Stages: Admission, Discharge, In Treatment, Follow-Up
- Progress visualization with charts
- View: `pages/asisttop.php`

## Code Conventions

### PHP
- Start session via `config/setup.php` (always include)
- Naming: `config/` for logic, `pages/` for views
- JSON output for AJAX: `json_encode($array, JSON_OUT)` (always use JSON_OUT flag)
- UTF-8 encoding on MySQL connection

### JavaScript
- Use jQuery (version 3.5+)
- SweetAlert for dialogs (`Swal.fire()`)
- DataTables for paginated tables (`pagination()`)
- LocalStorage for user data

### Database
- Table `patients`: patients
- Table `medicines`: medications
- Table `kardex`: medicine movements
- Table `priorities`: priorities/appointments
- IDs: UUID (`KP_UUID`), status (`z_xOne`: 1=active, 0=inactive)
- All tables use `ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci`

## Configuration and Execution

### Start Project
```bash
docker-compose up -d
```
- App: `http://localhost:8081/`
- DB: MySQL on `localhost:3306`
- User: `usrconect` / Password: `toor`

### Environment Variables
- `DB_HOST`: localhost or docker service name (`db`)
- Database: `bit_medical`

### Database
- Schema: `database/schema.sql`
- Seeds: `database/seed.sql`
- Auto-initializes when docker starts

## Key Files

| File | Purpose |
|------|---------|
| `index.php` | Entry point, redirects to login or dashboard |
| `config/setup.php` | Global config, sessions, paths |
| `config/config.php` | MySQL connection (defines JSON_OUT constant) |
| `pages/login.php` | Authentication form |
| `pages/dashboard.php` | Main panel after login |
| `Js/dashboard.js` | Reports and filtering logic |
| `config/dniverification.php` | Patient search by ID |
| `config/usepatient.php` | Create/update patient |
| `pages/medicines_l.php` | Medication list and management |
| `config/medicinestored.php` | Save medication |
| `config/medicinedown.php` | Delete/deactivate medication |
| `config/update_password.php` | Password change with bcrypt |
| `pages/asisttop.php` | Treatment tracking |

## Common Patterns

### AJAX POST
```php
// Receive in config/
$data = $_POST;
// Process
// Respond JSON
echo json_encode($response, JSON_OUT);
```

### Frontend Validation
```javascript
if (emptyFields) {
    Swal.fire({ icon: 'error', title: 'Error', text: '...' });
    return false;
}
```

### DataTables
```javascript
pagination('#table-id', '15', columns, 'Title', [1, 'asc'], true);
```

## Suggested Improvements for Agents

1. **Security**: Use prepared statements in all SQL queries (prevent SQL injection)
2. **REST API**: Migrate `config/*.php` to structured endpoints
3. **Validation**: Implement reusable validator classes
4. **Testing**: Add unit tests for critical functions
5. **Documentation**: API docs (phpDocumentor or Swagger)
6. **Performance**: Cache frequent queries (Redis)
7. **Code Quality**: PSR-12, linting, static analysis

## Debug and Troubleshooting

- **DB connection error**: Check `docker ps` and connection in `config/config.php`
- **Expired sessions**: Check `config/setup.php` `session_start()`
- **CORS issues**: Review AJAX headers in `Js/`
- **Slow queries**: Verify indexes in `database/schema.sql`

## Help Resources

- Bootstrap: https://getbootstrap.com/docs/5.0/
- jQuery: https://api.jquery.com/
- DataTables: https://datatables.net/
- PHPMailer: https://github.com/PHPMailer/PHPMailer
- MySQL: https://dev.mysql.com/doc/
