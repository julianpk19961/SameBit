# Módulo de Privilegios y Control de Acceso

## 📋 Descripción General

SameBit implementa un sistema granular de control de acceso basado en:
- **Módulos**: Áreas funcionales del sistema (Llamadas-SameBit, Medicina-SameComed)
- **Permisos**: Acciones disponibles (Ver, Crear, Editar, Generar Reporte X/Y)
- **Perfiles**: Conjuntos reutilizables de permisos (Admin, Operador, Visualizador)
- **Usuarios**: Asociados a un perfil que determina sus capacidades

## 🗄️ Estructura de Datos

### Tablas Principales

```
modules                 → Módulos del sistema
permissions             → Acciones/permisos disponibles
module_permissions      → Matriz módulo ↔ permiso
profiles                → Perfiles reutilizables
profile_permissions     → Matriz perfil ↔ módulo_permiso (can_access: 0/1)
users                   → Usuarios con profile_id asignado
```

### Módulos Iniciales

| ID | Nombre | Slug | Descripción |
|---|---|---|---|
| mod-001 | Llamadas - SameBit | `llamadas_samebit` | Gestión de llamadas y prioridades |
| mod-002 | Medicina - SameComed | `medicina_samecomed` | Gestión de medicamentos |

### Permisos Iniciales

| ID | Nombre | Slug | Descripción |
|---|---|---|---|
| perm-001 | Ver | `view` | Consultar datos |
| perm-002 | Crear | `create` | Crear nuevos registros |
| perm-003 | Editar | `edit` | Modificar registros |
| perm-004 | Generar Reporte X | `generate_report_x` | Generar reporte tipo X |
| perm-005 | Generar Reporte Y | `generate_report_y` | Generar reporte tipo Y |

### Perfiles Iniciales

| ID | Nombre | Slug | Descripción |
|---|---|---|---|
| prof-001 | Administrador | `admin` | Acceso total ✅ |
| prof-002 | Operador | `operador` | Ver, Crear, Editar ✅ / Reportes ❌ |
| prof-003 | Visualizador | `visualizador` | Solo Ver ✅ |

## 🔐 Uso en Código

### 1. Importar el PermissionManager

```php
require_once 'config/setup.php';
require_once 'config/PermissionManager.php';
```

### 2. Crear instancia del gestor

```php
$pm = new PermissionManager($pdo, $_SESSION['user_id']);
```

### 3. Verificar permiso específico

```php
// Verificar si el usuario puede crear en el módulo de Llamadas
if ($pm->hasPermission('llamadas_samebit', 'create')) {
    echo "✅ Puedes crear nuevas llamadas";
} else {
    echo "❌ No tienes permiso para crear llamadas";
}
```

### 4. Obtener todos los permisos de un módulo

```php
$perms = $pm->getModulePermissions('medicina_samecomed');
// Resultado:
// [
//   'view' => true,
//   'create' => true,
//   'edit' => false,
//   'generate_report_x' => false,
//   'generate_report_y' => false
// ]

if ($perms['create']) {
    // Mostrar botón de crear
}
```

### 5. Obtener TODOS los permisos del usuario

```php
$all_perms = $pm->getUserPermissions();
// Resultado:
// [
//   'llamadas_samebit' => [
//     'view' => true,
//     'create' => true,
//     ...
//   ],
//   'medicina_samecomed' => [
//     'view' => true,
//     ...
//   ]
// ]
```

### 6. Obtener perfil del usuario

```php
$profile = $pm->getUserProfile();
// ['id' => 'prof-001', 'name' => 'Administrador', 'slug' => 'admin', ...]

if ($pm->isAdmin()) {
    echo "Usuario es administrador";
}
```

### 7. Bloquear acceso si no tiene permiso (AJAX)

```php
<?php
require_once 'config/setup.php';
require_once 'config/PermissionManager.php';

// En config/usepatient.php u otro endpoint AJAX
$pdo = new PDO(...);
$pm = new PermissionManager($pdo, $_SESSION['user_id']);

// Esto genera 403 Forbidden si no tiene permiso
requirePermission('llamadas_samebit', 'create', $pdo, $_SESSION['user_id']);

// Si llega aquí, tiene permiso - continuar con la lógica
$data = $_POST;
// procesar...
```

## 📝 Ejemplo Completo: Dashboard con Control de Permisos

```php
<?php
require_once 'config/setup.php';
require_once 'config/PermissionManager.php';

$pm = new PermissionManager($pdo, $_SESSION['user_id']);
$user_profile = $pm->getUserProfile();
$modules = $pm->getAvailableModules();
?>

<div class="dashboard">
    <h1>Bienvenido, <?php echo $user_profile['name']; ?></h1>
    <p>Perfil: <?php echo $user_profile['name']; ?></p>

    <?php foreach ($modules as $module): ?>
        <div class="module-card">
            <h3><?php echo htmlspecialchars($module['name']); ?></h3>
            
            <?php $perms = $pm->getModulePermissions($module['slug']); ?>
            
            <?php if ($perms['view']): ?>
                <a href="pages/<?php echo $module['slug']; ?>.php" class="btn">Ver</a>
            <?php endif; ?>
            
            <?php if ($perms['create']): ?>
                <button onclick="openCreateModal('<?php echo $module['slug']; ?>')" class="btn btn-primary">Crear</button>
            <?php endif; ?>
            
            <?php if ($perms['generate_report_x']): ?>
                <button onclick="generateReport('<?php echo $module['slug']; ?>', 'x')" class="btn">Reporte X</button>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
```

## 🛠️ Administración de Permisos

### Cambiar perfil de usuario

```php
// Cambiar usuario a perfil "Operador"
$stmt = $pdo->prepare("UPDATE users SET profile_id = ? WHERE id = ?");
$stmt->execute(['prof-002', $user_id]);
```

### Crear nuevo perfil

```php
$profile_id = 'prof-004';
$stmt = $pdo->prepare("INSERT INTO profiles (id, name, slug, description, active) VALUES (?, ?, ?, ?, 1)");
$stmt->execute([$profile_id, 'Revisor', 'revisor', 'Acceso de solo lectura con reportes']);

// Luego asignar permisos module_permission_id uno a uno
// (Ver sección de Agregar Permiso a Perfil)
```

### Agregar permiso a un perfil

```php
// Obtener el ID de module_permission (llamadas_samebit + create)
$stmt = $pdo->prepare("
    SELECT mp.id FROM module_permissions mp
    INNER JOIN modules m ON mp.module_id = m.id
    INNER JOIN permissions p ON mp.permission_id = p.id
    WHERE m.slug = ? AND p.slug = ?
");
$stmt->execute(['llamadas_samebit', 'create']);
$mp_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

// Asignar permiso al perfil
$stmt = $pdo->prepare("
    INSERT INTO profile_permissions (profile_id, module_permission_id, can_access) 
    VALUES (?, ?, 1)
    ON DUPLICATE KEY UPDATE can_access = 1
");
$stmt->execute(['prof-002', $mp_id]);
```

### Revocar permiso de un perfil

```php
$stmt = $pdo->prepare("
    UPDATE profile_permissions 
    SET can_access = 0 
    WHERE profile_id = ? AND module_permission_id = ?
");
$stmt->execute(['prof-002', $mp_id]);
```

## 🔄 Inicialización de Base de Datos

El archivo `database/seed-privileges.sql` crea automáticamente:

✅ Módulos: Llamadas-SameBit, Medicina-SameComed  
✅ Permisos: Ver, Crear, Editar, Reporte X, Reporte Y  
✅ Perfiles: Admin (todos permisos ✅), Operador (limitado), Visualizador (solo lectura)  
✅ Usuario Admin por defecto: `admin` / `admin123` (MD5: `21232f297a57a5a743894a0e4a801fc3`)

## 🚀 Próximas Integraciones

1. **Interfaz de Gestión de Permisos**: Crear página admin para asignar/revocar permisos
2. **Auditoría**: Registrar cambios de permisos en tabla `audit_log`
3. **Herencia de Permisos**: Permitir que roles heredar de otros roles
4. **API REST**: Endpoints `/api/permissions/`, `/api/profiles/` para operaciones CRUD

## ❓ FAQ

**P: ¿Cómo verifico si un usuario es admin?**
```php
if ($pm->isAdmin()) { /* ... */ }
```

**P: ¿Qué pasa si cambio un módulo a inactivo?**
Los permisos del módulo no aparecerán en consultas, pero los datos persisten.

**P: ¿Puedo asignar múltiples perfiles a un usuario?**
Actualmente no. Un usuario = 1 perfil. Si necesitas más granularidad, abre un issue.

**P: ¿Cómo agrego un nuevo módulo?**
```php
// 1. Insertar módulo
INSERT INTO modules (id, name, slug, description) 
VALUES (UUID(), 'Mi Módulo', 'mi_modulo', 'Descripción');

// 2. Crear module_permissions para cada permiso existente
// 3. Asignar a perfiles según necesidad
```

---

**Última actualización**: 2026-04-28  
**Versión**: 1.0
