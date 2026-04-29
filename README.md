# SameBit - Medical Management System

A web-based medical and hospital management platform designed to optimize patient, medication, and treatment management in healthcare institutions.

Owned by **SAMEIN S.A.S.**

## Features

- **Patient Management** - Full registration with DNI, EPS, IPS, range classification (A/B/C/Sisben)
- **Medication Management** - CRUD catalog with active/inactive control and Kardex inventory tracking
- **Call/Priority Tracking** - Reception workflow, EPS status, appointment scheduling, response time tracking
- **Treatment Follow-up (TOP)** - Drug consumption tracking with stages (Admission/Discharge/In Treatment/Follow-up)
- **Reports & Audit** - Filtered reports by date/user/patient with Excel and PDF export
- **RBAC Permission System** - Granular access control with profiles (Admin, Operator, Viewer)
- **User Management** - CRUD for users with profile assignment and soft delete
- **i18n Support** - Multi-language interface based on device configuration

## Tech Stack

| Component | Technology |
|---|---|
| Backend | PHP 7+ |
| Database | MySQL 8.0 |
| Frontend | Bootstrap 5, jQuery 3.5+ |
| Tables | DataTables.js |
| Reports | PHPExcel |
| Email | PHPMailer |
| Infrastructure | Docker + Docker Compose |

## Quick Start

### With Docker (Recommended)

```bash
cd SameBit
docker-compose up -d
```

- App: `http://localhost:8081`
- MySQL: `localhost:3306`

The database initializes automatically with schema and seed data.

### Without Docker

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p bit_medical < database/seed.sql
```

Configure `config/config.php` with your database credentials, then serve with Apache/Nginx.

## Default Credentials

**Database:**
- Host: `db` (Docker) or `localhost`
- User: `usrconect`
- Password: `toor`
- Database: `bit_medical`
- Port: `3306`

**Application:**
- Username: `admin`
- Password: `admin`
- Profile: Administrator (full access)

## Project Structure

```
SameBit/
├── config/                 # Business logic and API endpoints
│   ├── config.php          # Database connection
│   ├── setup.php           # Global config, sessions, security
│   ├── security.php        # Security functions (bcrypt, CSRF, headers)
│   ├── PermissionManager.php  # RBAC permission manager class
│   └── ...                 # AJAX endpoints
├── pages/                  # HTML/PHP views
│   ├── login.php           # Login form
│   ├── dashboard.php       # Main panel
│   ├── medicines_l.php     # Medication management
│   ├── admin_users.php     # User management (admin only)
│   ├── admin_permissions.php  # Permission management (admin only)
│   └── generales/          # Headers and footers
├── Js/                     # Frontend JavaScript (jQuery)
├── css/                    # Styles (Bootstrap + custom)
├── database/               # SQL schema, seeds, test data
├── docs/                   # Project documentation
├── PHPMailer/              # Email library
├── PHPExcel/               # Excel report library
├── docker-compose.yml      # Docker configuration
├── index.php               # Entry point
└── AGENTS.md               # AI agent context
```

## Documentation

| Document | Description |
|---|---|
| [Usage Guide](docs/USAGE.md) | End-user operation manual |
| [Technical Reference](docs/TECHNICAL.md) | Architecture, API endpoints, database schema |
| [Security](docs/SECURITY.md) | Security implementations, migration guide |
| [Privileges & RBAC](docs/PRIVILEGES.md) | Permission system reference |

## Database Schema

| Table | Purpose |
|---|---|
| `users` | System users with profile assignment |
| `patients` | Patient records (DNI, EPS, IPS) |
| `medicines` | Medication catalog |
| `kardex` | Medication inventory movements |
| `priorities` | Appointments, diagnoses, call tracking |
| `entities` | Health institutions (EPS/IPS) |
| `entity_types` | Entity classification |
| `diagnoses` | Medical diagnosis catalog |
| `movement_categories` | Kardex movement types |
| `modules` | Permission modules |
| `permissions` | Available actions |
| `module_permissions` | Module-permission matrix |
| `profiles` | User profiles/roles |
| `profile_permissions` | Profile-permission grants |

All primary keys use MySQL UUID defaults (VARCHAR 36).

## Contributing

1. Create a feature branch: `git checkout -b feature/new-feature`
2. Commit changes: `git commit -m "description"`
3. Push: `git push origin feature/new-feature`
4. Open a Pull Request

## License

Proprietary - SAMEIN S.A.S.

## Version

- **Version**: 1.0.0
- **Last Updated**: 2026-04-25
- **Status**: In Production
