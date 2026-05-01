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
      <h5 class="fw-bold mb-0"><i class="bi bi-telephone-inbound me-2 text-warning"></i><?php echo __('calls_of_day'); ?></h5>
      <small class="text-muted" id="fecha-hoy"></small>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary btn-sm" id="btn-refresh">
        <i class="bi bi-arrow-clockwise"></i> <?php echo __('refresh'); ?>
      </button>
      <button class="btn btn-warning" id="btn-nueva-llamada">
        <i class="bi bi-telephone-plus"></i> <?php echo __('new_call'); ?>
      </button>
    </div>
  </div>

  <!-- Calls table -->
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <table class="table table-hover table-bordered mb-0 w-100" id="table-calls">
        <thead class="table-light">
          <tr>
            <th><?php echo __('hour'); ?></th>
            <th><?php echo __('patient'); ?></th>
            <th><?php echo __('document'); ?></th>
            <th><?php echo __('eps'); ?></th>
            <th><?php echo __('ips'); ?></th>
            <th><?php echo __('diagnosis_col'); ?></th>
            <th><?php echo __('contact'); ?></th>
            <th><?php echo __('approved'); ?></th>
            <th><?php echo __('registered_by'); ?></th>
            <th></th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

</div>

<!-- ====================================================
     OFFCANVAS — CALL REGISTRATION
     ==================================================== -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-registro" style="width: min(680px, 100vw);">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title fw-bold" id="offcanvas-registro-title">
      <i class="bi bi-telephone-plus me-2 text-warning"></i><?php echo __('register_call'); ?>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" tabindex="-1"></button>
  </div>

  <div class="offcanvas-body">
    <form id="form-registro-call" novalidate>
      <input type="hidden" id="call-pk-uuid">
      <input type="hidden" id="call-priority-id">

      <!-- Validation error banner -->
      <div id="form-error-banner" class="alert alert-danger d-none mb-3" role="alert">
        <div class="d-flex align-items-start gap-2">
          <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
          <span id="form-error-text" class="flex-grow-1"></span>
          <button type="button" class="btn-close" onclick="clearFormError()" aria-label="Close"></button>
        </div>
      </div>

      <!-- ── Patient search (create mode only) ── -->
      <div class="form-section" id="search-section">
        <p class="form-section-title"><?php echo __('search_patient'); ?></p>

        <div class="input-group mb-2">
          <select class="form-select" id="call-document-type" style="max-width:190px;">
            <option value="11"><?php echo __('doc_civil_registry'); ?></option>
            <option value="12"><?php echo __('doc_id_card'); ?></option>
            <option value="13" selected><?php echo __('doc_national_id'); ?></option>
            <option value="21"><?php echo __('doc_foreign_card'); ?></option>
            <option value="22"><?php echo __('doc_foreign_id'); ?></option>
            <option value="31"><?php echo __('doc_nit'); ?></option>
            <option value="41"><?php echo __('doc_passport'); ?></option>
            <option value="42"><?php echo __('doc_foreign_doc'); ?></option>
            <option value="43"><?php echo __('doc_undefined_dian'); ?></option>
          </select>
          <input type="text" class="form-control" id="call-dni"
            placeholder="<?php echo __('search_by_name_or_doc'); ?>" autofocus autocomplete="off">
        </div>

        <!-- Selected patient -->
        <div id="call-selected-patient" style="display:none;" class="patient-tag mb-2">
          <i class="bi bi-person-check-fill text-success"></i>
          <span id="call-selected-name" class="fw-semibold"></span>
          <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-auto" id="call-clear-patient" tabindex="-1">
            <i class="bi bi-x-circle"></i>
          </button>
        </div>

        <!-- Search results -->
        <ul id="call-patient-list" class="patient-dropdown" style="display:none;"></ul>
      </div>

      <!-- ── Patient (edit mode: DNI not editable) ── -->
      <div class="form-section" id="edit-patient-section" style="display:none;">
        <p class="form-section-title"><?php echo __('patient'); ?></p>
        <div class="patient-tag">
          <i class="bi bi-person-badge-fill text-warning"></i>
          <div>
            <div class="fw-semibold" id="edit-patient-name-display"></div>
            <small class="text-muted" id="edit-patient-dni-display"></small>
          </div>
          <span class="ms-auto badge bg-secondary" title="<?php echo __('fixed_dni_title'); ?>">
            <i class="bi bi-lock-fill me-1"></i><?php echo __('fixed_dni_badge'); ?>
          </span>
        </div>
      </div>

      <!-- ── Patient data ── -->
      <div class="form-section">
        <p class="form-section-title"><?php echo __('patient_info'); ?></p>
        <div class="row g-2">
          <div class="col-6">
            <label class="form-label"><?php echo __('first_name'); ?> <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="call-nombre" placeholder="<?php echo __('first_name'); ?>" novalidate>
          </div>
          <div class="col-6">
            <label class="form-label"><?php echo __('last_name'); ?> <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="call-apellido" placeholder="<?php echo __('last_name'); ?>" novalidate>
          </div>
          <div class="col-6">
            <label class="form-label"><?php echo __('ips'); ?> <span class="text-danger">*</span></label>
            <select class="form-select" id="call-ips" novalidate>
              <option value=""><?php echo __('select_ips'); ?></option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label"><?php echo __('eps'); ?> <span class="text-danger">*</span></label>
            <select class="form-select" id="call-eps" novalidate>
              <option value=""><?php echo __('select_eps'); ?></option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label"><?php echo __('range_label'); ?> <span class="text-danger">*</span></label>
            <select class="form-select" id="call-eps-classification" novalidate>
              <option value="" disabled selected><?php echo __('select_option'); ?></option>
              <option value="0">A</option>
              <option value="1">B</option>
              <option value="2">C</option>
              <option value="3">Sisben</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label"><?php echo __('eps_status'); ?> <span class="text-danger">*</span></label>
            <select class="form-select" id="call-eps-status" novalidate>
              <option value="" disabled selected><?php echo __('select_option'); ?></option>
              <option value="0"><?php echo __('inactive'); ?></option>
              <option value="1"><?php echo __('active'); ?></option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label"><?php echo __('contact_type'); ?> <span class="text-danger">*</span></label>
            <select class="form-select" id="call-contact-type" novalidate>
              <option value="0"><?php echo __('call_type'); ?></option>
              <option value="correo"><?php echo __('email_type'); ?></option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label"><?php echo __('approved'); ?> <span class="text-danger">*</span></label>
            <select class="form-select" id="call-approved" novalidate>
              <option value="" disabled selected><?php echo __('select_option'); ?></option>
              <option value="0"><?php echo __('no'); ?></option>
              <option value="1"><?php echo __('yes'); ?></option>
            </select>
          </div>
        </div>
      </div>

      <!-- ── Reference ── -->
      <div class="form-section">
        <p class="form-section-title ref"><?php echo __('reference_section'); ?></p>
        <div class="row g-2">
          <div class="col-6">
            <label class="form-label"><?php echo __('diagnosis_label'); ?> <span class="text-danger">*</span></label>
            <input type="hidden" id="call-diagnosis">
            <input type="text" class="form-control" id="call-diagnosis-search"
              placeholder="<?php echo __('js_search_by_code_or_desc'); ?>" autocomplete="off" novalidate>
            <ul id="call-diagnosis-list" class="patient-dropdown" style="display:none;"></ul>
            <div id="call-diagnosis-selected" style="display:none;" class="patient-tag mt-1">
              <i class="bi bi-clipboard2-pulse-fill text-success"></i>
              <span id="call-diagnosis-name" class="fw-semibold" style="font-size:.82rem; flex:1;"></span>
              <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-auto" id="call-clear-diagnosis" tabindex="-1">
                <i class="bi bi-x-circle"></i>
              </button>
            </div>
          </div>
          <div class="col-6">
            <label class="form-label"><?php echo __('num_calls_emails'); ?></label>
            <input type="number" class="form-control" id="call-number" placeholder="0" min="0" novalidate>
          </div>
          <div class="col-6">
            <label class="form-label"><?php echo __('request_date'); ?> <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="datetime-local" class="form-control comunication_in" id="call-check-in-date" novalidate>
              <button type="button" class="btn btn-outline-secondary" onclick="setNow('#call-check-in-date')" title="<?php echo __('now_btn'); ?>" tabindex="-1"><?php echo __('now_btn'); ?></button>
            </div>
          </div>
          <div class="col-6">
            <label class="form-label"><?php echo __('comment_date'); ?> <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="datetime-local" class="form-control comunication_out" id="call-comment-date" novalidate>
              <button type="button" class="btn btn-outline-secondary" onclick="setNow('#call-comment-date')" title="<?php echo __('now_btn'); ?>" tabindex="-1"><?php echo __('now_btn'); ?></button>
            </div>
          </div>
          <div class="col-6">
            <label class="form-label"><?php echo __('appointment_date'); ?></label>
            <input type="datetime-local" class="form-control" id="call-attention-date" disabled novalidate>
          </div>
          <div class="col-6">
            <label class="form-label"><?php echo __('annex_9'); ?></label>
            <select class="form-select" id="call-exhibit-nine" novalidate>
              <option value="" disabled selected><?php echo __('select_option'); ?></option>
              <option value="0"><?php echo __('no'); ?></option>
              <option value="1"><?php echo __('yes'); ?></option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label"><?php echo __('sent_from'); ?> <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="call-sent-by" placeholder="<?php echo __('js_sent_from_placeholder'); ?>" novalidate>
          </div>
          <div class="col-12">
            <label class="form-label"><?php echo __('observation'); ?> <span class="text-danger">*</span></label>
            <textarea class="form-control" id="call-observation-in" rows="2"
              placeholder="<?php echo __('js_observation_in_placeholder'); ?>" novalidate></textarea>
          </div>
        </div>
      </div>

      <!-- ── Counter-Reference ── -->
      <div class="form-section">
        <p class="form-section-title cref"><?php echo __('counter_reference'); ?></p>
        <div class="row g-2">
          <div class="col-8">
            <label class="form-label"><?php echo __('sent_to'); ?> <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="call-send-to" placeholder="<?php echo __('js_send_to_placeholder'); ?>" novalidate>
          </div>
          <div class="col-4">
            <label class="form-label"><?php echo __('annex_10'); ?></label>
            <select class="form-select" id="call-exhibit-ten" novalidate>
              <option value="" disabled selected><?php echo __('select_option'); ?></option>
              <option value="0"><?php echo __('no'); ?></option>
              <option value="1"><?php echo __('yes'); ?></option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label"><?php echo __('observation'); ?> <span class="text-danger">*</span></label>
            <textarea class="form-control" id="call-observation-out" rows="2"
              placeholder="<?php echo __('js_observation_out_placeholder'); ?>" novalidate></textarea>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2 pt-2">
        <button type="button" class="btn btn-secondary" id="call-btn-clean">
          <?php echo __('cancel'); ?>
        </button>
        <button type="submit" class="btn btn-success px-4">
          <?php echo __('save_call'); ?>
        </button>
      </div>

    </form>
  </div>
</div>

<script src="../Js/datatables/jquery.dataTables.min.js"></script>
<script src="../Js/datatables/dataTables.bootstrap5.min.js"></script>
<script src="../Js/calls.js"></script>
<script>ModulePermissions.init();</script>
<?php include './generales/footer.php'; ?>
