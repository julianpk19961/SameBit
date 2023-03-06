<!-- Llamado para encabezado de la página -->
<?php
include './generales/header.php';
?>

<meta name="viewport" content="width=device-width, initial-scale=1" http-equiv="content-type" content="text/html; charset=UTF-8">
<header class="d-flex flex-wrap justify-content-left py-3 mb-4 border-bottom">
  <a href="/" class="d-flex align-items-center mb-2 mb-md-0 me-md-auto text-dark text-decoration-none">
    <img src="../img/SameinLogo.png" height="40" class="logo">
  </a>

  <nav>
    <ul class="nav nav-pills">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle active" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Samebit
        </a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="./dashboard.php">Registro Novedades</a></li>
          <li><a class="dropdown-item" id="reportSamebitModal">Reportes</a></li>
        </ul>

      </li>
      <!-- <li class="nav-item"><a href="./asisttop.php" class="nav-link" aria-current="page">Asist</a></li>
      <li class="nav-item"><a href="./medicines_l.php" class="nav-link" aria-current="page">Samecomed</a></li> -->
      <li class="nav-item"><a href="../config/logout.php" class="nav-link link-secondary">Cerrar Sesión</a></li>
    </ul>
  </nav>
</header>

<body>
  <h4 class="text-uppercase text-center w-100 text-primary">BIENVENIDO <?php echo $_SESSION['usuario']; ?>
    <!-- <h6 class="text-center w-100 m-0"><sub>No olvide registrar todas las solicitudes recibidas</sub></h6> -->
  </h4>

  <section id="priorit" class="h-50">
    <form id="bitregister" accept-charset="UTF-8" method="POST">
      <div class="container-fluid py-1 h-50">
        <div class="row d-flex justify-content-center align-items-center h-50">
          <div class="col">
            <div class="card card-registration my-4">
              <div class="row g-0">
                <div class="col-sm-7">
                  <div class="card-body pt-5 text-black">
                    <div class="alert alert-light border" role="alert" id="refIn">
                      <strong class="text-muted text-middle">INFORMACIÓN PACIENTE</strong>
                      <hr>
                      <div class="form-group">

                        <div class="row">

                          <div class="col-md-6 mb-4">
                            <input id="pk_uuid" name="pk_uuid" type="hidden">
                            <!-- TIPO DNI -->
                            <div class="form-outline">
                              <label class="form-label" for="documenttype">TIPO IDENTIFICACIÓN*</label>
                              <Select required type="text" class="form-control form-control-lg" name="documenttype" id="documenttype">
                                <option value='11'>Registro Civil de nacimiento</option>
                                <option value="12">Tarjeta Identidad</option>
                                <option value="13" selected>Cedula de ciudadanía</option>
                                <option value="21">Tarjeta de extranjería</option>
                                <option value="22">Cédula de extranjería</option>
                                <option value="31">NIT</option>
                                <option value="41">Pasaport</option>
                                <option value="42">Tipo Documento extranjero</option>
                                <option value="43">No definido por la DIAN</option>
                              </Select>
                            </div>
                          </div>

                          <div class="col-md-6 mb-4">
                            <div class="form-outline">
                              <label class="form-label" for="dni">IDENTIFICACIÓN*</label>
                              <input required type="number" id="dni" name="dni" class="form-control form-control-lg" autofocus />
                            </div>
                          </div>


                          <div class="col-md-6 mb-4">
                            <!-- NOMBRES -->
                            <div class="form-outline">
                              <label class="form-label" for="nombre">NOMBRES*</label>
                              <input required type="text" id="nombre" name="nombre" class="form-control form-control-lg" />
                            </div>
                          </div>
                          <div class="col-md-6 mb-4">
                            <!-- APELLIDOS -->
                            <div class="form-outline">
                              <label class="form-label" for="apellido">APELLIDOS*</label>
                              <input required type="text" id="apellido" name="apellido" class="form-control form-control-lg" />
                            </div>
                          </div>
                        </div>

                        <div class="row">

                          <div class="col-md-6 mb-4">
                            <!-- IPS -->
                            <div class="form-outline">
                              <label class="form-label" for="ips">IPS*</label>
                              <Select required type="text" class="form-control form-control-lg" name="ips" id="ips">
                              </Select>
                            </div>
                          </div>

                          <div class="col-md-6 mb-4">
                            <!-- EPS -->
                            <div class="form-outline">
                              <label class="form-label" for="Eps">EPS*</label>
                              <Select required type="text" class="form-control form-control-lg" name="Eps" id="Eps">
                              </Select>
                            </div>
                          </div>

                          <div class="col-md-6 mb-4">
                            <!-- Rango EPS -->
                            <div class="form-outline">
                              <label class="form-label" for="EpsClassification">RANGO*</label>
                              <Select required type="text" class="form-control form-control-lg" name="EpsClassification" id="EpsClassification">
                                <option disabled selected>Seleccione una opción</option>
                                <option value='0'>A</option>
                                <option value="1">B</option>
                                <option value="2">C</option>
                                <option value="3">Sisben</option>
                              </Select>
                            </div>
                          </div>

                          <div class="col-md-6 mb-4">
                            <div class="form-outline">
                              <!-- Estado en la eps -->
                              <label class="form-label" for="EpsStatus">ACTIVO*</label>
                              <Select required type="text" class="form-control form-control-lg" name="EpsStatus" id="EpsStatus">
                                <option disabled selected>Seleccione una opción</option>
                                <option value='0'>NO</option>
                                <option value='1'>SI</option>
                              </Select>
                            </div>
                          </div>
                          <div class="col-md-6 mb-4">
                            <div class="form-outline">
                              <!-- TIPO DE CONTACTO -->
                              <label class="form-label" for="contacttype">TIPO CONTACTO*</label>
                              <Select required type="text" class="form-control form-control-lg" name="contacttype" id="contacttype">
                                <option value='llamada'>Llamada</option>
                                <option value="correo" selected>Correo</option>
                              </Select>

                            </div>
                          </div>
                          <div class="col-md-6 mb-4">
                            <div class="form-outline">
                              <!-- ACEPTACION PACIENTE -->
                              <label class="form-label" for="approved">APROBADO*</label>
                              <Select required type="text" class="form-control form-control-lg" name="approved" id="approved">
                                <option disabled selected>Seleccione una opción</option>
                                <option value="0">NO</option>
                                <option value='1'>SI</option>
                              </Select>
                            </div>
                          </div>
                          <div class="alert alert-success border" role="alert">
                            <!-- <div class="alert alert-secondary border"> -->
                            <strong class="text-muted">REFERENCIA</strong>
                            <hr>
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-outline">
                                  <label class="form-label" for="diagnosis">DIAGNÓSTICO*</label>
                                  <Select type="text" class="form-control form-control-lg" name="diagnosis" id="diagnosis" required>
                                  </Select>
                                </div>
                              </div>
                              <!-- Numero de llamadas -->
                              <div class="col-md-6">
                                <div class="form-outline">
                                  <label class="form-label" for="CallNumber">NUMERO: <span class="switchTitle">CORREO</span>S</label>
                                  <input type="number" id="CallNumber" name="CallNumber" class="form-control form-control-lg" />
                                </div>
                              </div>
                              <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                  <!-- DATOS COMUNICACION-->
                                  <label class="form-label" for="check-in-date">FECHA SOLICITUD* </label>
                                  <input type="datetime-local" id="check-in-date" name="check-in-date" class="comunication_in form-control form-control-lg" required />
                                </div>
                              </div>
                              <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                  <!-- DATOS DE ATENCIÓN -->
                                  <label class="form-label" for="CommentDate">FECHA COMENTARIO*</label>
                                  <input type="datetime-local" id="CommentDate" name="CommentDate" class="comunication_out form-control form-control-lg" required />
                                </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                  <!-- FECHA ATENCION -->
                                  <label class="form-label" for="attention-date">FECHA CITA</label>
                                  <input type="datetime-local" id="attention-date" name="attention-date" class="comunication_out form-control form-control-lg" />
                                </div>
                              </div>
                              <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                  <!-- Estado en la eps -->
                                  <label class="form-label" for="exhibitNine">¿TIENE DILIGENCIADO EL ANEXO 9?</label>
                                  <Select class="form-control form-control-lg col-6" type="text" id="exhibitNine" name="exhibitNine" value="false">
                                    <option disabled="disabled" selected>Seleccione una opción</option>
                                    <option value='0'>NO</option>
                                    <option value='1'>SI</option>
                                  </Select>
                                </div>
                              </div>


                              <!-- REMISIÓN  -->
                              <div class="form-outline">
                                <div class="row">
                                  <div class="form-outline">
                                    <label class="form-label" for="SentBy">REMITIDO DESDE: *</label>
                                    <input required type="text" id="SentBy" name="SentBy" class="form-control form-control-lg" />
                                  </div>
                                </div>
                                <div class="form-outline mb-2">
                                  <div class="form-outline">
                                    <label class="form-label" for="ObservationIn">OBSERVACIÓN*</label>
                                    <input required type="textarea" id="ObservationIn" name="ObservationIn" class="form-control form-control-lg" />
                                  </div>
                                </div>
                              </div>

                              <!-- Observaciones -->
                            </div>
                          </div>
                          <div class="alert alert-warning border" role="alert" id="refOut">
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
                                          <!-- Estado en la eps -->
                                          <label class="form-label" for="sendTo">REMITIDO A: *</label>
                                          <input required type="text" id="sendTo" name="sendTo" class="form-control form-control-lg" />
                                        </div>
                                      </div>

                                      <div class="col-md-5 mb-4">
                                        <div class="form-outline">
                                          <!-- Estado en la eps -->
                                          <label class="form-label" for="exhibitTen">¿TIENE DILIGENCIADO EL ANEXO 10?</label>
                                          <Select class="form-control form-control-lg col-6" type="text" id="exhibitTen" name="exhibitTen" value="false">
                                            <option disabled="disabled" selected>Seleccione una opción</option>
                                            <option value='0'>NO</option>
                                            <option value='1'>SI</option>
                                          </Select>
                                        </div>
                                      </div>

                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="form-outline mb-2">
                                <div class="form-outline">
                                  <label class="form-label" for="ObservationOut">OBSERVACIÓN*</label>
                                  <input required type="textarea" id="ObservationOut" name="ObservationOut" class="form-control form-control-lg" />
                                </div>
                              </div>

                            </div>
                          </div>
                        </div>

                        <!-- Contra referencia -->
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
                    <!-- Tabla de busquedas -->
                    <div class="row md-12 border rounded-2" id="search-patients">
                      <div class="container">
                        <h4 class="text-uppercase text-center text-muted mt-2">PACIENTES REGISTRADOS
                          <hr>
                        </h4>
                        <div class="m-1">
                          <table class="table table-striped table-bordered w-100" id="table-patients">
                            <thead class="thead-light">
                              <tr>
                                <th class="table-primary" hidden>KP_UUID</th>
                                <th class="table-primary">Documento</th>
                                <th class="table-primary">Paciente </th>
                                <th class="table-primary"></th>
                              </tr>
                            </thead>
                            <tbody id="patients">
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <!-- Tabla de historico -->
                    <div class="row md-12 border rounded-2" id="history-patient">
                      <h4 class="text-uppercase text-center text-muted mt-2">HISTORIAL - PRIORITARIA
                        <hr>
                      </h4>
                      <hr>
                      <div class="m-1">

                        <table class="table table-bordered" id="prioritarie-history">
                          <thead class="thead-light">
                            <tr>
                              <th class="table-primary"> Fecha</th>
                              <th class="table-primary"> Hora </th>
                              <th class="table-primary"> Atiende </th>
                              <th class="table-primary"> Observación </th>
                            </tr>
                          </thead>
                          <tbody id="patienshistory">
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
  <div class="modal fade" id="modal-report" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h1 class="modal-title fs-5" id="modalTitle">DATOS REGISTRADOS</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <form action="" method="post" class="p-1" id="getInformation">

            <div class="row">

              <div class="col-6">
                <label for="checkin-start">
                  <h6><sub>Datos Generales</sub></h6>
                </label>
                <div class="row">
                  <div class="col-6" title="Puede buscar al paciente por núemero de documento o nombre">
                    <labe for="dni-request">Paciente</labe>
                    <input type="text" name="dni-request" id="dni-request" class="form-control col-6" placeholder="" aria-describedby="helpId">
                  </div>
                  <div class="col-6">
                    <label for="user-request">Usuario Prioritaria</label>
                    <select type="text" name="user-request" id="user-request" class="form-control col-6" placeholder="" aria-describedby="helpId">
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
                  <h6><sub>Fecha Solicitúd</sub></h6>
                </label>
                <div class="row">

                  <div class="col-6">
                    <labe for="checkin-start">Desde</labe>
                    <input type="datetime-local" name="checkin-start" id="checkin-start" class="form-control col-6" placeholder="" step="any">
                  </div>

                  <div class="col-6">
                    <label for="checkin-end">Hasta</label>
                    <input type="datetime-local" name="checkin-end" id="checkin-end" class="form-control col-6" placeholder="" step="any">
                  </div>

                </div>
              </div>

              <div class="col-6">
                <label for="checkOut-start">
                  <h6><sub>Fecha Comentario</sub></h6>
                </label>
                <div class="row">
                  <div class="col-6">
                    <labe for="checkOut-start">Desde</labe>
                    <input type="datetime-local" name="checkOut-start" id="checkOut-start" class="form-control col-6" placeholder="" aria-describedby="helpId">
                  </div>
                  <div class="col-6">
                    <label for="checkOut-end">Hasta</label>
                    <input type="datetime-local" name="checkOut-end" id="checkOut-end" class="form-control col-6" placeholder="" aria-describedby="helpId">
                  </div>
                </div>
              </div>

              <div class="col-6">
                <label for="appointment-start">
                  <h6><sub>Fecha Cita</sub></h6>
                </label>
                <div class="row">
                  <div class="col-6">
                    <labe for="appointment-start">Desde</labe>
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
                  <button type="button" class="btn btn-sm btn-secondary m-1 float-end" id="cleanRequest">Limpiar</button>
                </div>
              </div>
            </div>


          </form>
          <hr class="w-100">
          <!-- --FIN DEL FORMULARIO -->
          <div class="container-fluid">
            <table class="table table-lg table-striped table-responsive" id="recordsSummary">
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
                  <th>dias hasta la respuesta</th>
                  <th>tiempo hasta la respuesta (sumar con los dias)</th>
                  <th>dias hasta la cita</th>
                  <th>tiempo hasta la cita (sumar con los dias)</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Recepción </th>
                  <th>Hora Recepcion</th>
                  <th>Respuesta </th>
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
                  <th>dias hasta la respuesta</th>
                  <th>tiempo hasta la respuesta (sumar con los dias)</th>
                  <th>dias hasta la cita</th>
                  <th>tiempo hasta la cita (sumar con los dias)</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>
  </div>
  </div>



</body>

<script src="../Js/dashboard.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>
</body>