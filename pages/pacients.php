<?php
include './generales/header.php';
include './generales/nav.php';
?>

<section class="h-100 ">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col">
        <div class="card card-registration my-4">
          <div class="row g-0">
            <div class="col-xl-6 d-none d-xl-block">
              <img src="../img/reg.jpg"
                alt="Sample photo" class="img-fluid"
                style="border-top-left-radius: .25rem; border-bottom-left-radius: .25rem;" />
            </div>
            <div class="col-xl-6">
              <div class="card-body p-md-5 text-black">
                <h3 class="mb-5 text-uppercase"><?php echo __('patient_registration') ?? 'Registro Paciente'; ?>: </h3>

                <div class="row">
                  <div class="col-md-6 mb-4">
                      <div class="form-outline">
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
                      <div class="form-outline">
                        <input type="text" id="form3Example1m" class="form-control form-control-lg" />
                        <label class="form-label" for="form3Example1m">Identificación</label>
                      </div>
                  </div>
                  <div class="col-md-6 mb-4">
                    <div class="form-outline">
                      <input type="text" id="form3Example1m" class="form-control form-control-lg" />
                      <label class="form-label" for="form3Example1m">Nombres</label>
                    </div>
                  </div>
                  <div class="col-md-6 mb-4">
                    <div class="form-outline">
                      <input type="text" id="form3Example1n" class="form-control form-control-lg" />
                      <label class="form-label" for="form3Example1n">Apellidos</label>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-4">
                    <div class="form-outline">
                        <Select type="text" class="form-control form-control-lg" id="z_xOne" >
                            <option value='0'>Elija una opción</option>
                            <option value='1'>Activo</option>
                            <option value="2">Inactivo</option>
                        </Select>
                        <label class="form-label" for="form3Example1m">Estado</label>
                      </div>
                  </div>
                  <div class="col-md-6 mb-4">
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
<?php include './generales/footer.php'; ?>