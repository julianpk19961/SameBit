<?php
include './generales/header.php';
include './generales/nav.php';

if ($profileSlug !== 'admin') {
    http_response_code(403);
    echo '<div class="alert alert-danger m-5"><i class="bi bi-shield-x me-2"></i>' . __('access_denied') . '</div>';
    exit;
}

$profiles    = [];
$r = $conn->query("SELECT * FROM profiles WHERE active = 1 ORDER BY name");
if ($r) while ($row = $r->fetch_assoc()) $profiles[] = $row;

$modules = [];
$r = $conn->query("SELECT * FROM modules WHERE active = 1 ORDER BY name");
if ($r) while ($row = $r->fetch_assoc()) $modules[] = $row;

$permissions = [];
$r = $conn->query("SELECT * FROM permissions ORDER BY name");
if ($r) while ($row = $r->fetch_assoc()) $permissions[] = $row;

$perm_matrix = [];
$r = $conn->query("
    SELECT pp.id, pp.profile_id, pp.can_access,
           m.slug as module_slug, p.slug as permission_slug
    FROM profile_permissions pp
    INNER JOIN module_permissions mp ON pp.module_permission_id = mp.id
    INNER JOIN modules m ON mp.module_id = m.id
    INNER JOIN permissions p ON mp.permission_id = p.id
");
if ($r) {
    while ($row = $r->fetch_assoc()) {
        $key = $row['profile_id'] . '|' . $row['module_slug'] . '|' . $row['permission_slug'];
        $perm_matrix[$key] = $row;
    }
}
?>

<style>
.module-header { background-color: var(--bs-primary); color: #fff; padding: 12px 16px; font-weight: 600; border-radius: 6px 6px 0 0; }
.module-card   { border: 1px solid var(--bs-border-color); border-radius: 8px; margin-bottom: 1.5rem; overflow: hidden; }
.toggle-switch { position: relative; display: inline-block; width: 46px; height: 22px; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; inset: 0; background: #ccc; transition: .3s; border-radius: 22px; }
.slider:before { content: ""; position: absolute; width: 16px; height: 16px; left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: .3s; }
input:checked + .slider { background: #28a745; }
input:checked + .slider:before { transform: translateX(24px); }
</style>

<div class="container-fluid mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary fw-bold">
      <i class="bi bi-shield-lock"></i> <?php echo __('permissions_matrix') ?? 'Matriz de Permisos'; ?>
    </h2>
    <a href="./admin_profiles.php" class="btn btn-outline-primary">
      <i class="bi bi-person-badge"></i> Gestionar Perfiles
    </a>
  </div>

  <div class="alert alert-info">
    <strong><i class="bi bi-info-circle"></i></strong>
    Vista completa de permisos por perfil. Para crear o editar perfiles ve a
    <a href="./admin_profiles.php" class="alert-link">Gestión de Perfiles</a>.
  </div>

  <?php foreach ($profiles as $profile): ?>
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <div>
        <strong><?php echo htmlspecialchars($profile['name']); ?></strong>
        <code class="ms-2 text-white-50"><?php echo htmlspecialchars($profile['slug']); ?></code>
      </div>
      <span class="badge bg-light text-dark"><?php echo htmlspecialchars($profile['description'] ?? ''); ?></span>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm table-bordered mb-0">
          <thead class="table-light">
            <tr>
              <th>Módulo</th>
              <?php foreach ($permissions as $perm): ?>
              <th class="text-center"><?php echo htmlspecialchars($perm['name']); ?></th>
              <?php endforeach; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($modules as $module): ?>
            <tr>
              <td><strong><?php echo htmlspecialchars($module['name']); ?></strong><br>
                  <small class="text-muted"><code><?php echo htmlspecialchars($module['slug']); ?></code></small></td>
              <?php foreach ($permissions as $perm): ?>
              <?php
                $key     = $profile['id'] . '|' . $module['slug'] . '|' . $perm['slug'];
                $entry   = $perm_matrix[$key] ?? null;
                $checked = $entry ? $entry['can_access'] : 0;
                $pp_id   = $entry['id'] ?? null;
              ?>
              <td class="text-center align-middle">
                <?php if (!$entry): ?>
                  <span class="text-muted">—</span>
                <?php else: ?>
                <label class="toggle-switch">
                  <input type="checkbox"
                    data-profile-id="<?php echo htmlspecialchars($profile['id']); ?>"
                    data-module-slug="<?php echo htmlspecialchars($module['slug']); ?>"
                    data-permission-slug="<?php echo htmlspecialchars($perm['slug']); ?>"
                    <?php echo $checked ? 'checked' : ''; ?>
                    onchange="togglePermission(this)">
                  <span class="slider"></span>
                </label>
                <?php endif; ?>
              </td>
              <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<script src="../Js/admin_permissions.js"></script>
<script>ModulePermissions.init();</script>
<?php include './generales/footer.php'; ?>
