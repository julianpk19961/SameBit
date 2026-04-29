<?php
include './generales/header.php';
include './generales/nav.php';

// Verificar que sea admin
if ($_SESSION['privilege'] !== 'admin' && $profileSlug !== 'admin') {
    http_response_code(403);
    echo '<div class="alert alert-danger m-5">❌ ' . __('access_denied') . '</div>';
    exit;
}

// Obtener usuarios
$query = "
    SELECT u.id, u.username, u.first_name, u.last_name, u.active, p.name as profile_name, p.slug as profile_slug
    FROM users u
    INNER JOIN profiles p ON u.profile_id = p.id
    ORDER BY u.first_name, u.last_name
";
$result = $conn->query($query);
$users = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Obtener perfiles
$profilesQuery = "SELECT id, name, slug FROM profiles WHERE active = 1 ORDER BY name";
$profilesResult = $conn->query($profilesQuery);
$profiles = [];
if ($profilesResult) {
    while ($row = $profilesResult->fetch_assoc()) {
        $profiles[] = $row;
    }
}
?>

<style>
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

<div class="container-fluid mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">👥 <?php echo __('users_management') ?? 'Gestión de Usuarios'; ?></h2>
        <button class="btn btn-primary" onclick="openCreateUserModal()">
            <i class="bi bi-plus-circle"></i> <?php echo __('new_user') ?? 'Nuevo Usuario'; ?>
        </button>
    </div>

    <div class="alert alert-info">
        <strong>ℹ️ <?php echo __('information') ?? 'Información'; ?>:</strong>
        <?php echo __('user_management_desc') ?? 'Aquí puedes crear, editar y gestionar usuarios con sus perfiles y privilegios.'; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-hover" id="usersTable">
            <thead class="table-light">
                <tr>
                    <th><?php echo __('username') ?? 'Usuario'; ?></th>
                    <th><?php echo __('name') ?? 'Nombre'; ?></th>
                    <th><?php echo __('profile') ?? 'Perfil'; ?></th>
                    <th><?php echo __('status') ?? 'Estado'; ?></th>
                    <th><?php echo __('actions') ?? 'Acciones'; ?></th>
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
                            <?php echo $user['active'] ? '✅ ' . __('active') : '❌ ' . __('inactive'); ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="openEditUserModal('<?php echo htmlspecialchars($user['id']); ?>', '<?php echo htmlspecialchars($user['username']); ?>', '<?php echo htmlspecialchars($user['profile_name']); ?>', <?php echo $user['active']; ?>)">
                            ✏️ <?php echo __('edit') ?? 'Editar'; ?>
                        </button>
                        <button class="btn btn-sm btn-info" onclick="viewUserPermissions('<?php echo htmlspecialchars($user['id']); ?>')">
                            🔍 <?php echo __('view_permissions') ?? 'Ver Permisos'; ?>
                        </button>
                        <?php if ($user['id'] !== $_SESSION['id']): ?>
                        <button class="btn btn-sm btn-danger" onclick="deleteUser('<?php echo htmlspecialchars($user['id']); ?>', '<?php echo htmlspecialchars($user['username']); ?>')">
                            🗑️ <?php echo __('delete') ?? 'Eliminar'; ?>
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
                <h5 class="modal-title" id="userModalTitle"><?php echo __('new_user') ?? 'Nuevo Usuario'; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <input type="hidden" id="userId" name="user_id" value="">

                    <div class="mb-3">
                        <label for="username" class="form-label"><?php echo __('username') ?? 'Usuario'; ?></label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>

                    <div class="mb-3" id="passwordDiv">
                        <label for="password" class="form-label"><?php echo __('password') ?? 'Contraseña'; ?></label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="<?php echo __('leave_blank_to_keep') ?? 'Dejar en blanco para no cambiar'; ?>">
                        <small class="text-muted"><?php echo __('min_6_chars') ?? 'Mínimo 6 caracteres'; ?></small>
                    </div>

                    <div class="mb-3">
                        <label for="firstName" class="form-label"><?php echo __('first_name') ?? 'Nombre'; ?></label>
                        <input type="text" class="form-control" id="firstName" name="first_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="lastName" class="form-label"><?php echo __('last_name') ?? 'Apellido'; ?></label>
                        <input type="text" class="form-control" id="lastName" name="last_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="profileId" class="form-label"><?php echo __('profile') ?? 'Perfil'; ?></label>
                        <select class="form-select" id="profileId" name="profile_id" required>
                            <option value=""><?php echo __('select_profile') ?? 'Selecciona un perfil...'; ?></option>
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
                                ✅ <?php echo __('active_user') ?? 'Usuario Activo'; ?>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo __('cancel') ?? 'Cancelar'; ?></button>
                <button type="button" class="btn btn-primary" onclick="saveUser()"><?php echo __('save') ?? 'Guardar'; ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Ver Permisos del Usuario -->
<div class="modal fade" id="permissionsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permissionsModalTitle"><?php echo __('user_permissions') ?? 'Permisos del Usuario'; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="permissionsContent">
                <!-- Se llena dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo __('close') ?? 'Cerrar'; ?></button>
            </div>
        </div>
    </div>
</div>

<script src="/Js/jquery/jquery-3.6.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
function openCreateUserModal() {
    $('#userId').val('');
    $('#userForm')[0].reset();
    $('#userModalTitle').text('<?php echo __('new_user') ?? 'Nuevo Usuario'; ?>');
    $('#passwordDiv label').text('<?php echo __('password') ?? 'Contraseña'; ?> *');
    $('#password').attr('required', true);
    $('#active').prop('checked', true);
    new bootstrap.Modal(document.getElementById('userModal')).show();
}

function openEditUserModal(userId, username, profileName, isActive) {
    $.ajax({
        url: '../config/get_user.php',
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

                $('#userModalTitle').text('<?php echo __('edit_user') ?? 'Editar Usuario'; ?>');
                $('#passwordDiv label').text('<?php echo __('password') ?? 'Contraseña'; ?> (<?php echo __('leave_blank_to_keep') ?? 'dejar en blanco para no cambiar'; ?>)');
                $('#password').attr('required', false);
                $('#password').val('');

                new bootstrap.Modal(document.getElementById('userModal')).show();
            }
        },
        error: function() {
            Swal.fire('Error', '<?php echo __('error_fetching_user') ?? 'No se pudo obtener los datos del usuario'; ?>', 'error');
        }
    });
}

function saveUser() {
    const formData = new FormData($('#userForm')[0]);
    formData.append('active', $('#active').is(':checked') ? 1 : 0);

    $.ajax({
        url: '../config/save_user.php',
        method: 'POST',
        dataType: 'json',
        data: Object.fromEntries(formData),
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: '<?php echo __('success') ?? 'Éxito'; ?>',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', response.message || '<?php echo __('error_saving') ?? 'Error al guardar'; ?>', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', '<?php echo __('connection_error') ?? 'Error de conexión'; ?>', 'error');
        }
    });
}

function viewUserPermissions(userId) {
    $.ajax({
        url: '../config/get_user_permissions.php',
        method: 'POST',
        dataType: 'json',
        data: { user_id: userId },
        success: function(response) {
            if (response.success) {
                let html = `<div class="alert alert-info"><?php echo __('profile'); ?>: <strong>${response.profile.name}</strong></div>`;
                html += '<div class="table-responsive"><table class="table table-sm">';

                for (const [module, perms] of Object.entries(response.permissions)) {
                    html += `<tr><td colspan="2"><strong>${module}</strong></td></tr>`;
                    for (const [perm, hasAccess] of Object.entries(perms)) {
                        html += `<tr><td style="padding-left: 30px;">${perm}</td><td>${hasAccess ? '✅' : '❌'}</td></tr>`;
                    }
                }

                html += '</table></div>';
                $('#permissionsContent').html(html);
                $('#permissionsModalTitle').text('<?php echo __('user_permissions'); ?> - ' + response.profile.name);
                new bootstrap.Modal(document.getElementById('permissionsModal')).show();
            }
        },
        error: function() {
            Swal.fire('Error', '<?php echo __('error_fetching_permissions') ?? 'No se pudieron obtener los permisos'; ?>', 'error');
        }
    });
}

function deleteUser(userId, username) {
    Swal.fire({
        title: '<?php echo __('confirm') ?? 'Confirmar'; ?>',
        text: `¿Estás seguro de eliminar el usuario ${username}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<?php echo __('yes_delete') ?? 'Sí, eliminar'; ?>',
        cancelButtonText: '<?php echo __('cancel') ?? 'Cancelar'; ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../config/delete_user.php',
                method: 'POST',
                dataType: 'json',
                data: { user_id: userId },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '<?php echo __('deleted') ?? 'Eliminado'; ?>',
                            text: '<?php echo __('user_deleted_success') ?? 'Usuario eliminado exitosamente'; ?>',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.message || '<?php echo __('error_deleting') ?? 'Error al eliminar'; ?>', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', '<?php echo __('connection_error') ?? 'Error de conexión'; ?>', 'error');
                }
            });
        }
    });
}
</script>
