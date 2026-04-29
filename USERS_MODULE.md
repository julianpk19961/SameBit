# Módulo de Gestión de Usuarios

## 📋 Descripción

El módulo de gestión de usuarios permite a los administradores de SameBit:
- ✅ Crear nuevos usuarios
- ✅ Editar usuarios existentes
- ✅ Asignar perfiles a usuarios
- ✅ Ver permisos detallados de cada usuario
- ✅ Activar/desactivar usuarios
- ✅ Eliminar usuarios

## 🔐 Acceso

**Ubicación**: `http://localhost:8081/pages/admin_users.php`

⚠️ **Restricción**: Solo usuarios con perfil **Administrador** pueden acceder

## 🎯 Funcionalidades

### 1. Vista Principal

Muestra una tabla con todos los usuarios activos e inactivos:
- **Usuario**: Email/username único
- **Nombre**: Nombre completo del usuario
- **Perfil**: Etiqueta con color según el tipo (Admin, Operador, Visualizador)
- **Estado**: Activo ✅ o Inactivo ❌
- **Acciones**: Editar, Ver Permisos, Eliminar

### 2. Crear Nuevo Usuario

**Botón**: "Nuevo Usuario" (arriba a la derecha)

**Campos requeridos**:
- 📧 Usuario (email) - Único en el sistema
- 🔐 Contraseña - Mínimo 6 caracteres
- 👤 Nombre - Requerido
- 👤 Apellido - Requerido
- 🏷️ Perfil - Seleccionar de lista (Admin, Operador, Visualizador)
- ✅ Estado - Checkbox para activar/desactivar

**Validaciones**:
- ✓ Username único
- ✓ Contraseña mínimo 6 caracteres
- ✓ Perfil debe existir y estar activo
- ✓ Campos obligatorios no vacíos

### 3. Editar Usuario

**Acción**: Click en botón "✏️ Editar"

**Cambios permitidos**:
- Cambiar nombre/apellido
- Cambiar perfil
- Cambiar contraseña (opcional)
- Activar/desactivar

**Restricciones**:
- No puedes eliminar el último admin
- No puedes editar a ti mismo (cambiar privilegios)

### 4. Ver Permisos

**Acción**: Click en botón "🔍 Ver Permisos"

**Muestra**:
- Nombre del perfil actual
- Todos los módulos del sistema
- Para cada módulo: todos los permisos (✅ o ❌)

**Ejemplo**:
```
Perfil: Administrador

Llamadas - SameBit
  - view: ✅
  - create: ✅
  - edit: ✅
  - generate_report_x: ✅
  - generate_report_y: ✅

Medicina - SameComed
  - view: ✅
  - create: ✅
  - edit: ✅
  - generate_report_x: ✅
  - generate_report_y: ✅
```

### 5. Eliminar Usuario

**Acción**: Click en botón "🗑️ Eliminar"

⚠️ **Importante**: 
- Se realiza "soft delete" (marca como inactivo)
- Los datos se preservan (no se eliminan de la BD)
- No puedes eliminar tu propio usuario
- Requiere confirmación

## 🔄 Endpoints AJAX

### `config/get_user.php`
**Propósito**: Obtener datos de un usuario para edición
```
POST /config/get_user.php
Parámetro: user_id

Respuesta:
{
  "success": true,
  "data": {
    "id": "user-id",
    "username": "usuario@email.com",
    "first_name": "Juan",
    "last_name": "Pérez",
    "profile_id": "prof-001",
    "active": 1
  }
}
```

### `config/save_user.php`
**Propósito**: Crear o actualizar usuario
```
POST /config/save_user.php
Parámetros:
  - user_id (vacío para crear)
  - username
  - password (requerido si crear, opcional si editar)
  - first_name
  - last_name
  - profile_id
  - active (0 o 1)

Respuesta:
{
  "success": true,
  "message": "Usuario creado/actualizado exitosamente"
}
```

### `config/get_user_permissions.php`
**Propósito**: Obtener permisos de un usuario
```
POST /config/get_user_permissions.php
Parámetro: user_id

Respuesta:
{
  "success": true,
  "profile": {
    "id": "prof-001",
    "name": "Administrador",
    "slug": "admin"
  },
  "permissions": {
    "llamadas_samebit": {
      "view": true,
      "create": true,
      "edit": true,
      "generate_report_x": true,
      "generate_report_y": true
    },
    "medicina_samecomed": { ... }
  }
}
```

### `config/delete_user.php`
**Propósito**: Eliminar usuario (soft delete)
```
POST /config/delete_user.php
Parámetro: user_id

Respuesta:
{
  "success": true,
  "message": "Usuario eliminado exitosamente"
}
```

## 🎨 Interfaz Visual

### Colores de Perfiles

| Perfil | Color | Badge |
|--------|-------|-------|
| Admin | Rojo | `#dc3545` |
| Operador | Amarillo | `#ffc107` |
| Visualizador | Azul | `#17a2b8` |

### Estados

| Estado | Icono | Clase |
|--------|-------|-------|
| Activo | ✅ | `status-active` |
| Inactivo | ❌ | `status-inactive` |

## 📝 Ejemplos de Uso

### Crear usuario "operador1"

1. Click "Nuevo Usuario"
2. Usuario: `operador1@empresa.com`
3. Contraseña: `micontraseña123`
4. Nombre: `Carlos`
5. Apellido: `García`
6. Perfil: `Operador`
7. Estado: ✅ Activo
8. Click "Guardar"

### Cambiar perfil de usuario

1. Click "✏️ Editar" en el usuario
2. Cambiar "Perfil" a nuevo valor
3. Click "Guardar"
4. ✅ El usuario tendrá nuevos permisos inmediatamente

### Ver qué puede hacer un usuario

1. Click "🔍 Ver Permisos" en el usuario
2. Se abre modal con todos los permisos
3. ✅ = puede hacer, ❌ = no puede hacer

## 🔐 Seguridad

✅ **Validaciones implementadas**:
- Solo admins pueden acceder
- Username único
- Contraseña mínimo 6 caracteres
- No puedes eliminarte a ti mismo
- Perfiles validados contra BD
- SQL injection prevenido (prepared statements)

## 🚀 Integración con Sistema de Privilegios

Este módulo está completamente integrado con el sistema de privilegios:

1. Cuando asignas un perfil a un usuario
2. El usuario hereda TODOS los permisos de ese perfil
3. Los permisos se validan en cada acción

**Ejemplo de validación en código**:

```php
require_once 'config/PermissionManager.php';

$pm = new PermissionManager($pdo, $_SESSION['user_id']);

// Verificar permiso
if ($pm->hasPermission('llamadas_samebit', 'create')) {
    // Usuario puede crear llamadas
}
```

## 📊 Tabla de Referencia - Permisos por Perfil

| Perfil | Módulo | Ver | Crear | Editar | Reporte X | Reporte Y |
|--------|--------|-----|-------|--------|-----------|-----------|
| Admin | Llamadas | ✅ | ✅ | ✅ | ✅ | ✅ |
| Admin | Medicina | ✅ | ✅ | ✅ | ✅ | ✅ |
| Operador | Llamadas | ✅ | ✅ | ✅ | ❌ | ❌ |
| Operador | Medicina | ✅ | ✅ | ✅ | ❌ | ❌ |
| Visualizador | Llamadas | ✅ | ❌ | ❌ | ❌ | ❌ |
| Visualizador | Medicina | ✅ | ❌ | ❌ | ❌ | ❌ |

## ❓ FAQ

**P: ¿Puedo crear múltiples administradores?**
Sí, puedes crear tantos usuarios Admin como necesites.

**P: ¿Qué pasa si cambio de perfil a un usuario?**
Los nuevos permisos se aplican inmediatamente en su siguiente acción.

**P: ¿Se pueden recuperar usuarios eliminados?**
Sí, se marca como inactivo. Un admin puede editar y reactivar.

**P: ¿Puedo cambiar el perfil del usuario admin principal?**
No, no se permite eliminar o cambiar privilegios del usuario que está logueado.

**P: ¿Cómo reinicio la contraseña de un usuario?**
Edita el usuario y asigna una nueva contraseña.

---

**Última actualización**: 2026-04-29
**Versión**: 1.0
