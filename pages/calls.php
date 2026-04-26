<?php include './generales/header.php'; ?>

<style>
  .offcanvas-body {
    background: var(--app-bg);
  }

  .form-section {
    background: var(--card-bg);
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
    border: 1px solid var(--card-border);
  }

  .form-section-title {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 12px;
  }

  .form-section-title.ref  { color: #198754; }
  .form-section-title.cref { color: #e67e22; }

  .patient-tag {
    background: var(--alert-warn-bg);
    border: 1px solid var(--card-border);
    border-radius: 6px;
    padding: 6px 10px;
    font-size: .875rem;
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-primary);
  }

  .patient-dropdown {
    list-style: none;
    padding: 0;
    margin: 4px 0 0;
    border: 1px solid var(--card-border);
    border-radius: 8px;
    background: var(--card-bg);
    max-height: 280px;
    overflow-y: auto;
    box-shadow: 0 4px 16px rgba(0,0,0,.18);
  }
  .patient-dropdown li {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 14px;
    cursor: pointer;
    border-bottom: 1px solid var(--card-border);
    transition: background .12s;
  }
  .patient-dropdown li:last-child { border-bottom: none; }
  .patient-dropdown li:hover { background: var(--app-bg-subtle); }
  .patient-dropdown .pd-doc {
    font-size: .78rem;
    color: var(--text-muted);
    min-width: 120px;
  }
  .patient-dropdown .pd-name {
    flex: 1;
    font-weight: 500;
    color: var(--text-primary);
  }
  .patient-dropdown .pd-icon {
    font-size: 1.1rem;
    color: var(--link-primary);
    opacity: .7;
  }

  #table-calls_wrapper {
    overflow-x: auto;
  }
  #table-calls_wrapper .dataTables_length select,
  #table-calls_wrapper .dataTables_filter input {
    border: 1px solid var(--card-border);
    border-radius: 6px;
    padding: 4px 8px;
  }
  #table-calls td, #table-calls th { white-space: nowrap; }

  #table-calls_wrapper .dataTables_length select,
  #table-calls_wrapper .dataTables_filter input {
    border: 1px solid var(--card-border);
    border-radius: 6px;
    padding: 4px 8px;
  }

  /* SweetAlert2 debe aparecer por encima del offcanvas (z-index 1045) */
  .swal2-container { z-index: 99999 !important; }
</style>

<header class="d-flex flex-wrap justify-content-between align-items-center py-3 mb-4 border-bottom px-4">
  <a href="/pages/dashboard.php" class="d-flex align-items-center text-dark text-decoration-none">
    <img src="../img/logo.png" height="40" class="logo">
    <span class="ms-2 fw-bold text-primary"><?php echo htmlspecialchars($appName); ?></span>
  </a>
  <nav>
    <ul class="nav nav-pills align-items-center gap-1">
      <li class="nav-item"><a href="./dashboard.php" class="nav-link">
          <i class="bi bi-house"></i> Inicio</a></li>
      <li class="nav-item"><span class="nav-link text-muted">
          <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['usuario']); ?></span></li>
      <li class="nav-item">
        <button id="theme-toggle" class="btn btn-sm btn-outline-secondary" title="Modo sistema">
          <i class="bi bi-circle-half" id="theme-icon"></i>
        </button>
      </li>
      <li class="nav-item"><a href="../config/logout.php" class="nav-link link-danger">Cerrar Sesión</a></li>
    </ul>
  </nav>
</header>

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
    <h5 class="offcanvas-title fw-bold">
      <i class="bi bi-telephone-plus me-2 text-warning"></i>Registrar Llamada
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>

  <div class="offcanvas-body">
    <form id="form-registro-call" novalidate>
      <input type="hidden" id="call-pk-uuid">

      <!-- ── Búsqueda paciente ── -->
      <div class="form-section">
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
          <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-auto" id="call-clear-patient">
            <i class="bi bi-x-circle"></i>
          </button>
        </div>

        <!-- Resultados búsqueda -->
        <ul id="call-patient-list" class="patient-dropdown" style="display:none;"></ul>
      </div>

      <!-- ── Datos paciente ── -->
      <div class="form-section">
        <p class="form-section-title">Información del paciente</p>
        <div class="row g-2">
          <div class="col-6">
            <label class="form-label">NOMBRES *</label>
            <input type="text" class="form-control" id="call-nombre" placeholder="Nombres del paciente" novalidate>
          </div>
          <div class="col-6">
            <label class="form-label">APELLIDOS *</label>
            <input type="text" class="form-control" id="call-apellido" placeholder="Apellidos del paciente" novalidate>
          </div>
          <div class="col-6">
            <label class="form-label">IPS *</label>
            <select class="form-select" id="call-ips" novalidate>
              <option value="">— Seleccione IPS —</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label">EPS *</label>
            <select class="form-select" id="call-eps" novalidate>
              <option value="">— Seleccione EPS —</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label">RANGO *</label>
            <select class="form-select" id="call-eps-classification" novalidate>
              <option value="" disabled selected>— Seleccione —</option>
              <option value="0">A</option>
              <option value="1">B</option>
              <option value="2">C</option>
              <option value="3">Sisben</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label">ESTADO EPS *</label>
            <select class="form-select" id="call-eps-status" novalidate>
              <option value="" disabled selected>— Seleccione —</option>
              <option value="0">Inactivo</option>
              <option value="1">Activo</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label">TIPO CONTACTO *</label>
            <select class="form-select" id="call-contact-type" novalidate>
              <option value="0">Llamada</option>
              <option value="correo">Correo</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label">APROBADO *</label>
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
            <label class="form-label">DIAGNÓSTICO *</label>
            <input type="hidden" id="call-diagnosis">
            <input type="text" class="form-control" id="call-diagnosis-search"
              placeholder="Buscar por código o descripción..." autocomplete="off" novalidate>
            <ul id="call-diagnosis-list" class="patient-dropdown" style="display:none;"></ul>
            <div id="call-diagnosis-selected" style="display:none;" class="patient-tag mt-1">
              <i class="bi bi-clipboard2-pulse-fill text-success"></i>
              <span id="call-diagnosis-name" class="fw-semibold" style="font-size:.82rem; flex:1;"></span>
              <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-auto" id="call-clear-diagnosis">
                <i class="bi bi-x-circle"></i>
              </button>
            </div>
          </div>
          <div class="col-6">
            <label class="form-label">N° LLAMADAS / CORREOS</label>
            <input type="number" class="form-control" id="call-number" placeholder="0" min="0" novalidate>
          </div>
          <div class="col-6">
            <label class="form-label">FECHA SOLICITUD *</label>
            <div class="input-group">
              <input type="datetime-local" class="form-control comunication_in" id="call-check-in-date" novalidate>
              <button type="button" class="btn btn-outline-secondary" onclick="setNow('#call-check-in-date')" title="Poner fecha y hora actual">Ahora</button>
            </div>
          </div>
          <div class="col-6">
            <label class="form-label">FECHA COMENTARIO *</label>
            <div class="input-group">
              <input type="datetime-local" class="form-control comunication_out" id="call-comment-date" novalidate>
              <button type="button" class="btn btn-outline-secondary" onclick="setNow('#call-comment-date')" title="Poner fecha y hora actual">Ahora</button>
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
            <label class="form-label">REMITIDO DESDE *</label>
            <input type="text" class="form-control" id="call-sent-by" placeholder="Ej: Hospital General, Clínica X..." novalidate>
          </div>
          <div class="col-12">
            <label class="form-label">OBSERVACIÓN *</label>
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
            <label class="form-label">REMITIDO A *</label>
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
            <label class="form-label">OBSERVACIÓN *</label>
            <textarea class="form-control" id="call-observation-out" rows="2"
              placeholder="Describa la observación de la contra-referencia..." novalidate></textarea>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2 pt-2">
        <button type="button" class="btn btn-light border" id="call-btn-clean">
          <i class="bi bi-eraser"></i> Limpiar
        </button>
        <button type="submit" class="btn btn-success px-4">
          <i class="bi bi-check-lg"></i> Guardar Llamada
        </button>
      </div>

    </form>
  </div>
</div>

<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js"></script>
<script src="../Js/calls.js"></script>
</body>