<?php
require_once 'setup.php';

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid request'], JSON_OUT));
}

try {
    if (!is_admin()) {
        http_response_code(403);
        die(json_encode(['success' => false, 'message' => 'Acceso denegado'], JSON_OUT));
    }

    $action = $_POST['action'] ?? '';

    switch ($action) {

        case 'list':
            $profiles = [];
            $result = $conn->query("SELECT id, name, slug, description, active FROM profiles ORDER BY name ASC");
            if ($result) {
                while ($row = $result->fetch_assoc()) $profiles[] = $row;
            }

            $modules = [];
            $result = $conn->query("SELECT id, name, slug, description FROM modules WHERE active = 1 ORDER BY name ASC");
            if ($result) {
                while ($row = $result->fetch_assoc()) $modules[] = $row;
            }

            $permissions = [];
            $result = $conn->query("SELECT id, name, slug FROM permissions ORDER BY name ASC");
            if ($result) {
                while ($row = $result->fetch_assoc()) $permissions[] = $row;
            }

            // Matriz: qué permisos soporta cada módulo
            $matrix = [];
            $result = $conn->query("
                SELECT mp.id as mp_id, m.id as module_id, p.id as permission_id,
                       m.slug as module_slug, p.slug as permission_slug
                FROM module_permissions mp
                INNER JOIN modules m ON mp.module_id = m.id
                INNER JOIN permissions p ON mp.permission_id = p.id
            ");
            if ($result) {
                while ($row = $result->fetch_assoc()) $matrix[] = $row;
            }

            // Grants: qué tiene activado cada perfil
            $grants = [];
            $result = $conn->query("
                SELECT pp.profile_id, pp.can_access,
                       m.slug as module_slug, p.slug as permission_slug
                FROM profile_permissions pp
                INNER JOIN module_permissions mp ON pp.module_permission_id = mp.id
                INNER JOIN modules m ON mp.module_id = m.id
                INNER JOIN permissions p ON mp.permission_id = p.id
            ");
            if ($result) {
                while ($row = $result->fetch_assoc()) $grants[] = $row;
            }

            // Usuarios por perfil
            $user_counts = [];
            $result = $conn->query("SELECT profile_id, COUNT(*) as cnt FROM users WHERE active = 1 GROUP BY profile_id");
            if ($result) {
                while ($row = $result->fetch_assoc()) $user_counts[$row['profile_id']] = (int)$row['cnt'];
            }

            echo json_encode([
                'success'     => true,
                'profiles'    => $profiles,
                'modules'     => $modules,
                'permissions' => $permissions,
                'matrix'      => $matrix,
                'grants'      => $grants,
                'user_counts' => $user_counts,
            ], JSON_OUT);
            break;

        case 'save_profile':
            $id          = trim($_POST['id'] ?? '');
            $name        = trim($_POST['name'] ?? '');
            $slug        = trim($_POST['slug'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $active      = (int)($_POST['active'] ?? 1);

            if (!$name || !$slug) {
                throw new Exception('Nombre y slug son obligatorios');
            }
            if (!preg_match('/^[a-z0-9_]+$/', $slug)) {
                throw new Exception('El slug solo puede contener letras minusculas, numeros y guiones bajos');
            }

            if ($id) {
                // Editar — slug no se cambia
                $stmt = $conn->prepare("UPDATE profiles SET name = ?, description = ?, active = ? WHERE id = ?");
                $stmt->bind_param('ssis', $name, $description, $active, $id);
                $stmt->execute();
                $stmt->close();
                $msg = 'Perfil actualizado correctamente';
            } else {
                // Crear — verificar unicidad de slug
                $check = $conn->prepare("SELECT id FROM profiles WHERE slug = ?");
                $check->bind_param('s', $slug);
                $check->execute();
                $check->store_result();
                if ($check->num_rows > 0) {
                    $check->close();
                    throw new Exception('Ya existe un perfil con ese slug');
                }
                $check->close();

                $stmt = $conn->prepare("INSERT INTO profiles (name, slug, description, active) VALUES (?, ?, ?, ?)");
                $stmt->bind_param('sssi', $name, $slug, $description, $active);
                $stmt->execute();
                $stmt->close();

                // Recuperar el UUID generado
                $fetch = $conn->prepare("SELECT id FROM profiles WHERE slug = ?");
                $fetch->bind_param('s', $slug);
                $fetch->execute();
                $fetch->bind_result($new_profile_id);
                $fetch->fetch();
                $fetch->close();

                // Pre-poblar profile_permissions con can_access=0 para todos los module_permissions
                if ($new_profile_id) {
                    $mps = $conn->query("SELECT id FROM module_permissions");
                    if ($mps) {
                        while ($mp = $mps->fetch_assoc()) {
                            $mp_id = $conn->real_escape_string($mp['id']);
                            $p_id  = $conn->real_escape_string($new_profile_id);
                            $conn->query("INSERT IGNORE INTO profile_permissions (profile_id, module_permission_id, can_access)
                                          VALUES ('$p_id', '$mp_id', 0)");
                        }
                    }
                }

                $msg = 'Perfil creado correctamente';
            }

            echo json_encode(['success' => true, 'message' => $msg], JSON_OUT);
            break;

        case 'delete_profile':
            $id = trim($_POST['id'] ?? '');
            if (!$id) throw new Exception('ID de perfil requerido');

            // No eliminar si tiene usuarios activos asignados
            $stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM users WHERE profile_id = ? AND active = 1");
            $stmt->bind_param('s', $id);
            $stmt->execute();
            $cnt = $stmt->get_result()->fetch_assoc()['cnt'];
            $stmt->close();

            if ($cnt > 0) {
                throw new Exception("No se puede eliminar: el perfil tiene {$cnt} usuario(s) activo(s) asignado(s)");
            }

            $stmt = $conn->prepare("DELETE FROM profile_permissions WHERE profile_id = ?");
            $stmt->bind_param('s', $id);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("DELETE FROM profiles WHERE id = ?");
            $stmt->bind_param('s', $id);
            $stmt->execute();
            $affected = $stmt->affected_rows;
            $stmt->close();

            if ($affected === 0) throw new Exception('Perfil no encontrado');

            echo json_encode(['success' => true, 'message' => 'Perfil eliminado correctamente'], JSON_OUT);
            break;

        case 'toggle_grant':
            $profile_id      = trim($_POST['profile_id'] ?? '');
            $module_slug     = trim($_POST['module_slug'] ?? '');
            $permission_slug = trim($_POST['permission_slug'] ?? '');
            $can_access      = (int)($_POST['can_access'] ?? 0);

            if (!$profile_id || !$module_slug || !$permission_slug) {
                throw new Exception('Parametros incompletos');
            }

            $stmt = $conn->prepare("
                SELECT mp.id as mp_id
                FROM module_permissions mp
                INNER JOIN modules m ON mp.module_id = m.id
                INNER JOIN permissions p ON mp.permission_id = p.id
                WHERE m.slug = ? AND p.slug = ? AND m.active = 1
            ");
            $stmt->bind_param('ss', $module_slug, $permission_slug);
            $stmt->execute();
            $mp = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$mp) throw new Exception('Combinacion modulo/permiso no encontrada');

            $mp_id = $mp['mp_id'];

            $stmt = $conn->prepare("
                INSERT INTO profile_permissions (profile_id, module_permission_id, can_access)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE can_access = ?, updated_at = NOW()
            ");
            $stmt->bind_param('ssii', $profile_id, $mp_id, $can_access, $can_access);
            $stmt->execute();
            $stmt->close();

            echo json_encode(['success' => true], JSON_OUT);
            break;

        default:
            throw new Exception('Accion no valida');
    }

} catch (Exception $e) {
    error_log("Error en manage_profiles.php: " . $e->getMessage());
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()], JSON_OUT);
}
