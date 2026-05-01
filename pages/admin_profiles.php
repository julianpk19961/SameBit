<?php
include './generales/header.php';
include './generales/nav.php';

if ($profileSlug !== 'admin') {
    http_response_code(403);
    echo '<div class="alert alert-danger m-5"><i class="bi bi-shield-x me-2"></i>' . __('access_denied') . '</div>';
    exit;
}
?>
<link rel="stylesheet" href="../css/datatables/dataTables.bootstrap5.min.css">

<div class="container-fluid px-4">

  <!-- Toolbar -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h5 class="fw-bold mb-0">
        <i class="bi bi-person-badge me-2 text-primary"></i><?php echo __('profiles_management'); ?>
      </h5>
    </div>
    <button class="btn btn-primary" onclick="profilesOpenCreate()">
      <i class="bi bi-plus-circle"></i> <?php echo __('new_profile'); ?>
    </button>
  </div>

  <!-- Table -->
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <table class="table table-hover table-bordered mb-0 w-100" id="profiles-table">
        <thead class="table-light">
          <tr>
            <th><?php echo __('name'); ?></th>
            <th>Slug</th>
            <th><?php echo __('description'); ?></th>
            <th class="text-center"><?php echo __('users'); ?></th>
            <th class="text-center"><?php echo __('status'); ?></th>
            <th class="text-center" style="width:140px"><?php echo __('actions'); ?></th>
          </tr>
        </thead>
        <tbody id="profiles-body"></tbody>
      </table>
    </div>
  </div>

</div>

<!-- ====================================================
     OFFCANVAS — CREATE / EDIT PROFILE
     ==================================================== -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="profiles-panel" style="width: min(480px, 100vw);">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title fw-bold" id="profiles-panel-title">
      <i class="bi bi-plus-circle me-2 text-primary"></i><?php echo __('new_profile'); ?>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <form id="profiles-form" novalidate>
      <input type="hidden" id="prof-id">

      <!-- Error banner -->
      <div id="profiles-error" class="alert alert-danger d-none mb-3" role="alert">
        <div class="d-flex align-items-start gap-2">
          <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
          <span class="sb-error-text flex-grow-1"></span>
          <button type="button" class="btn-close" onclick="SB.form.clear('profiles-error')"></button>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label"><?php echo __('name'); ?> <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="prof-name">
      </div>

      <div class="mb-3">
        <label class="form-label">Slug <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="prof-slug" placeholder="ej: supervisor">
        <small class="text-muted">a-z, 0-9, _ — no se puede cambiar después de crear.</small>
      </div>

      <div class="mb-3">
        <label class="form-label"><?php echo __('description'); ?></label>
        <textarea class="form-control" id="prof-description" rows="2"></textarea>
      </div>

      <div class="mb-3">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="prof-active" checked>
          <label class="form-check-label" for="prof-active"><?php echo __('active'); ?></label>
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
     MODAL — PERMISSIONS MATRIX (wide layout)
     ==================================================== -->
<div class="modal fade" id="profiles-perms-modal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="profiles-perms-title">
          <i class="bi bi-shield-lock me-2"></i>Permisos del Perfil
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="profiles-perms-body">
        <div class="text-center py-4"><div class="spinner-border text-primary"></div></div>
      </div>
      <div class="modal-footer">
        <div class="alert alert-secondary mb-0 small flex-grow-1">
          <i class="bi bi-info-circle me-1"></i>
          <strong>Ingresar</strong>: ver el módulo &nbsp;|&nbsp;
          <strong>Guardar</strong>: crear registros &nbsp;|&nbsp;
          <strong>Editar</strong>: modificar registros &nbsp;|&nbsp;
          <strong>Informes</strong>: exportar
        </div>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <?php echo __('close'); ?>
        </button>
      </div>
    </div>
  </div>
</div>

<script src="../Js/datatables/jquery.dataTables.min.js"></script>
<script src="../Js/datatables/dataTables.bootstrap5.min.js"></script>
<script src="../Js/admin_profiles.js"></script>
<script>ModulePermissions.init();</script>
<?php include './generales/footer.php'; ?>
