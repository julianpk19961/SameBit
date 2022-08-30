<?php 
include './generales/header.php';
?>
<header class="d-flex flex-wrap justify-content-left py-3 mb-4 border-bottom">
    <a href="/" class="d-flex align-items-center mb-2 mb-md-0 me-md-auto text-dark text-decoration-none">
      <img src="../img/SameinLogo.png" height="40" class="logo">
    </a>
    <ul class="nav nav-pills">
      <li class="nav-item"><a href="./dashboard.php" class="nav-link active" aria-current="page">Inicio</a></li>
      <li class="nav-item"><a href="../pages/pacients.php" class="nav-link">Pacientes</a></li>
      <li class="nav-item"><a href="#" class="nav-link">Diagnosticos</a></li>
      <li class="nav-item"><a href="../pages/asisttop.php" class="nav-link">Asist-Top</a></li>
      <li class="nav-item"><a href="../config/logout.php" class="nav-link">Cerrar Sesión</a></li>
    </ul>
</header>
<br />
  <div class="container">
    <section class="row">
      <div class="col-md-12">
        <h1 class="text-center">Perfil de Resultados de Tratamiento (TOP)</h1>
        <p class="text-center">SAMEIN S.A.S.</p>
      </div>
    </section>
    <hr><br />
        <section class="row">
            <section class="col-md-12">
                <h3>Datos basicos</h3>
                <p></p>
            </section>
        </section>
        <section class="row">
            <section class="col-md-12">
                <section class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fechaEntrevista">Fecha Entrevista: *</label>
                            <input type="date" class="form-control" id="fechaEntrevista" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="identificacion">identificación: *</label>
                            <input type="text" class="form-control" id="identificacion" maxlength="128" placeholder="Identificación" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                        <label for="fechaNacimiento">Fecha Nacimiento: *</label>
                        <input type="date" class="form-control" id="fechaNacimiento" required>
                        </div>
                    </div>
                </section>
                <section class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                        <label for="nombrePaciente">Nombre Paciente: *</label>
                        <input type="text" class="form-control" id="nombrePaciente" maxlength="128" placeholder="Nombre Completo" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form_group">
                            <label for="generoEncuesta">Genero: *</label>
                            <select class="form-control" id="generoEncuesta">
                                <option value="">Seleccione Genero</option>
                                <option value="0">Hombre</option>
                                <option value="1">Mujer</option>
                                <option value="2">Otro</option>
                            </select>
                        </div>
                    </div>
                </section>
                <section class="row">
                    <div class="col-md-4">
                        <div class="form_group">
                            <label for="generoEncuesta">Etapa Tratamiento: *</label>
                            <select class="form-control" id="generoEncuesta">
                                <option value="">Seleccione Etapa</option>
                                <option value="0">Ingreso</option>
                                <option value="1">Egreso</option>
                                <option value="2">En tratamiento</option>
                                <option value="2">Seguimiento</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <label for="entrevistador">Entrevistador: *</label>
                        <input type="text" class="form-control" id="entrevistador" placeholder="Entrevistador" maxlength="12" disabled/>
                    </div>
                </section>
            </section>
        </section>
    <hr/>

    <!--  Servicios  -->
    <section class="row">
      <div class="col-md-12">
        <h3>Seccion 1: Uso de Sustancias.</h3>
        <h6>Registrar la cantidad promedio de uso diario y el número de Días de uso de sustancias consumidas en las últimas 4 semanas.</h6>
        <p></p>
      </div>
    </section>
    <!--  PREGUNTA 1  -->
    <section class="row">
        <div class="col-md-1">
            <label class="text">Sustancia</label>
        </div>
        <div class="form-group col-md-2">
            <label for="promdia" class="text">Promedio día</label>  
        </div>
        <div class="form-group col-md-2">
            <label class="text">Última Semana</label>
           </div>
        <div class="form-group col-md-2">
            <label class="text">Semana 3 </label>
             </div>
        <div class="form-group col-md-2">
            <label class="text">Semana 2</label> 
             </div>
        <div class="form-group col-md-2">
            <label class="text">Semana 1</label>
            </div>
        <div class="form-group col-md-1">
            <label class="text">Total / Días</label>
        </div>   
    </section>
    <p></p>
    <section class="row">
        <div class="col-md-1">
            <label class="text">Alcohol</label>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Total" required>    
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="total" placeholder="Total" disabled>      
        </div>   
    </section>
    <p></p>
    <!--  PREGUNTA 2  -->
    <section class="row">
        <div class="col-md-1">
            <label class="text">Marihuana</label>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Total" required>    
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="total" placeholder="Total" disabled>      
        </div>   
    </section>
    <p></p>
    <section class="row">
        <div class="col-md-1">
            <label class="text">Pasta Base</label>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Total" required>    
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="total" placeholder="Total" disabled>      
        </div>   
    </section>
    <p></p>
    <section class="row">
        <div class="col-md-1">
            <label class="text">Cocaína</label>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Total" required>    
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="total" placeholder="Total" disabled>      
        </div>   
    </section>
    <p></p>
    <section class="row">
        <div class="col-md-1">
            <label class="text">Sedantes</label>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Total" required>    
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Cantidad Días" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="total" placeholder="Total" disabled>      
        </div>   
    </section>
    
    <br>
    <hr>
    <!--  Durante la Atención  -->
    <section class="row">
      <div class="col-md-12">
        <h3>Seccion 2: Transgresión a la norma social.</h3>
        <h6>Registrar hurtos, robos, vioolencia intrafamiliar y otras acciones cometidas en las últimas 4 semanas</h6>
        <p></p>
      </div>
    </section>
    <!--  PREGUNTA 5  -->
    <section class="row">
      <div class="col-md-6">
        <p>a. Hurto</p>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="pregunta1a" value="SI"> Si
      </label>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="preguntab" value="NO"> No
      </label>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="preguntac" value="NA"> N/A
      </label>
      </div>
    </section>
    <!--  PREGUNTA 6  -->
    <section class="row">
      <div class="col-md-6">
        <p>b. Robo</p>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="pregunta1a" value="SI"> Si
      </label>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="preguntab" value="NO"> No
      </label>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="preguntac" value="NA"> N/A
      </label>
      </div>
    </section>
    <!--  PREGUNTA 7  -->
    <section class="row">
      <div class="col-md-6">
        <p>c. Venta de droga</p>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="pregunta1a" value="SI"> Si
      </label>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="preguntab" value="NO"> No
      </label>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="preguntac" value="NA"> N/A
      </label>
      </div>
    </section>
    <!--  PREGUNTA 8  -->
    <section class="row">
      <div class="col-md-6">
        <p>d. Riña</p>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="pregunta1a" value="SI"> Si
      </label>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="preguntab" value="NO"> No
      </label>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="preguntac" value="NA"> N/A
      </label>
      </div>
    </section>
    <!--  PREGUNTA 9  -->
    <p></p>
    <!--  PREGUNTA 10  -->
    
    <section class="row">
        <div class="col-md-3">
            <p>e. Violencia intrafamiliar (Maltrato físico o psicológico)</p>
         </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="última Semana" min="1" max="7" required>
            última semana
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Semana 3" min="1" max="7" required>
            Semana 3
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Semana 2" min="1" max="7" required>
            Semana 2
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="promdia" placeholder="Semana 1" min="1" max="7" required>
            Semana 1
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="promdia" placeholder="" disabled >
            Total
        </div>
    </section>
    <p></p>
    <section class="row">
        <div class="col-md-6">
            <p><label>f. Otra acción</label><input type="text" class="form-control" id="total" placeholder="Otra acción"></p>        
        </div>

      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="pregunta1a" value="SI"> Si
      </label>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="preguntab" value="NO"> No
      </label>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="preguntac" value="NA"> N/A
      </label>
      </div>

    </section>
    <p></p>
    <br />
    <hr />
    <!--  Durante la Atención  -->
    <section class="row">
      <div class="col-md-12">
        <h3>Seccion 3: Salud y funcionamiento social.</h3>
        <p></p>
      </div>
    </section>
    <!--  PREGUNTA 10  -->
    <section class="row">
      <div class="col-md-6">
        <p>10- ¿A usted y/o a su familia le dieron las recomendaciones sobre cómo cuidar su salud en casa?</p>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="pregunta1a" value="SI"> Si
      </label>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="preguntab" value="NO"> No
      </label>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="preguntac" value="NA"> N/A
      </label>
      </div>
    </section>
    <!--  PREGUNTA 11  -->
    <section class="row">
      <div class="col-md-6">
        <p>11- ¿Las áreas del servicio donde fue atendido, se encontraban limpias, comodas y agradables?</p>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="pregunta1a" value="SI"> Si
      </label>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="preguntab" value="NO"> No
      </label>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="preguntac" value="NA"> N/A
      </label>
      </div>
    </section>
    <!--  PREGUNTA 12  -->
    <section class="row">
      <div class="col-md-6">
        <p>12- ¿Si se requiere volveria a utilizar nuestros servicios?</p>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="pregunta1a" value="SI"> Si
      </label>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="preguntab" value="NO"> No
      </label>
      </div>
      <div class="col-md-2">
        <label class="radio">
        <input type="radio" name="pregunta1" id="preguntac" value="NA"> N/A
      </label>
      </div>
    </section>





    <br />
    <hr />
    <!--  Satisfacción General  -->
    <section class="row">
      <div class="col-md-12">
        <h3>Satisfacción General.</h3>
        <p></p>
      </div>
    </section>
    <!--  PREGUNTA 13  -->
    <section class="row">
      <div class="col-md-12">
        <section class="row">
          <div class="col-md-8">
            <p>13- ¿Cómo calificaría su experiencia global respecto a los servicios de salud que ha recibido a través del Hospital?</p>
          </div>
          <div class="col-md-4">
            <select class="form-control" id="pregunta13">
            <option value="5">Muy Buena</option>
            <option value="4">Buena</option>
            <option value="3">Regular</option>
            <option value="2">Mala</option>
            <option value="1">Muy Mala</option>
            <option value="0">No Responde</option>
          </select>
          </div>
        </section>
      </div>
    </section><br />
    <!--  PREGUNTA 14  -->
    <section class="row">
      <div class="col-md-12">
        <section class="row">
          <div class="col-md-8">
            <p>14- ¿Recomendaria a sus familiares y amigos este Hospital?</p>
          </div>
          <div class="col-md-4">
            <select class="form-control" id="pregunta14">
            <option value="5">Muy Buena</option>
            <option value="4">Buena</option>
            <option value="3">Regular</option>
            <option value="2">Mala</option>
            <option value="1">Muy Mala</option>
            <option value="0">No Responde</option>
          </select>
          </div>
        </section>
      </div>
    </section><br />
    <hr />

    <!--  Comentarios  -->
    <section class="row">
      <div class="col-md-12">
        <h3>Comentarios.</h3>
        <p></p>
      </div>
    </section>
    <section class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="comment">Comentarios:</label>
          <textarea class="form-control" rows="6" id="comentarios"></textarea>
        </div>
      </div>
    </section>
    <section class="row">
      <div class="col-md-12">
        <button type="button" class="btn btn-info" id="saveForm" onclick="saveForm">Guardar Encuesta</button>
        <button type="button" class="btn btn-danger" id="clearForm">Limpiar formulario</button>
      </div>
    </section>
  </div>

  <br /><br />

  <script src="../Js/Login.Js"></script>

  <footer class="container">
    <p>Todos los derechos reservados para ESE Hospital San Rafael de Girardota.</p>
  </footer>