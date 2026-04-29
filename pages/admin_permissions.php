<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Permisos - SameBit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .permissions-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .profile-card {
            border-left: 4px solid #667eea;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
        }
        .permission-row {
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .permission-row:last-child {
            border-bottom: none;
        }
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #28a745;
        }
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        .badge-admin {
            background-color: #dc3545;
        }
        .badge-operator {
            background-color: #ffc107;
            color: #333;
        }
        .badge-viewer {
            background-color: #17a2b8;
        }
        .module-section {
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }
        .module-header {
            background-color: #667eea;
            color: white;
            padding: 15px;
            font-weight: bold;
        }
        .module-content {
            padding: 20px;
        }
        h1 {
            color: white;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

<?php
require_once 'config/setup.php';
require_once 'config/PermissionManager.php';

// Verificar que sea admin
if (empty($_SESSION['user_id'])) {
    header('Location: pages/login.php');
    exit;
}

$pm = new PermissionManager($pdo, $_SESSION['user_id']);
if (!$pm->isAdmin()) {
    http_response_code(403);
    die('<div class="alert alert-danger m-5">❌ Acceso denegado. Solo administradores pueden acceder a esta sección.</div>');
}

// Obtener todos los perfiles, módulos y permisos
$profiles = $pdo->query("SELECT * FROM profiles WHERE active = 1 ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$modules = $pdo->query("SELECT * FROM modules WHERE active = 1 ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$permissions = $pdo->query("SELECT * FROM permissions ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Obtener matriz de permisos
$stmt = $pdo->prepare("
    SELECT pp.id, pp.profile_id, pp.module_permission_id, pp.can_access,
           m.slug as module_slug, p.slug as permission_slug
    FROM profile_permissions pp
    INNER JOIN module_permissions mp ON pp.module_permission_id = mp.id
    INNER JOIN modules m ON mp.module_id = m.id
    INNER JOIN permissions p ON mp.permission_id = p.id
");
$stmt->execute();
$permissions_matrix = [];
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $key = $row['profile_id'] . '|' . $row['module_slug'] . '|' . $row['permission_slug'];
    $permissions_matrix[$key] = $row;
}
?>

<div class="permissions-container">
    <h1>🔐 Gestión de Permisos y Privilegios</h1>

    <div class="alert alert-info">
        <strong>ℹ️ Información:</strong>
        Aquí puedes gestionar qué acciones puede realizar cada perfil en cada módulo.
        Los cambios se aplican inmediatamente.
    </div>

    <?php foreach ($profiles as $profile): ?>
    <div class="profile-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-1"><?php echo htmlspecialchars($profile['name']); ?></h5>
                <p class="text-muted mb-0"><?php echo htmlspecialchars($profile['description']); ?></p>
            </div>
            <span class="badge 
                <?php 
                    if ($profile['slug'] === 'admin') echo 'badge-admin';
                    elseif ($profile['slug'] === 'operador') echo 'badge-operator';
                    else echo 'badge-viewer';
                ?>">
                <?php echo strtoupper($profile['slug']); ?>
            </span>
        </div>

        <?php foreach ($modules as $module): ?>
        <div class="module-section mt-3">
            <div class="module-header">
                📦 <?php echo htmlspecialchars($module['name']); ?>
            </div>
            <div class="module-content">
                <div class="row">
                    <?php foreach ($permissions as $permission): ?>
                    <?php
                        $key = $profile['id'] . '|' . $module['slug'] . '|' . $permission['slug'];
                        $current = $permissions_matrix[$key] ?? null;
                        $can_access = $current ? $current['can_access'] : 0;
                        $pp_id = $current['id'] ?? null;
                    ?>
                    <div class="col-md-6 permission-row">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?php echo htmlspecialchars($permission['name']); ?></strong>
                                <br>
                                <small class="text-muted"><?php echo htmlspecialchars($permission['slug']); ?></small>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" 
                                       data-pp-id="<?php echo htmlspecialchars($pp_id); ?>"
                                       data-profile-id="<?php echo htmlspecialchars($profile['id']); ?>"
                                       data-module-slug="<?php echo htmlspecialchars($module['slug']); ?>"
                                       data-permission-slug="<?php echo htmlspecialchars($permission['slug']); ?>"
                                       <?php echo $can_access ? 'checked' : ''; ?>
                                       onchange="togglePermission(this)">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
function togglePermission(checkbox) {
    const ppId = $(checkbox).data('pp-id');
    const profileId = $(checkbox).data('profile-id');
    const moduleSlug = $(checkbox).data('module-slug');
    const permissionSlug = $(checkbox).data('permission-slug');
    const canAccess = $(checkbox).is(':checked') ? 1 : 0;

    $.ajax({
        url: 'config/update_permission.php',
        method: 'POST',
        dataType: 'json',
        data: {
            profile_id: profileId,
            module_slug: moduleSlug,
            permission_slug: permissionSlug,
            can_access: canAccess
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: '✅ Actualizado',
                    text: 'Permiso actualizado exitosamente',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Error al actualizar permiso'
                });
                $(checkbox).prop('checked', !canAccess);
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión'
            });
            $(checkbox).prop('checked', !canAccess);
        }
    });
}
</script>

</body>
</html>
