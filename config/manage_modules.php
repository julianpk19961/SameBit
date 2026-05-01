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
            $modules = [];
            $result = $conn->query("SELECT id, name, slug, description, active FROM modules ORDER BY name ASC");
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $modules[] = $row;
                }
            }

            $permissions = [];
            $result = $conn->query("SELECT id, name, slug, description FROM permissions ORDER BY name ASC");
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $permissions[] = $row;
                }
            }

            $profiles = [];
            $result = $conn->query("SELECT id, name, slug, description FROM profiles WHERE active = 1 ORDER BY name ASC");
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $profiles[] = $row;
                }
            }

            $matrix = [];
            $query = "
                SELECT mp.id as mp_id, m.id as module_id, p.id as permission_id,
                       m.slug as module_slug, p.slug as permission_slug
                FROM module_permissions mp
                INNER JOIN modules m ON mp.module_id = m.id
                INNER JOIN permissions p ON mp.permission_id = p.id
            ";
            $result = $conn->query($query);
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $matrix[] = $row;
                }
            }

            $grants = [];
            $query = "
                SELECT pp.id, pp.profile_id, pp.module_permission_id, pp.can_access,
                       m.slug as module_slug, p.slug as permission_slug
                FROM profile_permissions pp
                INNER JOIN module_permissions mp ON pp.module_permission_id = mp.id
                INNER JOIN modules m ON mp.module_id = m.id
                INNER JOIN permissions p ON mp.permission_id = p.id
            ";
            $result = $conn->query($query);
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $grants[] = $row;
                }
            }

            echo json_encode([
                'success' => true,
                'modules' => $modules,
                'permissions' => $permissions,
                'profiles' => $profiles,
                'matrix' => $matrix,
                'grants' => $grants
            ], JSON_OUT);
            break;

        case 'save_module':
            $id = trim($_POST['id'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $active = (int)($_POST['active'] ?? 1);

            if (!$name || !$slug) {
                throw new Exception('Nombre y slug son obligatorios');
            }

            if (!preg_match('/^[a-z0-9_]+$/', $slug)) {
                throw new Exception('El slug solo puede contener letras minusculas, numeros y guiones bajos');
            }

            if ($id) {
                $stmt = $conn->prepare("UPDATE modules SET name = ?, slug = ?, description = ?, active = ? WHERE id = ?");
                $stmt->bind_param('sssis', $name, $slug, $description, $active, $id);
                $stmt->execute();
                $stmt->close();

                if ($active === 0) {
                    $stmt = $conn->prepare("DELETE FROM module_permissions WHERE module_id = ?");
                    $stmt->bind_param('s', $id);
                    $stmt->execute();
                    $stmt->close();
                }
            } else {
                $checkSlug = $conn->prepare("SELECT id FROM modules WHERE slug = ?");
                $checkSlug->bind_param('s', $slug);
                $checkSlug->execute();
                $checkSlug->store_result();
                if ($checkSlug->num_rows > 0) {
                    $checkSlug->close();
                    throw new Exception('Ya existe un modulo con ese slug');
                }
                $checkSlug->close();

                $stmt = $conn->prepare("INSERT INTO modules (name, slug, description, active) VALUES (?, ?, ?, ?)");
                $stmt->bind_param('sssi', $name, $slug, $description, $active);
                $stmt->execute();
                $new_id = $conn->insert_id ? (string)$conn->insert_id : $conn->query("SELECT UUID() as u")->fetch_assoc()['u'];
                $stmt->close();

                if (isset($_POST['permissions']) && is_array($_POST['permissions'])) {
                    foreach ($_POST['permissions'] as $perm_id) {
                        $perm_id = $conn->real_escape_string($perm_id);
                        $conn->query("INSERT IGNORE INTO module_permissions (module_id, permission_id) VALUES ('$new_id', '$perm_id')");
                    }
                }

                if (isset($_POST['grant_profiles']) && is_array($_POST['grant_profiles'])) {
                    foreach ($_POST['grant_profiles'] as $profile_id) {
                        $profile_id = $conn->real_escape_string($profile_id);
                        if (isset($_POST['permissions'])) {
                            foreach ($_POST['permissions'] as $perm_id) {
                                $perm_id = $conn->real_escape_string($perm_id);
                                $conn->query("INSERT IGNORE INTO profile_permissions (profile_id, module_permission_id, can_access)
                                    SELECT '$profile_id', mp.id, 1
                                    FROM module_permissions mp
                                    WHERE mp.module_id = '$new_id' AND mp.permission_id = '$perm_id'");
                            }
                        }
                    }
                }
            }

            echo json_encode(['success' => true, 'message' => 'Modulo guardado correctamente'], JSON_OUT);
            break;

        case 'delete_module':
            $id = trim($_POST['id'] ?? '');
            if (!$id) {
                throw new Exception('ID de modulo requerido');
            }

            $stmt = $conn->prepare("DELETE FROM profile_permissions WHERE module_permission_id IN (SELECT id FROM module_permissions WHERE module_id = ?)");
            $stmt->bind_param('s', $id);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("DELETE FROM module_permissions WHERE module_id = ?");
            $stmt->bind_param('s', $id);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("DELETE FROM modules WHERE id = ?");
            $stmt->bind_param('s', $id);
            $stmt->execute();
            $affected = $stmt->affected_rows;
            $stmt->close();

            if ($affected === 0) {
                throw new Exception('Modulo no encontrado');
            }

            echo json_encode(['success' => true, 'message' => 'Modulo eliminado correctamente'], JSON_OUT);
            break;

        case 'toggle_module_perm':
            $module_id = trim($_POST['module_id'] ?? '');
            $permission_id = trim($_POST['permission_id'] ?? '');
            $enabled = (int)($_POST['enabled'] ?? 0);

            if (!$module_id || !$permission_id) {
                throw new Exception('Parametros incompletos');
            }

            if ($enabled) {
                $stmt = $conn->prepare("INSERT IGNORE INTO module_permissions (module_id, permission_id) VALUES (?, ?)");
                $stmt->bind_param('ss', $module_id, $permission_id);
                $stmt->execute();
                $stmt->close();

                $profiles_result = $conn->query("SELECT id FROM profiles WHERE active = 1");
                while ($prof = $profiles_result->fetch_assoc()) {
                    $conn->query("INSERT IGNORE INTO profile_permissions (profile_id, module_permission_id, can_access)
                        SELECT '{$prof['id']}', mp.id, 0
                        FROM module_permissions mp
                        WHERE mp.module_id = '$module_id' AND mp.permission_id = '$permission_id'");
                }
            } else {
                $stmt = $conn->prepare("
                    DELETE FROM profile_permissions
                    WHERE module_permission_id IN (
                        SELECT id FROM module_permissions WHERE module_id = ? AND permission_id = ?
                    )
                ");
                $stmt->bind_param('ss', $module_id, $permission_id);
                $stmt->execute();
                $stmt->close();

                $stmt = $conn->prepare("DELETE FROM module_permissions WHERE module_id = ? AND permission_id = ?");
                $stmt->bind_param('ss', $module_id, $permission_id);
                $stmt->execute();
                $stmt->close();
            }

            echo json_encode(['success' => true], JSON_OUT);
            break;

        case 'toggle_grant':
            $profile_id = trim($_POST['profile_id'] ?? '');
            $module_slug = trim($_POST['module_slug'] ?? '');
            $permission_slug = trim($_POST['permission_slug'] ?? '');
            $can_access = (int)($_POST['can_access'] ?? 0);

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
            $result = $stmt->get_result();
            $mp = $result->fetch_assoc();
            $stmt->close();

            if (!$mp) {
                throw new Exception('Combinacion modulo/permiso no encontrada');
            }

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
    error_log("Error en manage_modules.php: " . $e->getMessage());
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()], JSON_OUT);
}
