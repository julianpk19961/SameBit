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

  <section id="priorit" class="h-50">
    <form id="bitregister" accept-charset="UTF-8" method="POST">
      <div class="container-fluid py-1 h-50">
        <div class="row d-flex justify-content-center align-items-center h-50">
          <div class="col">
            <div class="card card-registration my-4">
              <div class="row g-0">
                <div class="col-sm-6">
                  <div class="card-body pt-5 text-black">
                    <h4 class="mb-5 text-uppercase">BIENVENIDO <?php echo $_SESSION['usuario']; ?>,<br> INGRESE LOS DATOS DEL PACIENTE
                      <hr>
                    </h4>
                    <div class="row">
                      <div class="col-md-6 mb-4">
                        <!-- NUMERO DNI -->
                        <div class="form-outline">
                          <label class="form-label" for="Dni">IDENTIFICACIÓN*</label>
                          <input required type="number" id="Dni" name="Dni" class="form-control form-control-lg" list='patientslist' />
                          <datalist id='patientslist' name="patientslist">
                          </datalist>
                        </div>
                      </div>
                      <div class="col-md-6 mb-4">
                        <input id="PK_UUID" name="PK_UUID" type="hidden">
                        <!-- TIPO DNI -->
                        <div class="form-outline">
                          <label class="form-label" for="documenttype">TIPO IDENTIFICACIÓN*</label>
                          <!-- <input type="text" id="form3Example1m" class="form-control form-control-lg" /> -->
                          <Select required type="text" class="form-control form-control-lg" name="documenttype" id="documenttype">
                            <option value=''>Elija una opción</option>
                            <option value='11'>Registro Civil de nacimiento</option>
                            <option value="12">Tarjeta Identidad</option>
                            <option value="13">Cedula de ciudadanía</option>
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
                        <div class="form-outline">
                          <!-- TIPO DE CONTACTO -->
                          <label class="form-label" for="contacttype">TIPO CONTACTO*</label>
                          <Select required type="text" class="form-control form-control-lg" name="contacttype" id="contacttype">
                            <option value=''>Elija una opción</option>
                            <option value='0'>Llamada</option>
                            <option value="1">Correo</option>
                          </Select>

                        </div>
                      </div>
                      <div class="col-md-6 mb-4">
                        <div class="form-outline">
                          <!-- FECHA COMENTARIO -->
                          <label class="form-label" for="CommentDate">FECHA COMENTARIO*</label>
                          <input required type="date" id="CommentDate" name="CommentDate" class="form-control form-control-lg" />
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-4">
                        <div class="form-outline">
                          <!-- HORA COMENTARIO -->
                          <label class="form-label" for="CommentTime">HORA COMENTARIO*</label>
                          <input required type="time" id="CommentTime" name="CommentTime" class="form-control form-control-lg" />
                        </div>
                      </div>
                      <div class="col-md-6 mb-4">
                        <div class="form-outline">
                          <!-- ACEPTACION PACIENTE -->
                          <label class="form-label" for="approved">APROBADO*</label>
                          <Select required type="text" class="form-control form-control-lg" name="approved" id="approved">
                            <option value=''>Elija una opción</option>
                            <option value='1'>Sí</option>
                            <option value="2">No</option>
                          </Select>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-4">
                        <div class="form-outline">
                          <!-- FECHA ATENCION -->
                          <label class="form-label" for="AtentionDate">FECHA ATENCIÓN</label>
                          <input type="date" id="AtentionDate" name="AtentionDate" class="form-control form-control-lg" />
                        </div>
                      </div>
                      <div class="col-md-6 mb-4">
                        <div class="form-outline">
                          <!-- HORA ATENCION -->
                          <label class="form-label" for="AtentionTime">HORA ATENCIÓN</label>
                          <input required type="time" id="AtentionTime" name="AtentionTime" class="form-control form-control-lg" />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-4">
                        <!-- IPS -->
                        <div class="form-outline">
                          <label class="form-label" for="Ips">IPS*</label>
                          <Select required type="text" class="form-control form-control-lg" name="Ips" id="Ips">
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
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-4">
                        <!-- Rango EPS -->
                        <div class="form-outline">
                          <label class="form-label" for="EpsClassification">RANGO*</label>
                          <Select required type="text" class="form-control form-control-lg" name="EpsClassification" id="EpsClassification">
                            <option value=''>Elija una opción</option>
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
                            <option value=''>Elija una opción</option>
                            <option value='1'>Sí</option>
                            <option value='0'>No</option>
                          </Select>
                        </div>
                      </div>
                      <!-- Diagnostico aplicado a paciente -->
                      <div class="col-md-6 mb-4">
                        <div class="form-outline">
                          <label class="form-label" for="diagnosis">DIAGNÓSTICO*</label>
                          <Select required type="text" class="form-control form-control-lg" name="diagnosis" id="diagnosis">
                          </Select>
                        </div>
                      </div>
                      <!-- Numero de llamadas -->
                      <div class="col-md-6 mb-4">
                        <div class="form-outline">
                          <label class="form-label" for="CallNumber">NÚMERO LLAMADAS</label>
                          <input type="number" id="CallNumber" name="CallNumber" class="form-control form-control-lg" />
                        </div>
                      </div>
                      <!-- REMISIÓN  -->
                      <div class="form-outline mb-2">
                        <div class="form-outline">
                          <label class="form-label" for="SentBy">REMITIDO DESDE EPS*</label>
                          <input required type="text" id="SentBy" name="SentBy" class="form-control form-control-lg" />
                        </div>
                      </div>
                    </div>
                    <!-- Observaciones -->
                    <div class="row">
                      <div class="form-outline mb-2">
                        <div class="form-outline">
                          <label class="form-label" for="Observation">OBSERVACIÓN*</label>
                          <input required type="textarea" id="Observation0" name="Observation0" class="form-control form-control-lg" />
                        </div>
                      </div>
                    </div>

                    <div class="d-flex justify-content-end pt-3">
                      <button type="button" class="bit-clean btn btn-light btn-lg">Limpiar Formulario</button>
                      <button id="bit-submit" type="submit" class="bit-submit btn btn-warning btn-lg ms-2">Enviar</button>
                    </div>

                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="card-body pt-5 text-black">
                    <!-- Tabla de busquedas -->
                    <div class="row md-12" id="search-patients">
                      <h4 class="mb-5 text-uppercase">PACIENTES REGISTRADOS
                        <hr>
                      </h4>
                      <table class="table table-striped table-bordered" id="table-patients">
                        <thead class="thead-light">
                          <tr>
                            <th class="table-primary " style="display:none;"></th>
                            <th class="table-primary"> Documento</th>
                            <th class="table-primary"> Paciente </th>
                            <th class="table-primary"> </th>
                          </tr>
                        </thead>
                        <tbody id="patients">
                        </tbody>
                      </table>
                    </div>
                    <!-- Tabla de historico -->
                    <div class="row md-12" id="history-patient">
                      <h4 class="mb-5 text-uppercase">HISTORICO DEL PACIENTE
                        <hr>
                      </h4>
                      <table class="table table-bordered">
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
          <h1 class="modal-title fs-5" id="modalTitle">Datos registrados</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table class="table table-lg table-striped table-responsive" id="recordsSummary">
            <thead class="table-light">
              <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Documento</th>
                <th>Paciente</th>
                <th>Enviado</th>
                <th>Ips</th>
                <th>Eps</th>
                <th>Rango</th>
                <th>Diagnostico</th>
                <th>Estado</th>
                <th>Cita</th>
                <th>Recibe</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Documento</th>
                <th>Paciente</th>
                <th>Enviado</th>
                <th>Ips</th>
                <th>Eps</th>
                <th>Rango</th>
                <th>Diagnostico</th>
                <th>Estado</th>
                <th>Cita</th>
                <th>Recibe</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-danger">PDF</button>
        </div>
      </div>
    </div>
  </div>



</body>

<script src="../Js/dashboard.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
</body>