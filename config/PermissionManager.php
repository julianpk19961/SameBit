<?php
/**
 * PermissionManager - Gestión de permisos y privilegios por perfil
 * 
 * Uso:
 *  $pm = new PermissionManager($pdo, $user_id);
 *  
 *  if ($pm->hasPermission('llamadas_samebit', 'create')) {
 *      // Usuario puede crear en llamadas
 *  }
 *  
 *  $perms = $pm->getUserPermissions(); // Todos los permisos del usuario
 *  $module_perms = $pm->getModulePermissions('medicina_samecomed'); // Permisos en un módulo
 */

class PermissionManager {
    private $pdo;
    private $user_id;
    private $user_profile_id;
    private $permissions_cache = null;

    public function __construct($pdo, $user_id) {
        $this->pdo = $pdo;
        $this->user_id = $user_id;
        $this->loadUserProfile();
    }

    /**
     * Load user profile from database
     */
    private function loadUserProfile() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT profile_id FROM users WHERE id = ? AND active = 1
            ");
            $stmt->execute([$this->user_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                throw new Exception("Usuario no encontrado o inactivo");
            }

            $this->user_profile_id = $result['profile_id'];
        } catch (Exception $e) {
            error_log("PermissionManager Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check if user has a specific permission in a module
     * 
     * @param string $module_slug Ej: 'llamadas_samebit', 'medicina_samecomed'
     * @param string $permission_slug Ej: 'view', 'create', 'edit', 'generate_report_x'
     * @return bool
     */
    public function hasPermission($module_slug, $permission_slug) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT pp.can_access
                FROM profile_permissions pp
                INNER JOIN module_permissions mp ON pp.module_permission_id = mp.id
                INNER JOIN modules m ON mp.module_id = m.id
                INNER JOIN permissions p ON mp.permission_id = p.id
                WHERE pp.profile_id = ?
                  AND m.slug = ?
                  AND p.slug = ?
                  AND m.active = 1
                  AND pp.can_access = 1
                LIMIT 1
            ");
            $stmt->execute([$this->user_profile_id, $module_slug, $permission_slug]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return (bool)($result && $result['can_access']);
        } catch (Exception $e) {
            error_log("PermissionManager hasPermission Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all permissions for a specific module
     * 
     * @param string $module_slug
     * @return array ['permission_slug' => true/false, ...]
     */
    public function getModulePermissions($module_slug) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT p.slug, pp.can_access
                FROM profile_permissions pp
                INNER JOIN module_permissions mp ON pp.module_permission_id = mp.id
                INNER JOIN modules m ON mp.module_id = m.id
                INNER JOIN permissions p ON mp.permission_id = p.id
                WHERE pp.profile_id = ?
                  AND m.slug = ?
                  AND m.active = 1
                ORDER BY p.name ASC
            ");
            $stmt->execute([$this->user_profile_id, $module_slug]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $perms = [];
            foreach ($results as $row) {
                $perms[$row['slug']] = (bool)$row['can_access'];
            }

            return $perms;
        } catch (Exception $e) {
            error_log("PermissionManager getModulePermissions Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all permissions for current user (all modules)
     * 
     * @return array ['module_slug' => ['permission_slug' => true/false, ...], ...]
     */
    public function getUserPermissions() {
        if ($this->permissions_cache !== null) {
            return $this->permissions_cache;
        }

        try {
            $stmt = $this->pdo->prepare("
                SELECT m.slug as module_slug, p.slug as permission_slug, pp.can_access
                FROM profile_permissions pp
                INNER JOIN module_permissions mp ON pp.module_permission_id = mp.id
                INNER JOIN modules m ON mp.module_id = m.id
                INNER JOIN permissions p ON mp.permission_id = p.id
                WHERE pp.profile_id = ?
                  AND m.active = 1
                ORDER BY m.name ASC, p.name ASC
            ");
            $stmt->execute([$this->user_profile_id]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $permissions = [];
            foreach ($results as $row) {
                $module = $row['module_slug'];
                if (!isset($permissions[$module])) {
                    $permissions[$module] = [];
                }
                $permissions[$module][$row['permission_slug']] = (bool)$row['can_access'];
            }

            $this->permissions_cache = $permissions;
            return $permissions;
        } catch (Exception $e) {
            error_log("PermissionManager getUserPermissions Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get user's profile information
     * 
     * @return array ['id', 'name', 'slug', 'description']
     */
    public function getUserProfile() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, name, slug, description 
                FROM profiles 
                WHERE id = ? AND active = 1
            ");
            $stmt->execute([$this->user_profile_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Exception $e) {
            error_log("PermissionManager getUserProfile Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all available modules
     * 
     * @return array
     */
    public function getAvailableModules() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, name, slug, description 
                FROM modules 
                WHERE active = 1 
                ORDER BY name ASC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("PermissionManager getAvailableModules Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Check if user is admin (has all permissions)
     * 
     * @return bool
     */
    public function isAdmin() {
        try {
            $profile = $this->getUserProfile();
            return $profile && $profile['slug'] === 'admin';
        } catch (Exception $e) {
            error_log("PermissionManager isAdmin Error: " . $e->getMessage());
            return false;
        }
    }
}

// Helper function for quick permission check
function checkPermission($module_slug, $permission_slug, $pdo, $user_id) {
    static $pm = null;
    if ($pm === null) {
        $pm = new PermissionManager($pdo, $user_id);
    }
    return $pm->hasPermission($module_slug, $permission_slug);
}

// Helper function to deny access if permission missing
function requirePermission($module_slug, $permission_slug, $pdo, $user_id) {
    if (!checkPermission($module_slug, $permission_slug, $pdo, $user_id)) {
        http_response_code(403);
        die(json_encode([
            'success' => false,
            'message' => 'Acceso denegado. No tienes permisos para esta acción.'
        ]));
    }
}
?>
