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
        <i class="bi bi-grid-3x3-gap me-2 text-primary"></i><?php echo __('modules_management'); ?>
      </h5>
    </div>
    <button class="btn btn-primary" onclick="modulesOpenCreate()">
      <i class="bi bi-plus-circle"></i> <?php echo __('new_module'); ?>
    </button>
  </div>

  <!-- Table -->
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <table class="table table-hover table-bordered mb-0 w-100" id="modules-table">
        <thead class="table-light">
          <tr>
            <th><?php echo __('name'); ?></th>
            <th>Slug</th>
            <th><?php echo __('description'); ?></th>
            <th class="text-center"><?php echo __('status'); ?></th>
            <th class="text-center" style="width:130px"><?php echo __('actions'); ?></th>
          </tr>
        </thead>
        <tbody id="modules-body"></tbody>
      </table>
    </div>
  </div>

</div>

<!-- ====================================================
     OFFCANVAS — CREATE / EDIT MODULE
     ==================================================== -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="modules-panel" style="width: min(520px, 100vw);">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title fw-bold" id="modules-panel-title">
      <i class="bi bi-plus-circle me-2 text-primary"></i><?php echo __('new_module'); ?>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" tabindex="-1"></button>
  </div>
  <div class="offcanvas-body">
    <form id="modules-form" novalidate>
      <input type="hidden" id="mod-id">

      <!-- Error banner -->
      <div id="modules-error" class="alert alert-danger d-none mb-3" role="alert">
        <div class="d-flex align-items-start gap-2">
          <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
          <span class="sb-error-text flex-grow-1"></span>
          <button type="button" class="btn-close" onclick="SB.form.clear('modules-error')"></button>
        </div>
      </div>

      <div class="row g-2 mb-3">
        <div class="col-7">
          <label class="form-label"><?php echo __('name'); ?> <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="mod-name">
        </div>
        <div class="col-5">
          <label class="form-label">Slug <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="mod-slug" placeholder="mi_modulo">
          <small class="text-muted">a-z, 0-9, _</small>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label"><?php echo __('description'); ?></label>
        <textarea class="form-control" id="mod-description" rows="2"></textarea>
      </div>

      <div class="mb-3">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="mod-active" checked>
          <label class="form-check-label" for="mod-active"><?php echo __('active'); ?></label>
        </div>
      </div>

      <hr>
      <p class="fw-semibold mb-2">
        <i class="bi bi-shield-lock me-1"></i><?php echo __('module_permissions'); ?>
      </p>
      <div class="row g-2 mb-3" id="mod-perms"></div>

      <hr>
      <p class="fw-semibold mb-2">
        <i class="bi bi-people me-1"></i><?php echo __('profiles'); ?> con acceso
      </p>
      <div class="row g-2 mb-3" id="mod-profiles"></div>

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
<div class="modal fade" id="modules-perms-modal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="modules-perms-title">
          <i class="bi bi-shield-lock me-2"></i>Permisos del Módulo
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" tabindex="-1"></button>
      </div>
      <div class="modal-body" id="modules-perms-body">
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
<script src="../Js/admin_modules.js"></script>
<script>ModulePermissions.init();</script>
<?php include './generales/footer.php'; ?>
