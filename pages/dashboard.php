<!-- Llamado para encabezado de la página -->
<?php 
include './generales/header.php';
?>
<!-- Barra de menu -->
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">
        <img src='../img/SameinLogo.png' alt="logo" class="logo">
      </a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="#">Inicio</a></li>
      <li><a href="#">Pacientes</a></li>
      <li><a href="#">Entidades</a></li>
      <li><a href="#">Reportes</a></li>
      <li>  
        <a href="../config/logout.php"><i class="fa fa-power-off"></i> Cerrar Sesión</a>
      </li>
    </ul>
  </div>
</nav>

<nav>
  <div class="maxw content">
    <div class="cuerpo">
      <p>
        <div class="formulario">
          <div class="item">
            <h3 name="Especial">BIENVENIDO DR <?php echo  strtoupper($nombre);?>,INGRESE LOS DATOS DEL PACIENTE</h3>
            <div class="campos">
              <aside>
                <a href="#">
                  <svg class="imgperfil" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="90" height="90">
                  <path class="heroicon-ui" d="M12 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm9 11a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3H8a3 3 0 0 0-3 3v2a1 1 0 0 1-2 0v-2a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v2z"/></svg>
                  <span width="90" height="90">Adjuntar Archivo</span>
                </a>
              </aside>
            </div>
            <form method="POST" action="../config/commit.php" >
              <div class="doble">
                  <div class="campo">
                    <label for="dni">Documento*</label>
                    <input required type="tel" maxlength="20" id="Dni" name= "Doc"  >
                  </div>
                  <!-- Nombres completos -->
                  <div class="campo">
                    <label for="nombre">Nombres*</label>
                    <input required type="text" id="nombre" name="Name">
                  </div>
                  <!-- Apellidos completos -->
                  <div class="campo">
                    <label for="apellido">Apellidos*</label>
                    <input required type="text" id="apellido" name="LastN">
                  </div>
                  <!-- Teléfono -->
                  <div class="campo">
                    <label for="celular">Celular*</label>
                    <input required type="tel" maxlength="10" id="celular" name="Phone0">
                  </div>
                </div>
                <!-- SEGUNDO BLOQUE DE CAMPOS-->
                <div class="doble">
                  <div class="campo">
                    <label for="fec_Com">Fecha comentario*</label>
                    <input required type="date" id="CommentDate" name="CommentDate"/>
                  </div>
                  <div class="campo">
                    <label for="Com">Hora comentario202*</label>
                    <input required type="time" id="CommentHour" name="CommentHour"/>
                  </div>
                  <div class="campo">

                    <?php
                    include '../config/config.php';
                    $Sql = 'SELECT KP_UUID,codigo,observation  FROM diagnosis LIMIT 10';
                      $Result = $conn->query($Sql);

                      echo '<label for="Com"> Diagnostico* </label>';
                      echo '<select class="Acpt" id="Com"  name="Diagnosis">';
                        while ($row = $Result->fetch_assoc()){
                        echo "<option value=\"".$row['KP_UUID']."\">".$row['codigo']." ".$row['observation']."</option>"; 
                        }
                      echo "</select>";
                    
                    ?>

                  </div>
                  <div class="campo">
                    <label for="Com">Aceptado*</label>
                      <select id="color" name="Accept">
                        <option value="1">SI</option>
                        <option value="0">NO</option>
                      </select>
                  </div>
                  <div class="campo">
                    <label for="peso" >EPS</label>
                      <select class="Acpt" id="color" name="Eps">
                      <option value="0">E.P.S.  SANITAS  S.A.</option>
                      <option value="1">NUEVA EPS S.A.</option>
                      <option value="2">E.P.S.  FAMISANAR  LTDA.</option>
                      <option value="3">SALUD  TOTAL  S.A.  E.P.S.</option>
                      <option value="4">EPS SERVICIO OCCIDENTAL</option>
                      <option value="5">SALUDVIDA  S.A.  E.P.S.</option>
                      <option value="6">EPS  CONVIDA</option>
                      <option value="7">ANASWAYUU</option>
                    </select>
                  </div>
                  <div class="campo">
                    <label for="peso" >RANGO</label>
                    <select class="Acpt" id="color" name="Range">
                      <option value="0">SISBEN</option>
                      <option value="1">A</option>
                      <option value="2">B</option>
                      <option value="3">C</option>
                    </select>
                  </div>
                  <div class="campo">
                  <label for="Com">EPS Activo*</label>
                    <select id="color" name="StatusEps">
                      <option value="1">SI</option>
                      <option value="0">NO</option>
                    </select>
                  </div>
                </div>
                <!-- TERCER BLOQUE DE CAMPOS--> 
                <div class="doble"> 
                  <div class="campo">
                    <label for="SentBy">Remitido Por*</label>
                    <input required type="text" id="SentBy" name="SentBy" size="80Px">
                  </div>
                  <div class="campo">
                    <label for="peso">IPS</label>
                    <select class="Acpt" id="color" name="Ips">
                      <option value="0">EXPERTA SALUD</option>
                      <option value="1">SALUD Y VIDA </option>
                      <option value="1">IPS COOMEVA S.A.S MED</option>
                      <option value="1">RHS Alianza IPS</option>
                    </select>
                  </div>
                </div>
                <!-- CUARTO BLOQUE DE CAMPOS--> 
                <div class="doble">
                  <div class="campo">
                    <label for="Com">Fecha Cita*</label>
                    <input required type="date" id="appointmentDate" name="AppointmentDate"/>
                  </div>
                  <div class="campo" >
                    <label for="Com">Hora Cita*</label>
                    <input required type="time" id="AppointmentHour" name="AppointmentHour"/>
                  </div>
                  <div class="campo">
                    <label for="dni">No Llamadas*</label>
                    <input required type="number" id="CallsNumber" name="CallsNumber">
                  </div>
                </div>
                <!-- QUINTO BLOQUE DE CAMPOS--> 
                <div class="doble">
                  <div class="campo">
                    <label for="dni">Observación*</label>
                    <input required type="text" id="Comment" name="Comment"  size="110px">
                  </div>
                </div>
                <div class="clear_r">
                  <input type="submit" class="boton_ok" value="Confirmar"/>
                </div>
            </form>
          </div>
        </div>
      </p>
    </div>
  </div>
</nav>