# SameBit - Privileges & RBAC Reference

## Overview

SameBit implements a granular Role-Based Access Control (RBAC) system with the following hierarchy:

```
Users -> Profiles -> Profile Permissions -> Module Permissions -> Permissions
                                                                   |
                                                              Modules
```

- **Modules**: Functional areas of the system
- **Permissions**: Available actions (view, create, edit, generate reports)
- **Profiles**: Reusable sets of permission grants
- **Users**: Assigned to exactly one profile

---

## Database Structure

```
modules                 -> System modules (e.g. Calls-SameBit, Medicine-SameComed)
permissions             -> Available actions (view, create, edit, report_x, report_y)
module_permissions      -> Module <-> Permission matrix (10 combinations)
profiles                -> User profiles/roles (Admin, Operator, Viewer)
profile_permissions     -> Profile <-> ModulePermission with can_access (0/1)
users                   -> Users with profile_id FK
```

---

## Default Configuration

### Modules

| ID | Name | Slug | Description |
|---|---|---|---|
| mod-001 | Calls - SameBit | `llamadas_samebit` | Call and priority management |
| mod-002 | Medicine - SameComed | `medicina_samecomed` | Medication management |

### Permissions

| ID | Name | Slug | Description |
|---|---|---|---|
| perm-001 | View | `view` | Read data |
| perm-002 | Create | `create` | Create new records |
| perm-003 | Edit | `edit` | Modify existing records |
| perm-004 | Generate Report X | `generate_report_x` | Generate X-type report |
| perm-005 | Generate Report Y | `generate_report_y` | Generate Y-type report |

### Profiles & Default Permissions

| Profile | Module | View | Create | Edit | Report X | Report Y |
|---|---|---|---|---|---|---|
| **Admin** | Calls | Yes | Yes | Yes | Yes | Yes |
| **Admin** | Medicine | Yes | Yes | Yes | Yes | Yes |
| **Operator** | Calls | Yes | Yes | Yes | No | No |
| **Operator** | Medicine | Yes | Yes | Yes | No | No |
| **Viewer** | Calls | Yes | No | No | No | No |
| **Viewer** | Medicine | Yes | No | No | No | No |

---

## PermissionManager Class

Location: `config/PermissionManager.php`

### Initialization

```php
require_once 'config/setup.php';
require_once 'config/PermissionManager.php';

$pm = new PermissionManager($pdo, $_SESSION['user_id']);
```

### Available Methods

| Method | Returns | Description |
|---|---|---|
| `hasPermission($module_slug, $permission_slug)` | `bool` | Check specific permission |
| `getModulePermissions($module_slug)` | `array` | All permissions for a module |
| `getUserPermissions()` | `array` | All permissions across all modules |
| `getUserProfile()` | `array\|null` | User's profile data |
| `isAdmin()` | `bool` | Check if user has admin profile |
| `getAvailableModules()` | `array` | List of active modules |

### Quick Guard Function

```php
// Blocks with 403 Forbidden if permission denied
requirePermission('module_slug', 'action', $pdo, $_SESSION['user_id']);
```

---

## Usage Examples

### Check Permission Before Action

```php
$pm = new PermissionManager($pdo, $_SESSION['user_id']);

if ($pm->hasPermission('llamadas_samebit', 'create')) {
    // User can create calls
}
```

### Guard an AJAX Endpoint

```php
<?php
require_once 'config/setup.php';
require_once 'config/PermissionManager.php';

// Returns 403 if no permission
requirePermission('medicina_samecomed', 'edit', $pdo, $_SESSION['user_id']);

// If we reach here, user has permission
$data = $_POST;
```

### Conditional UI Rendering

```php
$pm = new PermissionManager($pdo, $_SESSION['user_id']);
$perms = $pm->getModulePermissions('medicina_samecomed');

if ($perms['create']): ?>
    <button class="btn btn-primary">New Medicine</button>
<?php endif; ?>

<?php if ($perms['generate_report_x']): ?>
    <button class="btn">Report X</button>
<?php endif; ?>
```

### Full Dashboard Example

```php
<?php
require_once 'config/setup.php';
require_once 'config/PermissionManager.php';

$pm = new PermissionManager($pdo, $_SESSION['user_id']);
$user_profile = $pm->getUserProfile();
$modules = $pm->getAvailableModules();
?>

<div class="dashboard">
    <h1>Welcome, <?php echo htmlspecialchars($user_profile['names']); ?></h1>
    <p>Profile: <?php echo htmlspecialchars($user_profile['name']); ?></p>

    <?php foreach ($modules as $module): ?>
        <?php $perms = $pm->getModulePermissions($module['slug']); ?>
        <?php if ($perms['view']): ?>
            <div class="module-card">
                <h3><?php echo htmlspecialchars($module['name']); ?></h3>
                <?php if ($perms['create']): ?>
                    <button class="btn btn-primary">Create</button>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
```

---

## Admin Operations

### Change User Profile

```php
$stmt = $pdo->prepare("UPDATE users SET profile_id = ? WHERE id = ?");
$stmt->execute(['prof-002', $user_id]);
```

### Create New Profile

```php
$stmt = $pdo->prepare("INSERT INTO profiles (id, name, slug, description, active) VALUES (?, ?, ?, ?, 1)");
$stmt->execute(['prof-004', 'Reviewer', 'reviewer', 'Read-only with reports']);
// Then assign module_permissions individually
```

### Grant Permission to Profile

```php
// Find the module_permission ID
$stmt = $pdo->prepare("
    SELECT mp.id FROM module_permissions mp
    INNER JOIN modules m ON mp.module_id = m.id
    INNER JOIN permissions p ON mp.permission_id = p.id
    WHERE m.slug = ? AND p.slug = ?
");
$stmt->execute(['llamadas_samebit', 'create']);
$mp_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

// Grant access
$stmt = $pdo->prepare("
    INSERT INTO profile_permissions (profile_id, module_permission_id, can_access)
    VALUES (?, ?, 1)
    ON DUPLICATE KEY UPDATE can_access = 1
");
$stmt->execute(['prof-002', $mp_id]);
```

### Revoke Permission

```php
$stmt = $pdo->prepare("
    UPDATE profile_permissions SET can_access = 0
    WHERE profile_id = ? AND module_permission_id = ?
");
$stmt->execute(['prof-002', $mp_id]);
```

### Add New Module

```sql
INSERT INTO modules (id, name, slug, description)
VALUES (UUID(), 'My Module', 'my_module', 'Description');

-- Then create module_permissions for each existing permission
-- Then assign to profiles as needed
```

---

## Admin UI

- **Permissions panel**: `pages/admin_permissions.php` (Admin only)
- **User management**: `pages/admin_users.php` (Admin only)

Both pages check `$pm->isAdmin()` before rendering and return 403 for non-admins.

---

## Files Reference

| File | Purpose |
|---|---|
| `config/PermissionManager.php` | Permission manager class |
| `config/update_permission.php` | AJAX endpoint for updating permissions |
| `config/example_permission_check.php` | Integration example |
| `config/permission_queries.sql` | Useful SQL queries |
| `pages/admin_permissions.php` | Permission administration UI |
| `pages/admin_users.php` | User management UI |
| `database/seed-privileges.sql` | Initial RBAC data |

---

## FAQ

**Q: Can a user have multiple profiles?**
No. Each user is assigned exactly one profile. If you need more granularity, create a combined profile.

**Q: What happens if a module is deactivated?**
Its permissions are excluded from queries, but data persists in the database.

**Q: How do I add a new permission type?**
1. Insert into `permissions` table
2. Create corresponding `module_permissions` entries
3. Assign to profiles via `profile_permissions`
4. Use the slug in `hasPermission()` checks

**Q: Are permission changes immediate?**
Yes. Changes take effect on the user's next action (permissions are checked per-request with in-memory caching).
