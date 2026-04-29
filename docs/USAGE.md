# SameBit - Usage Guide

This guide covers the end-user operation of the SameBit Medical Management System.

## Table of Contents

1. [Login](#login)
2. [Dashboard](#dashboard)
3. [Call Registration](#call-registration)
4. [Medication Management](#medication-management)
5. [Reports](#reports)
6. [User Management (Admin)](#user-management)
7. [Permission Management (Admin)](#permission-management)
8. [Navigation](#navigation)

---

## Login

1. Open `http://localhost:8081` in your browser
2. Enter your username and password
3. Click **Sign In**

If your session expires (30 minutes of inactivity), you will be redirected back to the login page.

## Dashboard

After logging in, you land on the main dashboard. It provides access to three primary modules:

| Option | Description |
|---|---|
| **Call Registration** | Register new calls and patient incidents |
| **Medications** | Manage medication catalog, inventory, and Kardex |
| **Reports** | View reports, appointments, and priorities |

You will only see modules you have permission to access.

## Call Registration

### Creating a New Call

1. Click **Call Registration** from the Dashboard
2. Search for a patient by document number (DNI)
   - If the patient exists, their data auto-fills
   - If not, fill in the registration form
3. Complete patient data:
   - Names and surnames
   - Document type and number
   - EPS (insurance provider)
   - IPS (healthcare institution)
   - Range level (A, B, C, Sisben)
4. Fill reference and counter-reference information
5. Add diagnosis if applicable
6. Click **Submit**

### Today's Priorities

From the Dashboard you can view today's scheduled appointments and pending diagnoses in the priority table.

## Medication Management

Access from the Dashboard or directly via `pages/medicines_l.php`.

### Available Actions

| Action | Description |
|---|---|
| **List** | View all active/inactive medications |
| **Create** | Add a new medication to the catalog |
| **Edit** | Modify medication details |
| **Activate/Deactivate** | Toggle medication status |
| **Kardex** | View movement history for a medication |

### Kardex (Inventory Movements)

The Kardex tracks all inventory changes for each medication:

- **Entry** - New stock received
- **Exit** - Stock dispensed
- **Balance** - Current inventory level

Each movement records date, quantity, reference, and the user who performed it.

## Reports

From the Dashboard, you can:

- Filter by date range, user, or patient
- View appointments and diagnoses
- Export reports to **Excel** or **PDF**
- View call history with response times

### Export Options

| Format | Library | Notes |
|---|---|---|
| Excel (.xlsx) | PHPExcel | Full data with formatting |
| PDF | Built-in | Formatted for printing |

## User Management

**Access**: `pages/admin_users.php` (Admin profile only)

### Creating a User

1. Click **New User** (top right)
2. Fill in required fields:
   - Username (email, must be unique)
   - Password (minimum 6 characters)
   - First name and last name
   - Profile (Admin, Operator, or Viewer)
   - Active status (checkbox)
3. Click **Save**

### Editing a User

1. Click the **Edit** button on the user row
2. Modify fields as needed
3. Password is optional when editing (leave blank to keep current)
4. Click **Save**

### Viewing User Permissions

1. Click the **View Permissions** button on the user row
2. A modal displays all permissions grouped by module with checkmarks

### Deleting a User

1. Click the **Delete** button on the user row
2. Confirm the action
3. The user is soft-deleted (marked inactive, data preserved)

**Restrictions:**
- You cannot delete your own account
- You cannot remove the last administrator

## Permission Management

**Access**: `pages/admin_permissions.php` (Admin profile only)

### Managing Permissions by Profile

1. Select a profile from the dropdown (Admin, Operator, Viewer)
2. The matrix shows all modules and their permissions
3. Toggle each permission on/off
4. Changes are saved in real-time

### Default Permission Matrix

| Profile | View | Create | Edit | Report X | Report Y |
|---|---|---|---|---|---|
| **Admin** | Yes | Yes | Yes | Yes | Yes |
| **Operator** | Yes | Yes | Yes | No | No |
| **Viewer** | Yes | No | No | No | No |

## Navigation

- **From any module**: Use the top navigation bar to switch between modules
- **From Dashboard**: Use the main panel with module buttons
- **Back button**: Available on all forms to return to the previous screen
- **Logout**: Available in the top right corner of all pages

## Troubleshooting

| Issue | Solution |
|---|---|
| Session expired | Log in again (30-min timeout) |
| Cannot access a module | Check your profile permissions with an admin |
| Export not downloading | Check browser popup/download settings |
| Page not loading | Verify Docker containers are running: `docker ps` |
| UTF-8 characters broken | Verify MySQL uses `utf8mb4` charset |
