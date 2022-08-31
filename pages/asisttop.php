<?php 
include './generales/header.php';
?>
<header class="d-flex flex-wrap justify-content-left py-3 mb-4 border-bottom">
    <a href="/" class="d-flex align-items-center mb-2 mb-md-0 me-md-auto text-dark text-decoration-none">
      <img src="../img/SameinLogo.png" height="40" class="logo">
    </a>
    <ul class="nav nav-pills">
      <li class="nav-item"><a href="./dashboard.php" class="nav-link" aria-current="page">Inicio</a></li>
      <li class="nav-item"><a href="../pages/pacients.php" class="nav-link">Pacientes</a></li>
      <li class="nav-item"><a href="#" class="nav-link">Diagnosticos</a></li>
      <li class="nav-item"><a href="../pages/asisttop.php" class="nav-link active">Asist-Top</a></li>
      <li class="nav-item"><a href="../config/logout.php" class="nav-link">Cerrar Sesión</a></li>
    </ul>
</header>
  <div class="container">
    <section class="row">
      <div class="col-md-12">
        <h1 class="text-center">Perfil de Resultados de Tratamiento (TOP)</h1>
        <p class="text-center">SAMEIN S.A.S.</p>
      </div>
    </section>
    <hr><br/>
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
    <!--  Uso de sustancias  -->
    <section class="row">
      <div class="col-md-12">
        <h3>Seccion 1: Uso de Sustancias.</h3>
        <h6>
          Registrar la <strong>cantidad promedio de uso diario</strong> y el <strong>número de 
          Días</strong> de uso de sustancias consumidas en las <strong>últimas 4 semanas.</strong> 
        </h6>
        <p></p>
      </div>
    </section>
    <!--  Sustancia 1  -->
    <section class="row">
        <div class="col-md-1">
            
        </div>
        <div class="form-group col-md-2">
            <label for="promdia" class="text"><strong>Promedio diario</strong></label>  
        </div>
        <div class="form-group col-md-2">
            <label class="text"><strong>Última Semana</strong></label>
           </div>
        <div class="form-group col-md-2">
            <label class="text"><strong>Semana 3</strong></label>
             </div>
        <div class="form-group col-md-2">
            <label class="text"><strong>Semana 2</strong></label> 
             </div>
        <div class="form-group col-md-2">
            <label class="text"><strong>Semana 1</strong></label>
            </div>
        <div class="form-group col-md-1">
            <label class="text"><strong>Total</strong></label>
        </div>   
    </section>
    <p></p>
    <section class="row">
        <div class="col-md-1">
            <label class="text"><strong>Alcohol</strong></label>
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
    <!--  Sustancia 2  -->
    <section class="row">
        <div class="col-md-1">
            <label class="text"><strong>Marihuana</strong></label>
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
    <!--  Sustancia 3  -->
    <section class="row">
        <div class="col-md-1">
            <label class="text"><strong>Pasta Base</strong></label>
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
    <!--  Sustancia 4  -->
    <section class="row">
        <div class="col-md-1">
            <label class="text"><strong>Cocaína</strong></label>
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
    <!--  Sustancia 5  -->
    <section class="row">
        <div class="col-md-1">
            <label class="text"><strong>Sedantes</strong></label>
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
    <!--  Sección 2  -->
    <section class="row">
      <div class="col-md-12">
        <h3>Seccion 2: Transgresión a la norma social.</h3>
        <h6>Registrar <strong>hurtos, robos, violencia intrafamiliar y 
          otras acciones cometidas en las últimas 4 semanas</strong></h6>
        <p></p>
      </div>
    </section>
    <p></p>
    <!--  Trangresion 1  -->
    <section class="row">
      <div class="col-md-6">
        <p><strong>a. Hurto</strong></p>
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
    <!--  Trangresion 2  -->
    <section class="row">
      <div class="col-md-6">
        <p><strong>b. Robo</strong></p>
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
    <!--  Trangresion 3  -->
    <section class="row">
      <div class="col-md-6">
        <p><strong>c. Venta de droga</strong></p>
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
    <!--  Trangresion 4  -->
    <section class="row">
      <div class="col-md-6">
        <p><strong>d. Riña</strong></p>
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
    <!--  Trangresion 5  -->
    <section class="row">
        <div class="col-md-3">
            <p><strong>e. Violencia intrafamiliar (Maltrato físico o psicológico)</strong></p>
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
    <p></p>
    <section class="row">
      <div class="col-md-6">
        <label><strong>f. Otra acción</strong></label> 
        <input type="text" class="form-control" id="total" placeholder="Otra acción">    
      </div>
      <div class="col-md-2">
        <br>
        <label class="radio">
        <input type="radio" name="pregunta1" id="pregunta1a" value="SI"> Si
        </label>
      </div>
      <div class="col-md-2">
        <br>
        <label class="radio">
        <input type="radio" name="pregunta1" id="preguntab" value="NO"> No
        </label>
      </div>
      <div class="col-md-2">
        <br>
        <label class="radio">
        <input type="radio" name="pregunta1" id="preguntac" value="NA"> N/A
        </label>
      </div>
    </section>
    <p></p>
    <br />
    <hr />
    <!--  SECCIÓN 3  -->
    <section class="row">
      <div class="col-md-12">
        <h3>Seccion 3: Salud y funcionamiento social.</h3>
        <p></p>
      </div>
    </section>
    <!--  Item 1  -->
    <section class="row">
      <div class="col-md-6">
        <p><strong>a. Calificar el resultado de salud psicológica del usuario</strong>
        (Ansiedad, depresión y/o problemas emocionales)</p>
      </div>
      <div class="col-md-2">
        <input class="form-control" type="text" id="psychologicalstate" placeholder="Calificación(1-20)" size="10px" title="calificación de 1 a 20 dónde 1 es muy malo y 20 excelente" >
      </div>
       1<div id="progress-wrappsy" class="progress-wrap progress col-md-2" data-progress-percent="50"  >
        <div id="progress-barpsy" class="progress-bar progress"></div>
      </div>20
    </section>
    <p></p>
    <!-- encabezado de inputs -->
    <section class="row">
        <div class="col-md-1">
            
        </div>
        <div class="form-group col-md-2">
            <label for="promdia" class="text"></label>  
        </div>
        <div class="form-group col-md-2">
            <label class="text"><strong>Última Semana</strong></label>
           </div>
        <div class="form-group col-md-2">
            <label class="text"><strong>Semana 3</strong></label>
             </div>
        <div class="form-group col-md-2">
            <label class="text"><strong>Semana 2</strong></label> 
             </div>
        <div class="form-group col-md-2">
            <label class="text"><strong>Semana 1</strong></label>
            </div>
        <div class="form-group col-md-1">
            <label class="text"><strong>Total</strong></label>
        </div>   
    </section>
    <p></p>
    <!--  Item 2  -->
    <section class="row">
        <div class="col-md-3">
            <label class="text"><strong>Días de trabajo remunerado</strong></label>
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
        <div class="col-md-3">
            <label class="text"><strong>Días asistidos al Colegio,Instituto, Universidad o Centro Capacitación</strong></label>
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
      <div class="col-md-6">
        <p><strong>a. Calificación del estado de salud física del usuario</strong>
        (Agrado de síntomas físicos u molestias por enfermadad)</p>
      </div>
      <div class="col-md-2">
        <input class="form-control" type="text" id="physicalstate" size="10px" placeholder="Calificación(1-20)" title="calificación de 1 a 20 dónde 1 es muy malo y 20 excelente" >
      </div>
      1<div  id="progress-wrapphy" class="progress-wrap progress col-md-2" data-progress-percent="50"  >
        <div id="progress-barphy" class="progress-bar progress"></div>
      </div>20
    </section>
    <p></p>
    <section class="row">
      <div class="col-md-6">
        <label><strong>e. Tiene un lugar para vivir</strong></label>  
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
    <section class="row">
      <div class="col-md-6">
        <label><strong>f. Habita en una vivienda que cumple con las condiciones básicas</strong></label> 
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
    <section class="row">
      <div class="col-md-6">
        <p><strong>a. Calificación global de calidad de vida del usuario</strong>
        (Ej.Es capaz de disfrutar de la vida, consigue estar bien con su familia y el entorno)</p>
      </div>
      <div class="col-md-2">
        <input class="form-control" type="text" id="housestate" size="10px" placeholder="Calificación(1-20)" title="calificación de 1 a 20 dónde 1 es muy malo y 20 excelente" >
      </div>
      1<div  id="progress-wrahou" class="progress-wrap progress col-md-2" data-progress-percent="50"  >
        <div id="progress-barhou" class="progress-bar progress"></div>
      </div>20
    </section>


    <br />
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

  <script src="../Js/asisttop.Js"></script>
  <style>
    .progress {
      width: 224px;
      margin: 0 auto;
      padding: 0;
    }
    .progress-wrap {
        background: #252D59;
        margin: 10px 0;
        overflow: hidden;
        position: relative;
    }

    .progress-bar {
        background: #ddd;
        position: absolute;
        top: 0;
        }
  </style>

  <footer class="container">
    <p>Todos los derechos reservados para ESE Hospital San Rafael de Girardota.</p>
  </footer>