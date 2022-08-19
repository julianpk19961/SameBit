<!-- Llamado para encabezado de la página -->
<?php 
include './generales/header.php';
?>

<header class="d-flex flex-wrap justify-content-left py-3 mb-4 border-bottom">
      <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
        <img src="../img/SameinLogo.png" height="40" class="logo">
      </a>
      <ul class="nav nav-pills">
        <li class="nav-item"><a href="./dashboard.php" class="nav-link active" aria-current="page">Inicio</a></li>
        <li class="nav-item"><a href="../pages/pacients.php" class="nav-link">Pacientes</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Diagnosticos</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Tipos</a></li>
        <li class="nav-item"><a href="../config/logout.php" class="nav-link">Cerrar Sesión</a></li>
      </ul>
  </header>
  <section class="h-100">
    <form id="bitregister">
      <div class="container py-1 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col">
            <div class="card card-registration my-4">
              <div class="row g-0"> 
                <div class="col-xl-6">
                  <div class="card-body p-md-5 text-black">
                    <h4 class="mb-5 text-uppercase">BIENVENIDO, INGRESE LOS DATOS DEL PACIENTE <hr></h4>
                    <div class="row">
                      <div class="col-md-6 mb-4">
                        <input id="PK_UUID" name="PK_UUID" type="hidden">
                        <!-- TIPO DNI -->
                        <div class="form-outline">
                          <label class="form-label" for="documenttype">Tipo Identificación</label>
                          <!-- <input type="text" id="form3Example1m" class="form-control form-control-lg" /> -->
                          <Select type="text" class="form-control form-control-lg" name="documenttype" id="documenttype" >
                            <option value='0'>Elija una opción</option>
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
                        <!-- NUMERO DNI -->
                        <div class="form-outline">
                          <label class="form-label" for="Dni">Identificación</label>
                          <input type="number" id="Dni" name="Dni" class="form-control form-control-lg" list='patientslist'/>
                          <datalist id='patientslist' name="patientslist">
                            
                          </datalist>
                        </div>
                      </div>
                      <div class="col-md-6 mb-4">
                        <!-- NOMBRES -->
                        <div class="form-outline">
                        <label class="form-label" for="nombre">Nombres</label>
                        <input type="text" id="nombre" name="nombre" class="form-control form-control-lg" />
                        </div>
                      </div>
                      <div class="col-md-6 mb-4">
                        <!-- APELLIDOS -->
                        <div class="form-outline">
                          <label class="form-label" for="apellido">Apellidos</label>
                          <input type="text" id="apellido" name="apellido" class="form-control form-control-lg" />
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-4">
                        <div class="form-outline">
                          <!-- TIPO DE CONTACTO -->
                          <label class="form-label" for="contacttype">Tipo Contacto</label>
                          <Select type="text" class="form-control form-control-lg" name="contacttype" id="contacttype" >
                            <option value='0'>Elija una opción</option>
                            <option value='1'>Llamada</option>
                            <option value="2">Correo</option>
                          </Select>
                          
                        </div>
                      </div>
                      <div class="col-md-6 mb-4">
                        <div class="form-outline">
                          <!-- FECHA COMENTARIO -->
                          <label class="form-label" for="CommentDate">Fecha Comentario</label>
                          <input type="date" id="CommentDate" name="CommentDate" class="form-control form-control-lg" />
                        </div>              
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-4">
                        <div class="form-outline">
                          <!-- HORA COMENTARIO -->
                            <label class="form-label" for="CommentTime">Hora Comentario</label>
                            <input required type="time" id="CommentTime" name="CommentTime" class="form-control form-control-lg"/>
                        </div> 
                      </div>
                      <div class="col-md-6 mb-4">
                        <div class="form-outline"> 
                          <!-- ACEPTACION PACIENTE -->
                          <label class="form-label" for="approved">Aprobado</label>
                          <Select type="text" class="form-control form-control-lg" name="approved" id="approved" >
                            <option value='0'>Elija una opción</option>
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
                          <label class="form-label" for="AtentionDate">Fecha Atención</label>
                          <input type="date" id="AtentionDate" name="AtentionDate" class="form-control form-control-lg" />
                        </div>
                      </div>
                      <div class="col-md-6 mb-4">
                          <div class="form-outline">
                            <!-- HORA ATENCION -->
                            <label class="form-label" for="AtentionTime">Hora Atención</label>
                            <input required type="time" id="AtentionTime" name="AtentionTime" class="form-control form-control-lg"/>
                          </div> 
                        </div>
                    </div>
                    <!-- Observaciones -->
                    <div class="row">
                      <div class="form-outline mb-2">
                        <div class="form-outline">
                          <label class="form-label" for="Observation0">Observación #1</label>
                          <input required type="textarea" id="Observation0" name="Observation0" class="form-control form-control-lg"/>
                        </div>
                      </div>
                      <div class="form-outline mb-2">
                        <div class="form-outline">
                          <label class="form-label" for="Observation1">Observación #2</label>
                          <input required type="textarea" id="Observation1" name="Observation1" class="form-control form-control-lg"/>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-4">
                        <div class="form-outline">
                          <label class="form-label" for="Eps">EPS</label>
                          <Select type="text" class="form-control form-control-lg" name="Eps" id="Eps">
                          </Select>
                        </div>
                      </div>
                      <div class="col-md-6 mb-4">
                        <div class="form-outline">
                          <label class="form-label" for="Ips">IPS</label>
                          <Select type="text" class="form-control form-control-lg" name="Ips" id="Ips" >
                            <option value='0'>Elija una opción</option>
                            <option value='1'>Famisanar</option>
                            <option value="2">IPS2</option>
                          </Select> 
                        </div> 
                      </div>
                      <!-- REMISIÓN  -->
                      <div class="form-outline mb-2">
                        <div class="form-outline">
                          <label class="form-label" for="SentBy">Remitido Desde EPS</label>
                          <input type="text" id="SentBy" name="SentBy" class="form-control form-control-lg" />
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 mb-4">
                        <div class="form-outline">
                          <!-- Estado en la eps -->
                          <label class="form-label" for="EpsStatus">Activo</label>
                          <Select type="text" class="form-control form-control-lg" name="EpsStatus" id="EpsStatus" >
                            <option value=''>Elija una opción</option>
                            <option value='1'>Sí</option>
                            <option value='0'>No</option>
                          </Select>
                          
                          </div>
                        </div>
                      <div class="col-md-6 mb-4">
                          <div class="form-outline">
                          <label class="form-label" for="EpsClassification">Rango</label>  
                          <Select type="text" class="form-control form-control-lg" name="EpsClassification" id="EpsClassification" >
                            <option value=''>Elija una opción</option>
                            <option value='0'>A</option>
                            <option value="1">B</option>
                            <option value="1">C</option>
                            <option value="1">Sisben</option>
                          </Select>
                            
                        </div> 
                      </div>
                      <div class="col-md-6 mb-4">
                        <div class="form-outline">
                          <label class="form-label" for="CallNumber">Número Llamadas</label>
                          <input type="number" id="CallNumber" name="CallNumber" class="form-control form-control-lg" />  
                        </div> 
                      </div>
                      <div class="col-md-6 mb-4">
                        <div class="form-outline">
                        <label class="form-label" for="diagnosis">Diagnostico</label>
                          <Select type="text" class="form-control form-control-lg" name="diagnosis" id="diagnosis" >
                          </Select>
                        </div>
                      </div>
                    </div>

                    <div class="d-flex justify-content-end pt-3">
                      <button type="button" class="bit-clean btn btn-light btn-lg">Limpiar Formulario</button>
                      <button type="submit" class="bit-submmmit btn btn-warning btn-lg ms-2">Enviar</button>
                    </div>
                    
                  </div> 
                </div>

                <div class="col-xl-6">
                  <div class="card-body p-md-5 text-black">
                    <!-- Tabla de busquedas -->
                    <div class="row md-12" id="search-patients" >
                      <h4 class="mb-5 text-uppercase">PACIENTES REGISTRADOS<hr></h4>
                      <table class="table table-bordered">
                        <thead class="thead-light">
                          <tr>
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
                    <div class="row md-12" id="history-patient" >
                      <h4 class="mb-5 text-uppercase">HISTORICO DEL PACIENTE <hr></h4>
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th scope="col"></th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Atendido por</th>
                            <th scope="col">Comentario</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                          </tr>
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

<script src="../Js/dashboard.js"></script>