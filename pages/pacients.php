<!DOCTYPE html>
<html lang="en">
  <head>
    
  <!-- <?php 
  include '../config/setup.php';
  session_start();

  $nombre = isset($_POST[$_SESSION['name']])?$_SESSION['name']:'';
  $id =isset($_POST[$_SESSION['id']])?$_SESSION['id']:'';
  ?> -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link href="./css/main.css" rel="stylesheet" type="text/css">
    <link href="./css/pacientes.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>

    <title>Pacientes</title>
  <!-- Barra de menu --> 
</head>

<body>
  <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
      <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">

        <img src="../img/SameinLogo.png" height="40" class="logo">
      </a>
       
      <ul class="nav nav-pills">
        <li class="nav-item"><a href="./dashboard.php" class="nav-link">Inicio</a></li>
        <li class="nav-item"><a href="#" class="nav-link active" >Pacientes</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Diagnosticos</a></li>
        <li class="nav-item"><a href="../pages/asisttop.php" class="nav-link">Asist-Top</a></li>
        <li class="nav-item"><a href="../config/logout.php" class="nav-link">Cerrar Sesión</a></li>
      </ul>
  </header>

  <section class="h-100 ">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col">
          <div class="card card-registration my-4">
            <div class="row g-0">
              <div class="col-xl-6 d-none d-xl-block">
                <!-- imagen de la portada -->
                <img src="../img/reg.jpg"
                  alt="Sample photo" class="img-fluid"
                  style="border-top-left-radius: .25rem; border-bottom-left-radius: .25rem;" />
              </div>
              <div class="col-xl-6">
                <div class="card-body p-md-5 text-black">
                  <h3 class="mb-5 text-uppercase">Registro Paciente: </h3>

                  <div class="row">
                    <div class="col-md-6 mb-4">
                      <!-- tipo identificación -->
                        <div class="form-outline">
                          <!-- <input type="text" id="form3Example1m" class="form-control form-control-lg" /> -->
                          <Select type="text" class="form-control form-control-lg" id="identification" >
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
                          <label class="form-label" for="form3Example1m">Tipo Identificación</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                      <!-- Numero de identificación -->
                        <div class="form-outline">
                          <input type="text" id="form3Example1m" class="form-control form-control-lg" />
                          <label class="form-label" for="form3Example1m">Identificación</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                      <!-- nombres -->
                      <div class="form-outline">
                        <input type="text" id="form3Example1m" class="form-control form-control-lg" />
                        <label class="form-label" for="form3Example1m">Nombres</label>
                      </div>
                    </div>
                    <div class="col-md-6 mb-4">
                      <!-- apellidos -->
                      <div class="form-outline">
                        <input type="text" id="form3Example1n" class="form-control form-control-lg" />
                        <label class="form-label" for="form3Example1n">Apellidos</label>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6 mb-4">
                      <div class="form-outline">
                        <!-- estado del paciente -->
                        <Select type="text" class="form-control form-control-lg" id="z_xOne" >
                            <option value='0'>Elija una opción</option>
                            <option value='1'>Activo</option>
                            <option value="2">Inactivo</option>
                        </Select>
                        <label class="form-label" for="form3Example1m">Estado</label>  
                      </div>
                    </div>
                    <div class="col-md-6 mb-4">
                      <!-- Genero del paciente -->
                        <Select type="text" class="form-control form-control-lg" id="gender" >
                            <option value='0'>Elija una opción</option>
                            <option value='1'>Mujer</option>
                            <option value="2">Hombre</option>
                            <option value="2">Otro...</option>
                        </Select>
                        <label class="form-label" for="form3Example1m1">Genero</label>
                      </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6 mb-4">
                      <div class="form-outline">
                        <input type="date" id="form3Example8" class="form-control form-control-lg" />
                        <label class="form-label" for="form3Example8">Fecha Nacimiento</label>
                      </div>
                    </div>
                    <div class="col-md-6 mb-4">
                      <input type="number" max="1" max="120" id="form3Example8" class="form-control form-control-lg" />
                      <label class="form-label" for="form3Example8">Edad</label>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6 mb-4">
                      <Select type="text" class="form-control form-control-lg" id="borncity" >
                              <option value='0'>Elija una opción</option>
                              <option value='1'>Bucaramanga</option>
                              <option value="2">Medellín</option>
                              <option value="2">Calí</option>
                              <option value='0'>Bógota</option>
                              <option value='1'>Villavicencio</option>
                              <option value="2">Mocoa</option>
                              <option value="2">Neiva</option>
                        </Select>
                      <label class="form-label" for="form3Example8">Lugar Nacimiento</label>
                    </div>
                    <!-- <div class="col-md-6 mb-4">
                      <Select type="text" class="form-control form-control-lg" id="civilstatus" >
                              <option value='0'>Elija una opción</option>
                              <option value='1'>Soltero</option>
                              <option value="2">Casado</option>
                              <option value="2">Únion Libre</option>
                              <option value='0'>Divorciado</option>
                              <option value='1'>Viudo</option>
                        </Select>
                      <label class="form-label" for="form3Example8">Estado Civil</label>
                    </div> -->
                    <div class="col-md-6 mb-4">
                      <input type="number" id="form3Example9" class="form-control form-control-lg" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"/>
                      <label class="form-label" for="form3Example8">Teléfono Fijo</label>
                    </div>
                    <div class="col-md-6 mb-4">
                      <input type="number" id="form3Example9" class="form-control form-control-lg" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"/>
                      <label class="form-label" for="form3Example8">Celular</label>
                    </div>
                    <div class="col-md-6 mb-4">
                    <input type="email" id="form3Example90" class="form-control form-control-lg" />
                    <label class="form-label" for="form3Example90">Correo Electronico</label>
                  </div>
                  </div>

                  <!-- <div class="row"> -->
                    <!-- <div class="col-md-6 mb-4">
                      <Select type="text" class="form-control form-control-lg" id="civilstatus" >
                                <option value='0'>Elija una opción</option>
                                <option value='1'>Prescolar</option>
                                <option value="2">Primaria</option>
                                <option value="2">Secundaria</option>
                                <option value='0'>Pregrado</option>
                                <option value='1'>Posgrado</option>
                          </Select>
                        <label class="form-label" for="form3Example8">Nivel Educativo</label>
                    </div> -->
                    <!-- <div class="col-md-6 mb-4">
                      <input type="number" id="form3Example9" class="form-control form-control-lg" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"/>
                      <label class="form-label" for="form3Example8">Teléfono Fijo</label>
                    </div>
                    <div class="col-md-6 mb-4">
                      <input type="number" id="form3Example9" class="form-control form-control-lg" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"/>
                      <label class="form-label" for="form3Example8">Celular</label>
                    </div>
                  </div> -->

                  <div class="form-outline mb-4">
                    <input type="text" id="form3Example9" class="form-control form-control-lg" />
                    <label class="form-label" for="form3Example9">Dirección</label>
                  </div>


                  <div class="d-flex justify-content-end pt-3">
                    <button type="button" class="btn btn-light btn-lg">Limpiar Formulario</button>
                    <button type="button" class="btn btn-warning btn-lg ms-2">Enviar</button>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>  
</body>
</html>