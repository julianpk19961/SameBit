<?php
require_once '../config/setup.php';
require_auth();
include './generales/header.php';
include './generales/nav.php';
?>

<div class="container-fluid px-4">

  <!-- Toolbar -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h5 class="fw-bold mb-0"><i class="bi bi-telephone-inbound me-2 text-warning"></i>Llamadas del Día</h5>
      <small class="text-muted" id="fecha-hoy"></small>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary btn-sm" id="btn-refresh">
        <i class="bi bi-arrow-clockwise"></i> Actualizar
      </button>
      <button class="btn btn-warning" id="btn-nueva-llamada">
        <i class="bi bi-telephone-plus"></i> Nueva Llamada
      </button>
    </div>
  </div>

  <!-- Tabla llamadas del día -->
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <table class="table table-hover table-bordered mb-0 w-100" id="table-calls">
        <thead class="table-light">
          <tr>
            <th>Hora</th>
            <th>Paciente</th>
            <th>Documento</th>
            <th>EPS</th>
            <th>IPS</th>
            <th>Diagnóstico</th>
            <th>Contacto</th>
            <th>Aprobado</th>
            <th>Registrado por</th>
            <th></th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

</div>

<!-- ====================================================
     OFFCANVAS — REGISTRO DE LLAMADA
     ==================================================== -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-registro" style="width: min(680px, 100vw);">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title fw-bold" id="offcanvas-registro-title">
      <i class="bi bi-telephone-plus me-2 text-warning"></i>Registrar Llamada
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" tabindex="-1"></button>
  </div>

  <div class="offcanvas-body">
    <form id="form-registro-call" novalidate>
      <input type="hidden" id="call-pk-uuid">
      <input type="hidden" id="call-priority-id">

      <!-- Banner de errores de validación -->
      <div id="form-error-banner" class="alert alert-danger d-none mb-3" role="alert">
        <div class="d-flex align-items-start gap-2">
          <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
          <span id="form-error-text" class="flex-grow-1"></span>
          <button type="button" class="btn-close" onclick="clearFormError()" aria-label="Cerrar"></button>
        </div>
      </div>

      <!-- ── Búsqueda paciente (solo en modo crear) ── -->
      <div class="form-section" id="search-section">
        <p class="form-section-title">Buscar paciente</p>

        <div class="input-group mb-2">
          <select class="form-select" id="call-document-type" style="max-width:190px;">
            <option value="11">Reg. Civil</option>
            <option value="12">Tarjeta Identidad</option>
            <option value="13" selected>Cédula</option>
            <option value="21">T. Extranjería</option>
            <option value="22">C. Extranjería</option>
            <option value="31">NIT</option>
            <option value="41">Pasaporte</option>
            <option value="42">Doc. Extranjero</option>
            <option value="43">No definido DIAN</option>
          </select>
          <input type="text" class="form-control" id="call-dni"
            placeholder="Buscar por nombre o número de documento..." autofocus autocomplete="off">
        </div>

        <!-- Paciente seleccionado -->
        <div id="call-selected-patient" style="display:none;" class="patient-tag mb-2">
          <i class="bi bi-person-check-fill text-success"></i>
          <span id="call-selected-name" class="fw-semibold"></span>
          <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-auto" id="call-clear-patient" tabindex="-1">
            <i class="bi bi-x-circle"></i>
          </button>
        </div>

        <!-- Resultados búsqueda -->
        <ul id="call-patient-list" class="patient-dropdown" style="display:none;"></ul>
      </div>

      <!-- ── Paciente (solo en modo editar: DNI no modificable) ── -->
      <div class="form-section" id="edit-patient-section" style="display:none;">
        <p class="form-section-title">Paciente</p>
        <div class="patient-tag">
          <i class="bi bi-person-badge-fill text-warning"></i>
          <div>
            <div class="fw-semibold" id="edit-patient-name-display"></div>
            <small class="text-muted" id="edit-patient-dni-display"></small>
          </div>
          <span class="ms-auto badge bg-secondary" title="El documento de identidad no puede modificarse">
            <i class="bi bi-lock-fill me-1"></i>DNI fijo
          </span>
        </div>
      </div>

      <!-- ── Datos paciente ── -->
      <div class="form-section">
        <p class="form-section-title">Información del paciente</p>
        <div class="row g-2">
          <div class="col-6">
            <label class="form-label">NOMBRES <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="call-nombre" placeholder="Nombres del paciente" novalidate>
          </div>
          <div class="col-6">
            <label class="form-label">APELLIDOS <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="call-apellido" placeholder="Apellidos del paciente" novalidate>
          </div>
          <div class="col-6">
            <label class="form-label">IPS <span class="text-danger">*</span></label>
            <select class="form-select" id="call-ips" novalidate>
              <option value="">— Seleccione IPS —</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label">EPS <span class="text-danger">*</span></label>
            <select class="form-select" id="call-eps" novalidate>
              <option value="">— Seleccione EPS —</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label">RANGO <span class="text-danger">*</span></label>
            <select class="form-select" id="call-eps-classification" novalidate>
              <option value="" disabled selected>— Seleccione —</option>
              <option value="0">A</option>
              <option value="1">B</option>
              <option value="2">C</option>
              <option value="3">Sisben</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label">ESTADO EPS <span class="text-danger">*</span></label>
            <select class="form-select" id="call-eps-status" novalidate>
              <option value="" disabled selected>— Seleccione —</option>
              <option value="0">Inactivo</option>
              <option value="1">Activo</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label">TIPO CONTACTO <span class="text-danger">*</span></label>
            <select class="form-select" id="call-contact-type" novalidate>
              <option value="0">Llamada</option>
              <option value="correo">Correo</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label">APROBADO <span class="text-danger">*</span></label>
            <select class="form-select" id="call-approved" novalidate>
              <option value="" disabled selected>— Seleccione —</option>
              <option value="0">No</option>
              <option value="1">Sí</option>
            </select>
          </div>
        </div>
      </div>

      <!-- ── Referencia ── -->
      <div class="form-section">
        <p class="form-section-title ref">Referencia</p>
        <div class="row g-2">
          <div class="col-6">
            <label class="form-label">DIAGNÓSTICO <span class="text-danger">*</span></label>
            <input type="hidden" id="call-diagnosis">
            <input type="text" class="form-control" id="call-diagnosis-search"
              placeholder="Buscar por código o descripción..." autocomplete="off" novalidate>
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
            <label class="form-label">N° LLAMADAS / CORREOS</label>
            <input type="number" class="form-control" id="call-number" placeholder="0" min="0" novalidate>
          </div>
          <div class="col-6">
            <label class="form-label">FECHA SOLICITUD <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="datetime-local" class="form-control comunication_in" id="call-check-in-date" novalidate>
              <button type="button" class="btn btn-outline-secondary" onclick="setNow('#call-check-in-date')" title="Poner fecha y hora actual" tabindex="-1">Ahora</button>
            </div>
          </div>
          <div class="col-6">
            <label class="form-label">FECHA COMENTARIO <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="datetime-local" class="form-control comunication_out" id="call-comment-date" novalidate>
              <button type="button" class="btn btn-outline-secondary" onclick="setNow('#call-comment-date')" title="Poner fecha y hora actual" tabindex="-1">Ahora</button>
            </div>
          </div>
          <div class="col-6">
            <label class="form-label">FECHA CITA</label>
            <input type="datetime-local" class="form-control" id="call-attention-date" disabled novalidate>
          </div>
          <div class="col-6">
            <label class="form-label">¿ANEXO 9?</label>
            <select class="form-select" id="call-exhibit-nine" novalidate>
              <option value="" disabled selected>— Seleccione —</option>
              <option value="0">No</option>
              <option value="1">Sí</option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label">REMITIDO DESDE <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="call-sent-by" placeholder="Ej: Hospital General, Clínica X..." novalidate>
          </div>
          <div class="col-12">
            <label class="form-label">OBSERVACIÓN <span class="text-danger">*</span></label>
            <textarea class="form-control" id="call-observation-in" rows="2"
              placeholder="Describa la observación de la solicitud..." novalidate></textarea>
          </div>
        </div>
      </div>

      <!-- ── Contra-Referencia ── -->
      <div class="form-section">
        <p class="form-section-title cref">Contra-Referencia</p>
        <div class="row g-2">
          <div class="col-8">
            <label class="form-label">REMITIDO A <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="call-send-to" placeholder="Destino de la contra-referencia..." novalidate>
          </div>
          <div class="col-4">
            <label class="form-label">¿ANEXO 10?</label>
            <select class="form-select" id="call-exhibit-ten" novalidate>
              <option value="" disabled selected>— Seleccione —</option>
              <option value="0">No</option>
              <option value="1">Sí</option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label">OBSERVACIÓN <span class="text-danger">*</span></label>
            <textarea class="form-control" id="call-observation-out" rows="2"
              placeholder="Describa la observación de la contra-referencia..." novalidate></textarea>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2 pt-2">
        <button type="button" class="btn btn-secondary" id="call-btn-clean">
          Cancelar
        </button>
        <button type="submit" class="btn btn-success px-4">
          Guardar Llamada
        </button>
      </div>

    </form>
  </div>
</div>

<script src="../Js/datatables/jquery.dataTables.min.js"></script>
<script src="../Js/datatables/dataTables.bootstrap5.min.js"></script>
<script src="../Js/calls.js"></script>
<?php include './generales/footer.php'; ?>
