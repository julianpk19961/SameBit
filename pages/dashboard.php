<?php
include './generales/header.php';
include './generales/nav.php';
?>

<div class="container-fluid mt-5">
  <div class="row mb-4">
    <div class="col-12">
      <h2 class="text-center text-primary fw-bold mb-4">Panel de Control</h2>
    </div>
  </div>

  <div class="row g-4 mb-5">
    <!-- Card: Registro de Llamadas -->
    <div class="col-md-6 col-lg-3">
      <div class="card h-100 shadow-sm border-0 hover-card" id="btn-new-record-card">
        <div class="card-body text-center d-flex flex-column justify-content-center">
          <div class="display-4 mb-3">📞</div>
          <h5 class="card-title fw-bold">Registro de Llamadas</h5>
          <p class="card-text text-muted small">Registra nuevas llamadas y novedades de pacientes</p>
        </div>
      </div>
    </div>

    <!-- Card: Gestión de Medicamentos -->
    <div class="col-md-6 col-lg-3">
      <div class="card h-100 shadow-sm border-0 hover-card" onclick="location.href='./medicines_l.php'">
        <div class="card-body text-center d-flex flex-column justify-content-center">
          <div class="display-4 mb-3">💊</div>
          <h5 class="card-title fw-bold">Medicamentos</h5>
          <p class="card-text text-muted small">Gestiona catálogo de medicinas y kardex</p>
        </div>
      </div>
    </div>

    <!-- Card: Reportes -->
    <div class="col-md-6 col-lg-3">
      <div class="card h-100 shadow-sm border-0 hover-card" id="btn-reportes">
        <div class="card-body text-center d-flex flex-column justify-content-center">
          <div class="display-4 mb-3">📊</div>
          <h5 class="card-title fw-bold">Reportes</h5>
          <p class="card-text text-muted small">Visualiza reportes y prioridades</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Reportes de prioridades -->
<div class="modal fade" id="modal-report" tabindex="-1" aria-labelledby="modal-titulo" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h1 class="modal-title fs-5" id="modal-titulo">DATOS REGISTRADOS</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" method="post" class="p-1" id="form-reporte">
          <div class="row">
            <div class="col-6">
              <label><h6><sub>Datos Generales</sub></h6></label>
              <div class="row">
                <div class="col-6" title="Puede buscar al paciente por número de documento o nombre">
                  <label for="dni-request">Paciente</label>
                  <input type="text" name="dni-request" id="dni-request" class="form-control" placeholder="">
                </div>
                <div class="col-6">
                  <label for="user-request">Usuario Prioritaria</label>
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
              <label><h6><sub>Fecha Solicitud</sub></h6></label>
              <div class="row">
                <div class="col-6">
                  <label for="checkin-start">Desde</label>
                  <input type="datetime-local" name="checkin-start" id="checkin-start" class="form-control" step="any">
                </div>
                <div class="col-6">
                  <label for="checkin-end">Hasta</label>
                  <input type="datetime-local" name="checkin-end" id="checkin-end" class="form-control" step="any">
                </div>
              </div>
            </div>
            <div class="col-6">
              <label><h6><sub>Fecha Comentario</sub></h6></label>
              <div class="row">
                <div class="col-6">
                  <label for="checkout-start">Desde</label>
                  <input type="datetime-local" name="checkout-start" id="checkout-start" class="form-control">
                </div>
                <div class="col-6">
                  <label for="checkout-end">Hasta</label>
                  <input type="datetime-local" name="checkout-end" id="checkout-end" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-6">
              <label><h6><sub>Fecha Cita</sub></h6></label>
              <div class="row">
                <div class="col-6">
                  <label for="appointment-start">Desde</label>
                  <input type="datetime-local" name="appointment-start" id="appointment-start" class="form-control">
                </div>
                <div class="col-6">
                  <label for="appointment-end">Hasta</label>
                  <input type="datetime-local" name="appointment-end" id="appointment-end" class="form-control">
                </div>
              </div>
            </div>
            <div class="row-inline m-1">
              <div>
                <button type="button" class="btn btn-sm btn-success m-1 float-end" onclick="showReportCard()">Buscar</button>
              </div>
              <div>
                <button type="button" class="btn btn-sm btn-secondary m-1 float-end" id="btn-limpiar-reporte">Limpiar</button>
              </div>
            </div>
          </div>
        </form>
        <hr class="w-100">
        <div class="container-fluid w-100">
          <table class="table table-lg table-striped table-responsive w-100" id="table-resumen">
            <thead class="table-light">
              <tr>
                <th>Recepción</th>
                <th>Hora Recepcion</th>
                <th>Respuesta</th>
                <th>Hora Respuesta</th>
                <th>Documento</th>
                <th>Paciente</th>
                <th>Enviado</th>
                <th>Ips</th>
                <th>Eps</th>
                <th>Rango</th>
                <th>Diagnostico</th>
                <th>Aprobado</th>
                <th>Fecha Cita</th>
                <th>Hora Cita</th>
                <th>Anexo 9</th>
                <th>Anexo 10</th>
                <th>Enviado a</th>
                <th>Comentario</th>
                <th>Comentario contrareferencia</th>
                <th>Usuario Registra</th>
                <th>Dias hasta la respuesta</th>
                <th>Tiempo hasta la respuesta</th>
                <th>Dias hasta la cita</th>
                <th>Tiempo hasta la cita</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="../Js/datatables/jquery.dataTables.min.js"></script>
<script src="../Js/datatables/dataTables.bootstrap5.min.js"></script>
<script src="../Js/datatables/dataTables.buttons.min.js"></script>
<script src="../Js/datatables/buttons.html5.min.js"></script>
<script src="../Js/datatables/buttons.print.min.js"></script>
<script src="../Js/dashboard.js"></script>
<?php include './generales/footer.php'; ?>
