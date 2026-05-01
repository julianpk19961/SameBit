<?php
include './generales/header.php';
include './generales/nav.php';

if ($profileSlug !== 'admin') {
    http_response_code(403);
    echo '<div class="alert alert-danger m-5"><i class="bi bi-shield-x me-2"></i>' . __('access_denied') . '</div>';
    exit;
}

$users = [];
$result = $conn->query("
    SELECT u.id, u.username, u.first_name, u.last_name, u.active,
           p.name AS profile_name, p.slug AS profile_slug
    FROM users u
    INNER JOIN profiles p ON u.profile_id = p.id
    ORDER BY u.first_name, u.last_name
");
if ($result) {
    while ($row = $result->fetch_assoc()) $users[] = $row;
}

$profiles = [];
$result = $conn->query("SELECT id, name, slug FROM profiles WHERE active = 1 ORDER BY name");
if ($result) {
    while ($row = $result->fetch_assoc()) $profiles[] = $row;
}
?>
<link rel="stylesheet" href="../css/datatables/dataTables.bootstrap5.min.css">

<div class="container-fluid px-4">

  <!-- Toolbar -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h5 class="fw-bold mb-0">
        <i class="bi bi-people me-2 text-primary"></i><?php echo __('users_management'); ?>
      </h5>
    </div>
    <button class="btn btn-primary" onclick="usersOpenCreate()">
      <i class="bi bi-person-plus"></i> <?php echo __('new_user'); ?>
    </button>
  </div>

  <!-- Table -->
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <table class="table table-hover table-bordered mb-0 w-100" id="users-table">
        <thead class="table-light">
          <tr>
            <th><?php echo __('username'); ?></th>
            <th><?php echo __('name'); ?></th>
            <th><?php echo __('profile'); ?></th>
            <th><?php echo __('status'); ?></th>
            <th class="text-center" style="width:120px"><?php echo __('actions'); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
          <?php
            $badgeCls = match($u['profile_slug']) {
                'admin'    => 'bg-danger',
                'operador' => 'bg-warning text-dark',
                default    => 'bg-info text-dark'
            };
          ?>
          <tr>
            <td><code>@<?php echo htmlspecialchars($u['username']); ?></code></td>
            <td><?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?></td>
            <td>
              <span class="badge <?php echo $badgeCls; ?>">
                <?php echo htmlspecialchars($u['profile_name']); ?>
              </span>
            </td>
            <td>
              <span class="badge <?php echo $u['active'] ? 'bg-success' : 'bg-secondary'; ?>">
                <?php echo $u['active'] ? __('active') : __('inactive'); ?>
              </span>
            </td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-primary me-1"
                      onclick="usersOpenEdit('<?php echo htmlspecialchars($u['id']); ?>')"
                      title="<?php echo __('edit'); ?>">
                <i class="bi bi-pencil"></i>
              </button>
              <button class="btn btn-sm btn-outline-info me-1"
                      onclick="usersViewPermissions('<?php echo htmlspecialchars($u['id']); ?>')"
                      title="<?php echo __('view_permissions'); ?>">
                <i class="bi bi-shield-lock"></i>
              </button>
              <?php if ($u['id'] !== $_SESSION['id']): ?>
              <button class="btn btn-sm btn-outline-danger"
                      onclick="usersDelete('<?php echo htmlspecialchars($u['id']); ?>', '<?php echo htmlspecialchars($u['username']); ?>')"
                      title="<?php echo __('delete'); ?>">
                <i class="bi bi-trash"></i>
              </button>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<!-- ====================================================
     OFFCANVAS — CREATE / EDIT USER
     ==================================================== -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="users-panel" style="width: min(480px, 100vw);">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title fw-bold" id="users-panel-title">
      <i class="bi bi-person-plus me-2 text-primary"></i><?php echo __('new_user'); ?>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <form id="users-form" novalidate>
      <input type="hidden" id="users-id">

      <!-- Error banner -->
      <div id="users-error" class="alert alert-danger d-none mb-3" role="alert">
        <div class="d-flex align-items-start gap-2">
          <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
          <span class="sb-error-text flex-grow-1"></span>
          <button type="button" class="btn-close" onclick="SB.form.clear('users-error')"></button>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label"><?php echo __('username'); ?> <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="users-username" autocomplete="off">
      </div>

      <div class="mb-3">
        <label class="form-label" id="users-pass-label">
          <?php echo __('password'); ?> <span class="text-danger">*</span>
        </label>
        <input type="password" class="form-control" id="users-password" autocomplete="new-password">
        <small class="text-muted" id="users-pass-hint"><?php echo __('min_6_chars'); ?></small>
      </div>

      <div class="row g-2 mb-3">
        <div class="col">
          <label class="form-label"><?php echo __('first_name'); ?> <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="users-first-name">
        </div>
        <div class="col">
          <label class="form-label"><?php echo __('last_name'); ?> <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="users-last-name">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label"><?php echo __('profile'); ?> <span class="text-danger">*</span></label>
        <select class="form-select" id="users-profile-id">
          <option value=""><?php echo __('select_profile'); ?></option>
          <?php foreach ($profiles as $p): ?>
          <option value="<?php echo htmlspecialchars($p['id']); ?>">
            <?php echo htmlspecialchars($p['name']); ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="users-active" checked>
          <label class="form-check-label" for="users-active"><?php echo __('active_user'); ?></label>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2 pt-3 border-top">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">
          <?php echo __('cancel'); ?>
        </button>
        <button type="submit" class="btn btn-primary px-4">
          <?php echo __('save'); ?>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ====================================================
     MODAL — VIEW USER PERMISSIONS (read-only)
     ==================================================== -->
<div class="modal fade" id="users-perms-modal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="users-perms-title">
          <i class="bi bi-shield-lock me-2"></i><?php echo __('user_permissions'); ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="users-perms-body">
        <div class="text-center py-4"><div class="spinner-border text-primary"></div></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <?php echo __('close'); ?>
        </button>
      </div>
    </div>
  </div>
</div>

<script src="../Js/datatables/jquery.dataTables.min.js"></script>
<script src="../Js/datatables/dataTables.bootstrap5.min.js"></script>
<script src="../Js/admin_users.js"></script>
<script>ModulePermissions.init();</script>
<?php include './generales/footer.php'; ?>
