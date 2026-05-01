<?php
class PermissionManager {
    private $conn;
    private $user_id;
    private $user_profile_id;
    private $profile_slug;
    private $permissions_cache = null;

    public function __construct($conn, $user_id) {
        $this->conn    = $conn;
        $this->user_id = $user_id;
        $this->loadUserProfile();
    }

    private function loadUserProfile() {
        $stmt = $this->conn->prepare("SELECT profile_id, p.slug FROM users u INNER JOIN profiles p ON u.profile_id = p.id WHERE u.id = ? AND u.active = 1");
        if (!$stmt) throw new Exception("Error al cargar perfil de usuario");
        $stmt->bind_param('s', $this->user_id);
        $stmt->execute();
        $stmt->bind_result($profile_id, $slug);
        if (!$stmt->fetch()) {
            $stmt->close();
            throw new Exception("Usuario no encontrado o inactivo");
        }
        $this->user_profile_id = $profile_id;
        $this->profile_slug    = $slug;
        $stmt->close();
    }

    public function hasPermission($module_slug, $permission_slug) {
        $stmt = $this->conn->prepare("
            SELECT pp.can_access
            FROM profile_permissions pp
            INNER JOIN module_permissions mp ON pp.module_permission_id = mp.id
            INNER JOIN modules m ON mp.module_id = m.id
            INNER JOIN permissions p ON mp.permission_id = p.id
            WHERE pp.profile_id = ? AND m.slug = ? AND p.slug = ? AND m.active = 1 AND pp.can_access = 1
            LIMIT 1
        ");
        if (!$stmt) return false;
        $stmt->bind_param('sss', $this->user_profile_id, $module_slug, $permission_slug);
        $stmt->execute();
        $stmt->store_result();
        $found = $stmt->num_rows > 0;
        $stmt->close();
        return $found;
    }

    public function getModulePermissions($module_slug) {
        $stmt = $this->conn->prepare("
            SELECT p.slug, pp.can_access
            FROM profile_permissions pp
            INNER JOIN module_permissions mp ON pp.module_permission_id = mp.id
            INNER JOIN modules m ON mp.module_id = m.id
            INNER JOIN permissions p ON mp.permission_id = p.id
            WHERE pp.profile_id = ? AND m.slug = ? AND m.active = 1
            ORDER BY p.name ASC
        ");
        if (!$stmt) return [];
        $stmt->bind_param('ss', $this->user_profile_id, $module_slug);
        $stmt->execute();
        $result = $stmt->get_result();
        $perms  = [];
        while ($row = $result->fetch_assoc()) {
            $perms[$row['slug']] = (bool)$row['can_access'];
        }
        $stmt->close();
        return $perms;
    }

    public function getUserPermissions() {
        if ($this->permissions_cache !== null) return $this->permissions_cache;

        $stmt = $this->conn->prepare("
            SELECT m.slug as module_slug, p.slug as permission_slug, pp.can_access
            FROM profile_permissions pp
            INNER JOIN module_permissions mp ON pp.module_permission_id = mp.id
            INNER JOIN modules m ON mp.module_id = m.id
            INNER JOIN permissions p ON mp.permission_id = p.id
            WHERE pp.profile_id = ? AND m.active = 1
            ORDER BY m.name ASC, p.name ASC
        ");
        if (!$stmt) return [];
        $stmt->bind_param('s', $this->user_profile_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $permissions = [];
        while ($row = $result->fetch_assoc()) {
            $mod = $row['module_slug'];
            if (!isset($permissions[$mod])) $permissions[$mod] = [];
            $permissions[$mod][$row['permission_slug']] = (bool)$row['can_access'];
        }
        $stmt->close();
        $this->permissions_cache = $permissions;
        return $permissions;
    }

    public function isAdmin() {
        return $this->profile_slug === 'admin';
    }

    public function getUserProfile() {
        $stmt = $this->conn->prepare("SELECT id, name, slug, description FROM profiles WHERE id = ? AND active = 1");
        if (!$stmt) return null;
        $stmt->bind_param('s', $this->user_profile_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result ?: null;
    }
}
