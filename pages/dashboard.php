<?php
include './generales/header.php';
?>

<header class="d-flex flex-wrap justify-content-between py-3 mb-4 border-bottom">
  <a href="/" class="d-flex align-items-center mb-2 mb-md-0 text-dark text-decoration-none">
    <img src="../img/logo.png" height="40" class="logo">
    <span class="ms-2 fw-bold text-primary"><?php echo htmlspecialchars($appName); ?></span>
  </a>

  <nav>
    <ul class="nav nav-pills">
      <li class="nav-item"><span class="nav-link text-muted">Bienvenido: <?php echo $_SESSION['usuario']; ?></span></li>
      <li class="nav-item"><a href="../config/logout.php" class="nav-link link-danger">Cerrar Sesión</a></li>
    </ul>
  </nav>
</header>

<!-- MENÚ PRINCIPAL DE NAVEGACIÓN -->
<div class="container-fluid mt-5">
  <div class="row mb-4">
    <div class="col-12">
      <h2 class="text-center text-primary fw-bold mb-4">Panel de Control</h2>
    </div>
  </div>

  <div class="row g-4 mb-5">
    <!-- Card 1: Registro de Novedades/Llamadas -->
    <div class="col-md-6 col-lg-3">
      <div class="card h-100 shadow-sm border-0 hover-card" style="cursor: pointer;" id="btn-new-record-card">
        <div class="card-body text-center">
          <div class="display-4 text-warning mb-3">📞</div>
          <h5 class="card-title fw-bold">Registro de Llamadas</h5>
          <p class="card-text text-muted small">Registra nuevas llamadas y novedades de pacientes</p>
          <button class="btn btn-warning btn-sm mt-3 w-100" id="btn-new-record">Ir →</button>
        </div>
      </div>
    </div>

    <!-- Card 2: Gestión de Medicamentos -->
    <div class="col-md-6 col-lg-3">
      <div class="card h-100 shadow-sm border-0 hover-card" style="cursor: pointer;">
        <div class="card-body text-center">
          <div class="display-4 text-success mb-3">💊</div>
          <h5 class="card-title fw-bold">Medicamentos</h5>
          <p class="card-text text-muted small">Gestiona catálogo de medicinas y kardex</p>
          <a href="./medicines_l.php" class="btn btn-success btn-sm mt-3 w-100">Ir →</a>
        </div>
      </div>
    </div>

    <!-- Card 3: Reportes -->
    <div class="col-md-6 col-lg-3">
      <div class="card h-100 shadow-sm border-0 hover-card" style="cursor: pointer;">
        <div class="card-body text-center">
          <div class="display-4 text-info mb-3">📊</div>
          <h5 class="card-title fw-bold">Reportes</h5>
          <p class="card-text text-muted small">Visualiza reportes y prioridades</p>
          <button class="btn btn-info btn-sm mt-3 w-100" id="btn-reportes">Ir →</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Estilos para las cards -->
<style>
.hover-card {
  transition: all 0.3s ease;
}
.hover-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
}
.display-4 {
  font-size: 3rem;
}
</style>

  <section id="section-registro" class="h-50" style="display:none">
    <div class="mb-3">
      <button class="btn btn-secondary btn-sm" id="btn-back-to-menu">
        <i class="bi bi-arrow-left"></i> Volver al Menú
      </button>
    </div>
    <form id="form-registro" accept-charset="UTF-8" method="POST">
      <div class="container-fluid py-1 h-50">
        <div class="row d-flex justify-content-center align-items-center h-50">
          <div class="col">
            <div class="card card-registration my-4">
              <div class="row g-0">
                <div class="col-sm-7">
                  <div class="card-body pt-5 text-black">
                    <div class="alert alert-light border" role="alert" id="ref-in">
                      <strong class="text-muted text-middle">INFORMACIÓN PACIENTE</strong>
                      <hr>
                      <div class="form-group">

                        <div class="row">

                          <div class="col-md-6 mb-4">
                            <input id="pk-uuid" name="pk-uuid" type="hidden">
                            <div class="form-outline">
                              <label class="form-label" for="document-type">TIPO IDENTIFICACIÓN*</label>
                              <select required class="form-control form-control-lg" name="document-type" id="document-type">
                                <option value='11'>Registro Civil de nacimiento</option>
                                <option value="12">Tarjeta Identidad</option>
                                <option value="13" selected>Cedula de ciudadanía</option>
                                <option value="21">Tarjeta de extranjería</option>
                                <option value="22">Cédula de extranjería</option>
                                <option value="31">NIT</option>
                                <option value="41">Pasaport</option>
                                <option value="42">Tipo Documento extranjero</option>
                                <option value="43">No definido por la DIAN</option>
                              </select>
                            </div>
                          </div>

                          <div class="col-md-6 mb-4">
                            <div class="form-outline">
                              <label class="form-label" for="dni">IDENTIFICACIÓN*</label>
                              <input required type="number" id="dni" name="dni" class="form-control form-control-lg" autofocus />
                            </div>
                          </div>

                          <div class="col-md-6 mb-4">
                            <div class="form-outline">
                              <label class="form-label" for="nombre">NOMBRES*</label>
                              <input required type="text" id="nombre" name="nombre" class="form-control form-control-lg" />
                            </div>
                          </div>
                          <div class="col-md-6 mb-4">
                            <div class="form-outline">
                              <label class="form-label" for="apellido">APELLIDOS*</label>
                              <input required type="text" id="apellido" name="apellido" class="form-control form-control-lg" />
                            </div>
                          </div>
                        </div>

                        <div class="row">

                          <div class="col-md-6 mb-4">
                            <div class="form-outline">
                              <label class="form-label" for="ips">IPS*</label>
                              <select required class="form-control form-control-lg" name="ips" id="ips">
                              </select>
                            </div>
                          </div>

                          <div class="col-md-6 mb-4">
                            <div class="form-outline">
                              <label class="form-label" for="eps">EPS*</label>
                              <select required class="form-control form-control-lg" name="eps" id="eps">
                              </select>
                            </div>
                          </div>

                          <div class="col-md-6 mb-4">
                            <div class="form-outline">
                              <label class="form-label" for="eps-classification">RANGO*</label>
                              <select required class="form-control form-control-lg" name="eps-classification" id="eps-classification">
                                <option disabled selected>Seleccione una opción</option>
                                <option value='0'>A</option>
                                <option value="1">B</option>
                                <option value="2">C</option>
                                <option value="3">Sisben</option>
                              </select>
                            </div>
                          </div>

                          <div class="col-md-6 mb-4">
                            <div class="form-outline">
                              <label class="form-label" for="eps-status">ACTIVO*</label>
                              <select required class="form-control form-control-lg" name="eps-status" id="eps-status">
                                <option disabled selected>Seleccione una opción</option>
                                <option value='0'>NO</option>
                                <option value='1'>SI</option>
                              </select>
                            </div>
                          </div>

                          <div class="col-md-6 mb-4">
                            <div class="form-outline">
                              <label class="form-label" for="contact-type">TIPO CONTACTO*</label>
                              <select required class="form-control form-control-lg" name="contact-type" id="contact-type">
                                <option value='0'>Llamada</option>
                                <option value="correo" selected>Correo</option>
                              </select>
                            </div>
                          </div>

                          <div class="col-md-6 mb-4">
                            <div class="form-outline">
                              <label class="form-label" for="approved">APROBADO*</label>
                              <select required class="form-control form-control-lg" name="approved" id="approved">
                                <option disabled selected>Seleccione una opción</option>
                                <option value="0">NO</option>
                                <option value='1'>SI</option>
                              </select>
                            </div>
                          </div>

                          <div class="alert alert-success border" role="alert">
                            <strong class="text-muted">REFERENCIA</strong>
                            <hr>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-outline">
                                  <label class="form-label" for="diagnosis">DIAGNÓSTICO*</label>
                                  <select class="form-control form-control-lg" name="diagnosis" id="diagnosis" required>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-outline">
                                  <label class="form-label" for="call-number">NUMERO: <span class="switchTitle">CORREO</span>S</label>
                                  <input type="number" id="call-number" name="call-number" class="form-control form-control-lg" />
                                </div>
                              </div>
                              <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                  <label class="form-label" for="check-in-date">FECHA SOLICITUD*</label>
                                  <input type="datetime-local" id="check-in-date" name="check-in-date" class="comunication_in form-control form-control-lg" required />
                                </div>
                              </div>
                              <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                  <label class="form-label" for="comment-date">FECHA COMENTARIO*</label>
                                  <input type="datetime-local" id="comment-date" name="comment-date" class="comunication_out form-control form-control-lg" required />
                                </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                  <label class="form-label" for="attention-date">FECHA CITA</label>
                                  <input type="datetime-local" id="attention-date" name="attention-date" class="comunication_out form-control form-control-lg" />
                                </div>
                              </div>
                              <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                  <label class="form-label" for="exhibit-nine">¿TIENE DILIGENCIADO EL ANEXO 9?</label>
                                  <select class="form-control form-control-lg col-6" id="exhibit-nine" name="exhibit-nine">
                                    <option disabled="disabled" selected>Seleccione una opción</option>
                                    <option value='0'>NO</option>
                                    <option value='1'>SI</option>
                                  </select>
                                </div>
                              </div>

                              <div class="form-outline">
                                <div class="row">
                                  <div class="form-outline">
                                    <label class="form-label" for="sent-by">REMITIDO DESDE: *</label>
                                    <input required type="text" id="sent-by" name="sent-by" class="form-control form-control-lg" />
                                  </div>
                                </div>
                                <div class="form-outline mb-2">
                                  <div class="form-outline">
                                    <label class="form-label" for="observation-in">OBSERVACIÓN*</label>
                                    <input required type="textarea" id="observation-in" name="observation-in" class="form-control form-control-lg" />
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="alert alert-warning border" role="alert" id="ref-out">
                            <strong class="text-muted">CONTRA-REFERENCIA</strong>
                            <hr>
                            <div class="form-group">
                              <br>
                              <div class="form-outline">
                                <div>
                                  <div class="form-outline">
                                    <div class="row">

                                      <div class="col-md-7 mb-4">
                                        <div class="form-outline">
                                          <label class="form-label" for="send-to">REMITIDO A: *</label>
                                          <input required type="text" id="send-to" name="send-to" class="form-control form-control-lg" />
                                        </div>
                                      </div>

                                      <div class="col-md-5 mb-4">
                                        <div class="form-outline">
                                          <label class="form-label" for="exhibit-ten">¿TIENE DILIGENCIADO EL ANEXO 10?</label>
                                          <select class="form-control form-control-lg col-6" id="exhibit-ten" name="exhibit-ten">
                                            <option disabled="disabled" selected>Seleccione una opción</option>
                                            <option value='0'>NO</option>
                                            <option value='1'>SI</option>
                                          </select>
                                        </div>
                                      </div>

                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="form-outline mb-2">
                                <div class="form-outline">
                                  <label class="form-label" for="observation-out">OBSERVACIÓN*</label>
                                  <input required type="textarea" id="observation-out" name="observation-out" class="form-control form-control-lg" />
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                      </div>

                      <div class="d-flex justify-content-end pt-3">
                        <button type="button" class="bit-clean btn btn-light btn-lg">Limpiar Formulario</button>
                        <button id="bit-submit" type="submit" class="bit-submit btn btn-success btn-lg ms-2">Enviar</button>
                      </div>

                    </div>
                  </div>

                </div>
                <div class="col-sm-5">
                  <div class="card-body pt-5 text-black">
                    <div class="row md-12 border rounded-2" id="search-patients">
                      <div class="container">
                        <h4 class="text-uppercase text-center text-muted mt-2">PACIENTES REGISTRADOS
                          <hr>
                        </h4>
                        <div class="m-1">
                          <table class="table table-striped table-bordered w-100" id="table-patients">
                            <thead class="thead-light">
                              <tr>
                                <th class="table-primary" hidden>UUID</th>
                                <th class="table-primary">Paciente</th>
                                <th class="table-primary">Documento</th>
                                <th class="table-primary"></th>
                              </tr>
                            </thead>
                            <tbody id="tbody-patients">
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="row md-12 border rounded-2" id="history-patient">
                      <h4 class="text-uppercase text-center text-muted mt-2">HISTORIAL - PRIORITARIA
                        <hr>
                      </h4>
                      <hr>
                      <div class="m-1">
                        <table class="table table-bordered" id="table-historial">
                          <thead class="thead-light">
                            <tr>
                              <th class="table-primary">Fecha</th>
                              <th class="table-primary">Hora</th>
                              <th class="table-primary">Atiende</th>
                              <th class="table-primary">Observación</th>
                            </tr>
                          </thead>
                          <tbody id="tbody-historial">
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </section>

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
                <label for="checkin-start">
                  <h6><sub>Datos Generales</sub></h6>
                </label>
                <div class="row">
                  <div class="col-6" title="Puede buscar al paciente por número de documento o nombre">
                    <label for="dni-request">Paciente</label>
                    <input type="text" name="dni-request" id="dni-request" class="form-control col-6" placeholder="" aria-describedby="helpId">
                  </div>
                  <div class="col-6">
                    <label for="user-request">Usuario Prioritaria</label>
                    <select name="user-request" id="user-request" class="form-control col-6" aria-describedby="helpId">
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
                <label for="checkin-start">
                  <h6><sub>Fecha Solicitud</sub></h6>
                </label>
                <div class="row">
                  <div class="col-6">
                    <label for="checkin-start">Desde</label>
                    <input type="datetime-local" name="checkin-start" id="checkin-start" class="form-control col-6" placeholder="" step="any">
                  </div>
                  <div class="col-6">
                    <label for="checkin-end">Hasta</label>
                    <input type="datetime-local" name="checkin-end" id="checkin-end" class="form-control col-6" placeholder="" step="any">
                  </div>
                </div>
              </div>

              <div class="col-6">
                <label for="checkout-start">
                  <h6><sub>Fecha Comentario</sub></h6>
                </label>
                <div class="row">
                  <div class="col-6">
                    <label for="checkout-start">Desde</label>
                    <input type="datetime-local" name="checkout-start" id="checkout-start" class="form-control col-6" placeholder="" aria-describedby="helpId">
                  </div>
                  <div class="col-6">
                    <label for="checkout-end">Hasta</label>
                    <input type="datetime-local" name="checkout-end" id="checkout-end" class="form-control col-6" placeholder="" aria-describedby="helpId">
                  </div>
                </div>
              </div>

              <div class="col-6">
                <label for="appointment-start">
                  <h6><sub>Fecha Cita</sub></h6>
                </label>
                <div class="row">
                  <div class="col-6">
                    <label for="appointment-start">Desde</label>
                    <input type="datetime-local" name="appointment-start" id="appointment-start" class="form-control col-6" placeholder="" aria-describedby="helpId">
                  </div>
                  <div class="col-6">
                    <label for="appointment-end">Hasta</label>
                    <input type="datetime-local" name="appointment-end" id="appointment-end" class="form-control col-6" placeholder="" aria-describedby="helpId">
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

  <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
  <script src="../Js/dashboard.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>
</body>
