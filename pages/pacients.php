<?php
require_once '../config/setup.php';
require_auth();
include './generales/header.php';
include './generales/nav.php';
?>
<link rel="stylesheet" href="../css/datatables/dataTables.bootstrap5.min.css">

<div class="container-fluid px-4">

  <!-- Toolbar -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h5 class="fw-bold mb-0">
        <i class="bi bi-person-vcard me-2 text-primary"></i><?php echo __('patient_registration'); ?>
      </h5>
    </div>
    <button class="btn btn-primary" onclick="pacientsOpenCreate()">
      <i class="bi bi-person-plus"></i> <?php echo __('new_patient') ?? 'Nuevo Paciente'; ?>
    </button>
  </div>

  <!-- Table -->
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <table class="table table-hover table-bordered mb-0 w-100" id="pacients-table">
        <thead class="table-light">
          <tr>
            <th><?php echo __('doc_national_id') ?? 'Documento'; ?></th>
            <th><?php echo __('name'); ?></th>
            <th><?php echo __('last_name'); ?></th>
            <th><?php echo __('eps') ?? 'EPS'; ?></th>
            <th><?php echo __('status'); ?></th>
            <th class="text-center" style="width:80px"><?php echo __('actions'); ?></th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

</div>

<!-- ====================================================
     OFFCANVAS — CREATE / EDIT PATIENT
     ==================================================== -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="pacients-panel" style="width: min(560px, 100vw);">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title fw-bold" id="pacients-panel-title">
      <i class="bi bi-person-plus me-2 text-primary"></i><?php echo __('new_patient') ?? 'Nuevo Paciente'; ?>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" tabindex="-1"></button>
  </div>
  <div class="offcanvas-body">
    <form id="pacients-form" novalidate>
      <input type="hidden" id="pac-id">

      <!-- Error banner -->
      <div id="pacients-error" class="alert alert-danger d-none mb-3" role="alert">
        <div class="d-flex align-items-start gap-2">
          <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
          <span class="sb-error-text flex-grow-1"></span>
          <button type="button" class="btn-close" onclick="SB.form.clear('pacients-error')"></button>
        </div>
      </div>

      <!-- Identification -->
      <div class="row g-2 mb-3">
        <div class="col-5">
          <label class="form-label">Tipo ID <span class="text-danger">*</span></label>
          <select class="form-select" id="pac-doc-type">
            <option value="11">Registro Civil</option>
            <option value="12">Tarjeta Identidad</option>
            <option value="13" selected>Cédula</option>
            <option value="21">Tarjeta Extranjería</option>
            <option value="22">Cédula Extranjería</option>
            <option value="31">NIT</option>
            <option value="41">Pasaporte</option>
            <option value="42">Doc. Extranjero</option>
            <option value="43">No definido DIAN</option>
          </select>
        </div>
        <div class="col-7">
          <label class="form-label"><?php echo __('document') ?? 'Identificación'; ?> <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="pac-dni" autocomplete="off">
        </div>
      </div>

      <!-- Name -->
      <div class="row g-2 mb-3">
        <div class="col">
          <label class="form-label"><?php echo __('first_name'); ?> <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="pac-first-name">
        </div>
        <div class="col">
          <label class="form-label"><?php echo __('last_name'); ?> <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="pac-last-name">
        </div>
      </div>

      <!-- Demographics -->
      <div class="row g-2 mb-3">
        <div class="col-6">
          <label class="form-label">Género</label>
          <select class="form-select" id="pac-gender">
            <option value="">Elige...</option>
            <option value="1">Mujer</option>
            <option value="2">Hombre</option>
            <option value="3">Otro</option>
          </select>
        </div>
        <div class="col-6">
          <label class="form-label">Fecha Nacimiento</label>
          <input type="date" class="form-control" id="pac-birth-date">
        </div>
      </div>

      <!-- Contact -->
      <div class="row g-2 mb-3">
        <div class="col-6">
          <label class="form-label">Teléfono</label>
          <input type="tel" class="form-control" id="pac-phone">
        </div>
        <div class="col-6">
          <label class="form-label">Celular</label>
          <input type="tel" class="form-control" id="pac-mobile">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Correo</label>
        <input type="email" class="form-control" id="pac-email">
      </div>

      <div class="mb-3">
        <label class="form-label">Dirección</label>
        <input type="text" class="form-control" id="pac-address">
      </div>

      <div class="mb-3">
        <label class="form-label"><?php echo __('status'); ?></label>
        <select class="form-select" id="pac-status">
          <option value="1"><?php echo __('active'); ?></option>
          <option value="2"><?php echo __('inactive'); ?></option>
        </select>
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

<script src="../Js/datatables/jquery.dataTables.min.js"></script>
<script src="../Js/datatables/dataTables.bootstrap5.min.js"></script>
<script src="../Js/pacients.js"></script>
<script>ModulePermissions.init();</script>
<?php include './generales/footer.php'; ?>
