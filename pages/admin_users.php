<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - SameBit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container-main {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        h1 {
            color: #667eea;
            margin-bottom: 30px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        .btn-new-user {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        .btn-new-user:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
        }
        .status-active {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.85rem;
        }
        .status-inactive {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.85rem;
        }
        .badge-profile {
            padding: 8px 12px;
            border-radius: 5px;
            font-weight: bold;
        }
        .badge-admin {
            background-color: #dc3545;
            color: white;
        }
        .badge-operator {
            background-color: #ffc107;
            color: #333;
        }
        .badge-viewer {
            background-color: #17a2b8;
            color: white;
        }
        table tbody tr {
            transition: background-color 0.2s;
        }
        table tbody tr:hover {
            background-color: #f5f5f5;
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

// Obtener usuarios
$stmt = $pdo->query("
    SELECT u.id, u.username, u.first_name, u.last_name, u.active, p.name as profile_name, p.slug as profile_slug
    FROM users u
    INNER JOIN profiles p ON u.profile_id = p.id
    ORDER BY u.first_name, u.last_name
");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener perfiles
$profiles = $pdo->query("SELECT id, name, slug FROM profiles WHERE active = 1 ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>👥 Gestión de Usuarios</h1>
        <button class="btn btn-new-user" onclick="openCreateUserModal()">
            <i class="bi bi-plus-circle"></i> Nuevo Usuario
        </button>
    </div>

    <div class="alert alert-info">
        <strong>ℹ️ Información:</strong>
        Aquí puedes crear, editar y gestionar usuarios con sus perfiles y privilegios.
    </div>

    <div class="table-responsive">
        <table class="table table-hover" id="usersTable">
            <thead class="table-light">
                <tr>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Perfil</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><strong>@<?php echo htmlspecialchars($user['username']); ?></strong></td>
                    <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                    <td>
                        <span class="badge-profile 
                            <?php 
                                if ($user['profile_slug'] === 'admin') echo 'badge-admin';
                                elseif ($user['profile_slug'] === 'operador') echo 'badge-operator';
                                else echo 'badge-viewer';
                            ?>">
                            <?php echo htmlspecialchars($user['profile_name']); ?>
                        </span>
                    </td>
                    <td>
                        <span class="<?php echo $user['active'] ? 'status-active' : 'status-inactive'; ?>">
                            <?php echo $user['active'] ? '✅ Activo' : '❌ Inactivo'; ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="openEditUserModal('<?php echo htmlspecialchars($user['id']); ?>', '<?php echo htmlspecialchars($user['username']); ?>', '<?php echo htmlspecialchars($user['profile_name']); ?>', <?php echo $user['active']; ?>)">
                            ✏️ Editar
                        </button>
                        <button class="btn btn-sm btn-info" onclick="viewUserPermissions('<?php echo htmlspecialchars($user['id']); ?>')">
                            🔍 Ver Permisos
                        </button>
                        <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                        <button class="btn btn-sm btn-danger" onclick="deleteUser('<?php echo htmlspecialchars($user['id']); ?>', '<?php echo htmlspecialchars($user['username']); ?>')">
                            🗑️ Eliminar
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Crear/Editar Usuario -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalTitle">Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <input type="hidden" id="userId" name="user_id" value="">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario (email)</label>
                        <input type="email" class="form-control" id="username" name="username" required>
                    </div>

                    <div class="mb-3" id="passwordDiv">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Dejar en blanco para no cambiar">
                        <small class="text-muted">Mínimo 6 caracteres</small>
                    </div>

                    <div class="mb-3">
                        <label for="firstName" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="firstName" name="first_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="lastName" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="lastName" name="last_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="profileId" class="form-label">Perfil</label>
                        <select class="form-select" id="profileId" name="profile_id" required>
                            <option value="">Selecciona un perfil...</option>
                            <?php foreach ($profiles as $profile): ?>
                            <option value="<?php echo htmlspecialchars($profile['id']); ?>">
                                <?php echo htmlspecialchars($profile['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="active" name="active" value="1" checked>
                            <label class="form-check-label" for="active">
                                ✅ Usuario Activo
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="saveUser()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Ver Permisos del Usuario -->
<div class="modal fade" id="permissionsModal" tabindex="-1" size="lg">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permissionsModalTitle">Permisos del Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="permissionsContent">
                <!-- Se llena dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net@1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
// Inicializar DataTable
$(document).ready(function() {
    $('#usersTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es_es.json'
        },
        pageLength: 10,
        order: [[1, 'asc']]
    });
});

function openCreateUserModal() {
    $('#userId').val('');
    $('#userForm')[0].reset();
    $('#userModalTitle').text('Nuevo Usuario');
    $('#passwordDiv label').text('Contraseña *');
    $('#password').attr('required', true);
    $('#active').prop('checked', true);
    new bootstrap.Modal(document.getElementById('userModal')).show();
}

function openEditUserModal(userId, username, profileName, isActive) {
    // Obtener datos del usuario
    $.ajax({
        url: 'config/get_user.php',
        method: 'POST',
        dataType: 'json',
        data: { user_id: userId },
        success: function(response) {
            if (response.success) {
                const user = response.data;
                $('#userId').val(user.id);
                $('#username').val(user.username);
                $('#firstName').val(user.first_name);
                $('#lastName').val(user.last_name);
                $('#profileId').val(user.profile_id);
                $('#active').prop('checked', user.active == 1);
                
                $('#userModalTitle').text('Editar Usuario');
                $('#passwordDiv label').text('Contraseña (dejar en blanco para no cambiar)');
                $('#password').attr('required', false);
                $('#password').val('');
                
                new bootstrap.Modal(document.getElementById('userModal')).show();
            }
        },
        error: function() {
            Swal.fire('Error', 'No se pudo obtener los datos del usuario', 'error');
        }
    });
}

function saveUser() {
    const formData = new FormData($('#userForm')[0]);
    formData.append('active', $('#active').is(':checked') ? 1 : 0);

    $.ajax({
        url: 'config/save_user.php',
        method: 'POST',
        dataType: 'json',
        data: Object.fromEntries(formData),
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', response.message || 'Error al guardar', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Error de conexión', 'error');
        }
    });
}

function viewUserPermissions(userId) {
    $.ajax({
        url: 'config/get_user_permissions.php',
        method: 'POST',
        dataType: 'json',
        data: { user_id: userId },
        success: function(response) {
            if (response.success) {
                let html = `<div class="alert alert-info">Perfil: <strong>${response.profile.name}</strong></div>`;
                html += '<div class="table-responsive"><table class="table table-sm">';
                
                for (const [module, perms] of Object.entries(response.permissions)) {
                    html += `<tr><td colspan="2"><strong>${module}</strong></td></tr>`;
                    for (const [perm, hasAccess] of Object.entries(perms)) {
                        html += `<tr><td style="padding-left: 30px;">${perm}</td><td>${hasAccess ? '✅' : '❌'}</td></tr>`;
                    }
                }
                
                html += '</table></div>';
                $('#permissionsContent').html(html);
                $('#permissionsModalTitle').text('Permisos - ' + response.profile.name);
                new bootstrap.Modal(document.getElementById('permissionsModal')).show();
            }
        },
        error: function() {
            Swal.fire('Error', 'No se pudieron obtener los permisos', 'error');
        }
    });
}

function deleteUser(userId, username) {
    Swal.fire({
        title: 'Confirmar',
        text: `¿Estás seguro de eliminar el usuario ${username}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'config/delete_user.php',
                method: 'POST',
                dataType: 'json',
                data: { user_id: userId },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: 'Usuario eliminado exitosamente',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.message || 'Error al eliminar', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Error de conexión', 'error');
                }
            });
        }
    });
}
</script>

</body>
</html>
