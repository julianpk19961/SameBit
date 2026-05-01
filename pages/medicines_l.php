<?php
include './generales/header.php';
include './generales/nav.php';
?>
<link rel="stylesheet" href="../css/datatables/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="../css/datatables/buttons.dataTables.min.css">

<div class="container-fluid px-4">

  <!-- Toolbar -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h5 class="fw-bold mb-0">
        <i class="bi bi-capsule me-2 text-primary"></i><?php echo __('medicine_management'); ?>
      </h5>
    </div>
    <button class="btn btn-primary" id="btn-new-medicine">
      <i class="bi bi-plus-circle"></i> <?php echo __('new_medicine'); ?>
    </button>
  </div>

  <!-- Table -->
  <div class="card shadow-sm border-0" data-module-slug="medicina_samecomed">
    <div class="card-body">
      <table class="table table-bordered table-striped mb-0 w-100" id="medical_tbl">
        <thead>
          <tr class="table text-light bg-primary">
            <th hidden></th>
            <th hidden></th>
            <th class="text-center"><?php echo __('status'); ?></th>
            <th><?php echo __('name'); ?></th>
            <th><?php echo __('reference_col'); ?></th>
            <th><?php echo __('observation_col'); ?></th>
            <th class="text-center"><?php echo __('options'); ?></th>
          </tr>
        </thead>
        <tbody id="dataMedicines"></tbody>
      </table>
    </div>
  </div>

</div>

<!-- ====================================================
     OFFCANVAS — VIEW / EDIT MEDICINE
     ==================================================== -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="medicines-panel" style="width: min(700px, 100vw);">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title fw-bold" id="medicines-panel-title">
      <i class="bi bi-capsule me-2 text-primary"></i><?php echo __('new_medicine'); ?>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <form id="medicineStored" novalidate>
      <input type="hidden" id="pk_uuid">

      <!-- Error banner -->
      <div id="medicines-error" class="alert alert-danger d-none mb-3" role="alert">
        <div class="d-flex align-items-start gap-2">
          <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
          <span class="sb-error-text flex-grow-1"></span>
          <button type="button" class="btn-close" onclick="SB.form.clear('medicines-error')"></button>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label"><?php echo __('name_label'); ?> <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="name" name="name">
      </div>
      <div class="mb-3">
        <label class="form-label"><?php echo __('reference_label'); ?></label>
        <input type="text" class="form-control" id="reference" name="reference">
      </div>
      <div class="mb-3">
        <label class="form-label"><?php echo __('observation_label'); ?></label>
        <textarea class="form-control" id="observation" name="observation" rows="3"></textarea>
      </div>

      <!-- Action buttons — populated by medicines.js -->
      <div id="save-buttons" class="d-flex justify-content-end gap-2 mb-3"></div>
    </form>

    <!-- Kardex section — populated by medicines.js when in view mode -->
    <div id="kardex"></div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="../Js/jszip/jszip.min.js"></script>
<script src="../Js/FileSaver/FileSaver.min.js"></script>
<script src="../Js/datatables/jquery.dataTables.min.js"></script>
<script src="../Js/datatables/dataTables.bootstrap5.min.js"></script>
<script src="../Js/datatables/dataTables.buttons.min.js"></script>
<script src="../Js/datatables/buttons.html5.min.js"></script>
<script src="../Js/medicines.js"></script>
<script>ModulePermissions.init();</script>
<?php include './generales/footer.php'; ?>
