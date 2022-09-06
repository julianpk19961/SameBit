<?php 
include './generales/header.php';
?>

<header class="d-flex flex-wrap justify-content-left py-3 mb-4 border-bottom">
    <a href="/" class="d-flex align-items-center mb-2 mb-md-0 me-md-auto text-dark text-decoration-none">
      <img src="../img/SameinLogo.png" height="40" class="logo">
    </a>
    <ul class="nav nav-pills">
    <li class="nav-item"><a href="../pages/dashboard.php" class="nav-link active" aria-current="page">Inicio</a></li>
      <li class="nav-item"><a href="../pages/pacients.php" class="nav-link">Pacientes</a></li>
      <li class="nav-item"><a href="#" class="nav-link">Diagnosticos</a></li>
      <li class="nav-item"><a href="" class="nav-link">Asist-Top</a></li>
      <li class="nav-item"><a href="http://localhost/samebit/" class="nav-link">Cerrar Sesión</a></li>
    </ul>
</header>

  <div class="container" id="form">
    <section class="row">
      <div class="col-md-12">
        <h1 class="text-center">Perfil de Resultados de Tratamiento (TOP)</h1>
        <p class="text-center"><strong>SAMEIN S.A.S.</strong></p>
      </div>
    </section>
    <hr><br/>
    <section class="row">
      <section class="col-md-12">
        <h3>Datos básicos</h3>
        <p></p>
      </section>
    </section>
    <section class="row">
        <section class="col-md-12">
          <section class="row">
            <div class="col-md-4">
              <div class="form-group">
                  <label for="interviewDate">Fecha Entrevista: *</label>
                  <input type="date" class="form-control" id="interviewDate" disabled>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="dni">Identificación: *</label>
                <input type="text" class="form-control" id="dni" maxlength="128" placeholder="Identificación" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="bornDate">Fecha Nacimiento: *</label>
                <input type="date" class="form-control" id="bornDate" required>
              </div>
            </div>
          </section>
          <section class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label for="patientName">Nombre Paciente: *</label>
                <input type="text" class="form-control" id="patientName" maxlength="128" placeholder="Nombre Completo" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form_group">
                <label for="gender">Género: *</label>
                <select class="form-control" id="gender">
                  <option value="">Seleccione Género</option>
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
                <label for="step">Etapa Tratamiento: *</label>
                <select class="form-control" id="step">
                  <option value="">Seleccione Etapa</option>
                  <option value="0">Ingreso</option>
                  <option value="1">Egreso</option>
                  <option value="2">En tratamiento</option>
                  <option value="2">Seguimiento</option>
                </select>
              </div>
            </div>
            <div class="col-md-8">
              <label for="pollster">Entrevistador: *</label>
              <input type="text" class="form-control" id="entrevistador" placeholder="pollster" maxlength="12" disabled/>
            </div>
          </section>
        </section>
    </section>
    <hr/>
    <!--  Head drugs section  -->
    <section class="row">
      <div class="col-md-12">
        <h3>Sección 1: Uso de Sustancias.</h3>
        <h6>
          Registrar la <strong>cantidad promedio de uso diario</strong> y el <strong>número de 
          Días</strong> de uso de sustancias consumidas en las <strong>últimas 4 semanas.</strong> 
        </h6>
        <p></p>
      </div>
    </section>
    <!--  Headers of drugs question  -->
    <section class="row">
        <div class="col-md-1">
        </div>
        <div class="form-group col-md-2">
            <label for="daylyAverageAlcohol" class="text"><strong>Promedio diario</strong></label>  
        </div>
        <div class="form-group col-md-2">
            <label for="week4Alcohol" class="text">
              <strong>Última Semana</strong><br>
              Dias Semana / Consumo
            </label>
           </div>
        <div class="form-group col-md-2">
            <label for="week3Alcohol" class="text">
              <strong>Semana 3</strong><br>
              Dias Semana / Consumo
             </div>
        <div class="form-group col-md-2">
            <label for="week2Alcohol" class="text">
                <strong>Semana 2</strong></label><br>
              Dias Semana / Consumo 
             </div>
        <div class="form-group col-md-2">
            <label for="week1Alcohol" class="text">
              <strong>Semana 1</strong><br>
              Dias Semana / Consumo</label>
            </div>
        <div class="form-group col-md-1">
            <label class="text"><strong>Días Consumo Total</strong></label>
        </div>   
    </section>
    <p></p>
    <!-- ALCOHOL -->
    <section class="row" id="Alcohol">
        <div class="col-md-1">
            <label class="text mx-auto" ><strong>Alcohol</strong></label>
        </div>
        <div class="form-group col-md-1 ">
            <input type="number" class="form-control input-sm" id="daylyAverageAlcohol"  placeholder="Total" min="0" title="Cantidad tragos diarios" required>  
        </div>
        <div class="form-group col-md-1">
           <label id="undAlcohol" class="text"><strong>Tragos</strong></label>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week4Alcohol" title="Días de la semana" min="0" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week4dailyAlcohol" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week3Alcohol" title="Días de la semana" min="0" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week3dailyAlcohol" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week2Alcohol" title="Días de la semana" min="0" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week2dailyAlcohol" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week1Alcohol" title="Días de la semana" min="0" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week1dailyAlcohol" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="MonthTotalAlcohol" placeholder="Total" disabled>      
        </div>   
    </section>

    <p></p>
    <!--  WEED  -->
    <section class="row" id="Weed">
        <div class="col-md-1">
            <label class="text"><strong>Marihuana</strong></label>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="daylyAverageWeed" placeholder="Total" min="0" title="Cantidad cripas diarias" required>  
        </div>
        <div class="form-group col-md-1">
           <label id="undWeed"><strong>Cripas</strong></label>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week4Weed" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week4dailyWeed" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week3Weed" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week3dailyWeed" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week2Weed" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week2dailyWeed" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week1Weed" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week1dailyWeed" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="MonthTotalWeed" placeholder="Total" disabled>      
        </div>   
    </section>
    <p></p>
    <!--  PBC  -->
    <section class="row" id="Pbc">
        <div class="col-md-1">
            <label  class="text"><strong>Pasta Base</strong></label>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="daylyAveragePbc" placeholder="Total" min="0" title="Cantidad cripas diarias" required>  
        </div>
        <div class="form-group col-md-1">
           <label id="undPbc"><strong>Papeletas</strong></label>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week4Pbc" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week4dailyPbc" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week3Pbc" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week3dailyPbc" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week2Pbc" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week2dailyPbc" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week1Pbc" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week1dailyPbc" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="MonthTotalPbc" placeholder="Total" disabled>      
        </div>   
    </section>
    <p></p>
    <!--  Sustancia 4  -->
    <section class="row" id="Stimulants">
        <div class="col-md-1">
            <label class="text"><strong>Estimulantes</strong></label>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="daylyAverageStimulants" placeholder="Total" min="0" title="Cantidad cripas diarias" required>  
        </div>
        <div class="form-group col-md-1">
           <label id="undStimulants" for=""><strong>Gramos</strong></label>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week4Stimulants" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week4dailyStimulants" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week3Stimulants" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week3dailyStimulants" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week2Stimulants" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week2dailyStimulants" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week1Stimulants" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week1dailyStimulants" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="MonthTotalStimulants" placeholder="Total" disabled>      
        </div>   
    </section>
    <p></p>
    <!--  Sustancia 5  -->
    <section class="row" id="Sedative">
        <div class="col-md-1">
            <label class="text"><strong>Sedantes</strong></label>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="daylyAverageSedative" placeholder="Total" min="0" title="Cantidad cripas diarias" required>  
        </div>
        <div class="form-group col-md-1">
           <label id="undSedative" for=""><strong>Comprimidos</strong></label>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week4Sedative" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week4dailySedative" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week3Sedative" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week3dailySedative" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week2Sedative" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week2dailySedative" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week1Sedative" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week1dailySedative" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="MonthTotalSedative" placeholder="Total" disabled>      
        </div>   
    </section>
    <p></p>
     <!--  Sustancia 6  -->
     <section class="row" id="Opiates">
        <div class="col-md-1">
            <label  class="text"><strong>Opiáceos</strong></label>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="daylyAverageOpiates" placeholder="Total" min="0" title="Cantidad cripas diarias" required>  
        </div>
        <div class="form-group col-md-1">
           <label id="undOpiates"><strong>Gramos</strong></label>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week4Opiates" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week4dailyOpiates" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week3Opiates" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week3dailyOpiates" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week2Opiates" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week2dailyOpiates" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week1Opiates" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week1dailyOpiates" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="MonthTotalOpiates" placeholder="Total" disabled>      
        </div>   
    </section>
    <p></p>
     <!--  Sustancia 6  -->
     <section class="row" id="Gambling">
        <div class="col-md-1">
            <label class="text"><strong>Ludopatia</strong></label>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="daylyAverageGambling" placeholder="Total" min="0" title="Cantidad cripas diarias" required>  
        </div>
        <div class="form-group col-md-1">
           <label id="undGambling" contenteditable="true" ><strong>Modificar Unidad</strong></label>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week4Gambling" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week4dailyGambling" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week3Gambling" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week3dailyGambling" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week2Gambling" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week2dailyGambling" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week1Gambling" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="week1dailyGambling" title="Consumo" disabled>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="MonthTotalGambling" placeholder="Total" disabled>      
        </div>   
    </section>
    <br>
    <hr>
    <!--  Sección 2  -->
    <section class="row">
      <div class="col-md-12">
        <h3>Sección 2: Transgresión a la norma social.</h3>
        <h6>Registrar <strong>hurtos, robos, violencia intrafamiliar y 
          otras acciones cometidas en las últimas 4 semanas</strong></h6>
        <p></p>
      </div>
    </section>
    <p></p>
    <!--  Trangresion 1  -->
    <section class="row" id="Theft">
      <div class="col-md-10" >
        <p><strong>a. Hurto</strong></p>
      </div>
      <div class="col-md-1">
        <label class="radio">
          <input type="radio" name="TheftYN" id="YTheft" value="1"> Si
        </label>
      </div>
      <div class="col-md-1">
        <label class="radio">
          <input type="radio" name="TheftYN" id="NTheft" value="0"> No
        </label>
      </div>

    </section>
    <p></p>
    <!--  Trangresion 2  -->
    <section class="row" id="Robbery">
      <div class="col-md-10">
        <p><strong>b. Robo</strong></p>
      </div>
      <div class="col-md-1">
        <label class="radio">
        <input type="radio" name="RobberyYN" id="YRobbery" value="1"> Si
      </label>
      </div>
      <div class="col-md-1">
        <label class="radio">
        <input type="radio" name="RobberyYN" id="NRobbery" value="0"> No
      </label>
      </div>
    </section>
    <p></p>
    <!--  Trangresion 3  -->
    <section class="row" id="microtraffic">
      <div class="col-md-10">
        <p><strong>c. Venta de droga</strong></p>
      </div>
      <div class="col-md-1">
        <label class="radio">
          <input type="radio" name="microtrafficYN" id="Ymicrotraffic" value="0"> Si
        </label>
      </div>
      <div class="col-md-1">
        <label class="radio">
          <input type="radio" name="microtrafficYN" id="Nmicrotraffic" value="1"> No
        </label>
      </div>
    </section>
    <p></p>
    <!--  Trangresion 4  -->
    <section class="row" id="fight">
      <div class="col-md-10">
        <p><strong>d. Riña</strong></p>
      </div>
      <div class="col-md-1">
        <label class="radio">
        <input type="radio" name="fightYN" id="Yfight" value="1"> Si
      </label>
      </div>
      <div class="col-md-1">
        <label class="radio">
        <input type="radio" name="fightYN" id="Nfight" value="0"> No
      </label>
      </div>
    </section>
    <p></p>
    <!--  Trangresion 5  -->
    <section class="row" id="Abuse">
        <div class="col-md-3">
            <p><strong>e. Violencia intrafamiliar (Maltrato físico o psicológico)</strong></p>
         </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="week4Abuse" placeholder="última Semana" min="1" max="7" required>
            Última semana
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="week3Abuse" placeholder="Semana 3" min="1" max="7" required>
            Semana 3
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="week2Abuse" placeholder="Semana 2" min="1" max="7" required>
            Semana 2
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="week1Abuse" placeholder="Semana 1" min="1" max="7" required>
            Semana 1
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="MonthTotalAbuse" placeholder="" disabled >
            Total
        </div>
    </section>
    <p></p>
    <p></p>
    <section class="row" id="Other">
      <div class="col-md-10">
        <label><strong>f. Otra acción</strong></label> 
        <input type="text" class="form-control" id="Cnt" placeholder="Otra acción">    
      </div>
      <div class="col-md-1">
        <br>
        <label class="radio">
        <input type="radio" name="OtherYN" id="YOther" value="1"> Si
        </label>
      </div>
      <div class="col-md-1">
        <br>
        <label class="radio">
        <input type="radio" name="OtherYN" id="NOther" value="0"> No
        </label>
      </div>
    </section>
    <p></p>
    <br />
    <hr />
    <!--  SECCIÓN 3  -->
    <section class="row">
      <div class="col-md-12">
        <h3>Sección 3: Salud y funcionamiento social.</h3>
        <p></p>
      </div>
    </section>
    <!--  Item 1  -->
    <section class="row">
      <div class="col-md-4">
        <p><strong>a. Calificar el resultado de salud psicológica del usuario</strong>
        (Ansiedad, depresión y/o problemas emocionales)</p>
      </div>
      <div class="col-md-1">
        <input class="form-control" type="text" id="psychologicalstate" placeholder="(1 a 20)" size="10px" title="calificación de 1 a 20 dónde 1 es muy malo y 20 excelente" >
      </div>
      <div class="col-md-7">

        <div class="progress" style="height: 20px;" style="height: 20px;">
        <div class="progress-bar" id="_progress-wrappsy" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"> 
        </div>
       </div>
      </div>
    </section>
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
            <label class="text"><strong>Total días</strong></label>
        </div>   
    </section>
    <p></p>
    <!--  Item 2  -->
    <section class="row" id="Work">
        <div class="col-md-3">
            <label class="text"><strong>Días de trabajo remunerado</strong></label>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="week4Work" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="week3Work" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="week2Work" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="week1Work" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="MonthTotalWork" placeholder="Total" disabled>      
        </div>   
    </section>
    <p></p>
    <section class="row" id="School">
        <div class="col-md-3">
            <label class="text"><strong>Días asistidos al Colegio, Instituto, Universidad o Centro Capacitación</strong></label>
        </div>
        <div lass="col-md-1" class="form-group col-md-2">
            <input type="number" class="form-control" id="week4School" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="week3School" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="week2School" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-2">
            <input type="number" class="form-control" id="week1School" title="Días de la semana" min="1" max="7" required>
        </div>
        <div class="form-group col-md-1">
            <input type="number" class="form-control" id="MonthTotalSchool" placeholder="Total" disabled>      
        </div>   
    </section>
    <p></p>
    <section class="row">
      <div class="col-md-4">
        <p><strong>a. Calificación del estado de salud física del usuario</strong>
        (Agrado de síntomas físicos u molestias por enfermedad)</p>
      </div>
      <div class="col-md-1">
        <input class="form-control" type="text" id="physicalstate" size="10px" placeholder="(1 a 20)" title="calificación de 1 a 20 dónde 1 es muy malo y 20 excelente" >
      </div>
      <div class="col-md-7">
      <p></p>
        <div class="progress" style="height: 20px;">
          <div class="progress-bar" id="_progress-wrapphy" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"> </div>
        </div>
      </div>
    </section>
    <p></p>
    <section class="row" id="LiveHome">
      <div class="col-md-10">
        <label><strong>e. Tiene un lugar para vivir</strong></label>  
      </div>
      <div class="col-md-1">
        
        <label class="radio">
        <input type="radio" name="LivehomeYN" id="YLiveHome" value="1"> Si
        </label>
      </div>
      <div class="col-md-1">
        
        <label class="radio">
        <input type="radio" name="LivehomeYN" id="NLiveHome" value="0"> No
        </label>
      </div>
    </section>
    <p></p>
    <section class="row" id="Basicconditions">
      <div class="col-md-10">
        <label><strong>f. Habita en una vivienda que cumple con las condiciones básicas</strong></label> 
      </div>
      <div class="col-md-1">
        <label class="radio">
        <input type="radio" name="BasicconditionsYN" id="YBasicconditions" value="1"> Si
        </label>
      </div>
      <div class="col-md-1">
        <label class="radio">
        <input type="radio" name="BasicconditionsYN" id="NBasicconditions" value="0"> No
        </label>
      </div>
    </section>
    <p></p>
    <section class="row">
      <div class="col-md-4">
        <p><strong>a. Calificación global de calidad de vida del usuario</strong>
        (Ej. Es capaz de disfrutar de la vida, consigue estar bien con su familia y el entorno)</p>
      </div>
      <div class="col-md-1">
        <input class="form-control" type="text" id="housestate" size="10px" placeholder="(1 a 20)" title="calificación de 1 a 20 dónde 1 es muy malo y 20 excelente" >
      </div>
      <div class="col-md-7">
        <p></p>
        <div class="progress" style="height: 20px;">
          <div class="progress-bar"   id="_progress-wraphou" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
        </div>
      </div>
    </section>
    <hr />
   

    <!--  Comentarios  -->
    <section class="row">
      <div class="col-md-12">
        <h3>Comentarios Adicionales.</h3>
        <p></p>
      </div>
    </section>
    <section class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="comment">Comentarios:</label>
          <textarea class="form-control" rows="6" id="comment"></textarea>
        </div>
      </div>
    </section>
    <p></p>
    <section class="row ">
      <div class="col-md-12">
        <button type="button" class="btn btn-success ml-auto" id="saveForm" onclick="saveForm">Guardar</button>
        <button type="button" class="btn btn-danger" id="clearForm">Limpiar</button>
      </div>
    </section>
  </div>

  <br /><br />

  <script src="../Js/asisttop.Js"></script>
  

  <footer class="container">
    <!-- <p>Todos los derechos reservados para ESE Hospital San Rafael de Girardota.</p> -->
  </footer>