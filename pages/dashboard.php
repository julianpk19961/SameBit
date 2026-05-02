<?php
include './generales/header.php';
include './generales/nav.php';
?>
<link rel="stylesheet" href="../css/datatables/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="../css/datatables/buttons.dataTables.min.css">

<div class="container-fluid mt-5">
  <div class="row mb-4">
    <div class="col-12">
      <h2 class="text-center text-primary fw-bold mb-4"><?php echo __('control_panel'); ?></h2>
    </div>
  </div>

  <div class="row g-4 mb-5">
    <!-- Dashboard content goes here -->
  </div>
</div>

<!-- Modal: Reports -->
<div class="modal fade" id="modal-report" tabindex="-1" aria-labelledby="modal-titulo" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h1 class="modal-title fs-5" id="modal-titulo"><?php echo __('registered_data'); ?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" method="post" class="p-1" id="form-reporte">
          <div class="row">
            <div class="col-6">
              <p class="fw-semibold small text-muted mb-1"><?php echo __('general_data'); ?></p>
              <div class="row">
                <div class="col-6" title="<?php echo __('search_by_name_or_doc'); ?>">
                  <label for="dni-request"><?php echo __('patient_label'); ?></label>
                  <input type="text" name="dni-request" id="dni-request" class="form-control" placeholder="">
                </div>
                <div class="col-6">
                  <label for="user-request"><?php echo __('priority_user'); ?></label>
                  <select name="user-request" id="user-request" class="form-control">
                    <option value="" selected></option>
                    <option value="Andrés Toro">ANDRES TORO</option>
                    <option value="Alejandro Osma">ALEJANDRO OSMA</option>
                    <option value="Julián Villa">JULIAN VILLA</option>
                    <option value="Julian Rodriguez">JULIAN RODRIGUEZ</option>
                    <option value="XIOMARA LOPERA">XIOMARA LOPERA</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-6">
              <p class="fw-semibold small text-muted mb-1"><?php echo __('request_date_range'); ?></p>
              <div class="row">
                <div class="col-6">
                  <label for="checkin-start"><?php echo __('from'); ?></label>
                  <input type="datetime-local" name="checkin-start" id="checkin-start" class="form-control" step="any">
                </div>
                <div class="col-6">
                  <label for="checkin-end"><?php echo __('to'); ?></label>
                  <input type="datetime-local" name="checkin-end" id="checkin-end" class="form-control" step="any">
                </div>
              </div>
            </div>
            <div class="col-6">
              <p class="fw-semibold small text-muted mb-1"><?php echo __('comment_date_range'); ?></p>
              <div class="row">
                <div class="col-6">
                  <label for="checkout-start"><?php echo __('from'); ?></label>
                  <input type="datetime-local" name="checkout-start" id="checkout-start" class="form-control">
                </div>
                <div class="col-6">
                  <label for="checkout-end"><?php echo __('to'); ?></label>
                  <input type="datetime-local" name="checkout-end" id="checkout-end" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-6">
              <p class="fw-semibold small text-muted mb-1"><?php echo __('appointment_date_range'); ?></p>
              <div class="row">
                <div class="col-6">
                  <label for="appointment-start"><?php echo __('from'); ?></label>
                  <input type="datetime-local" name="appointment-start" id="appointment-start" class="form-control">
                </div>
                <div class="col-6">
                  <label for="appointment-end"><?php echo __('to'); ?></label>
                  <input type="datetime-local" name="appointment-end" id="appointment-end" class="form-control">
                </div>
              </div>
            </div>
            <div class="row-inline m-1">
              <div>
                <button type="button" class="btn btn-sm btn-success m-1 float-end" onclick="showReportCard()"><?php echo __('search'); ?></button>
              </div>
              <div>
                <button type="button" class="btn btn-sm btn-secondary m-1 float-end" id="btn-limpiar-reporte"><?php echo __('clear'); ?></button>
              </div>
            </div>
          </div>
        </form>
        <hr class="w-100">
        <div class="container-fluid w-100">
          <table class="table table-lg table-striped table-responsive w-100" id="table-resumen">
            <thead class="table-light">
              <tr>
                <th><?php echo __('reception'); ?></th>
                <th><?php echo __('reception_time'); ?></th>
                <th><?php echo __('response'); ?></th>
                <th><?php echo __('response_time'); ?></th>
                <th><?php echo __('document'); ?></th>
                <th><?php echo __('patient'); ?></th>
                <th><?php echo __('sent'); ?></th>
                <th><?php echo __('ips'); ?></th>
                <th><?php echo __('eps'); ?></th>
                <th><?php echo __('range'); ?></th>
                <th><?php echo __('diagnosis_col'); ?></th>
                <th><?php echo __('approved_col'); ?></th>
                <th><?php echo __('appointment_date_col'); ?></th>
                <th><?php echo __('appointment_time_col'); ?></th>
                <th><?php echo __('annex_9_col'); ?></th>
                <th><?php echo __('annex_10_col'); ?></th>
                <th><?php echo __('sent_to_col'); ?></th>
                <th><?php echo __('comment_col'); ?></th>
                <th><?php echo __('counter_ref_comment'); ?></th>
                <th><?php echo __('register_user'); ?></th>
                <th><?php echo __('days_to_response'); ?></th>
                <th><?php echo __('time_to_response'); ?></th>
                <th><?php echo __('days_to_appointment'); ?></th>
                <th><?php echo __('time_to_appointment'); ?></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="../Js/jszip/jszip.min.js"></script>
<script src="../Js/FileSaver/FileSaver.min.js"></script>
<script src="../Js/datatables/jquery.dataTables.min.js"></script>
<script src="../Js/datatables/dataTables.bootstrap5.min.js"></script>
<script src="../Js/datatables/dataTables.buttons.min.js"></script>
<script src="../Js/datatables/buttons.html5.min.js"></script>
<script src="../Js/dashboard.js"></script>
<script>ModulePermissions.init();</script>
<?php include './generales/footer.php'; ?>
