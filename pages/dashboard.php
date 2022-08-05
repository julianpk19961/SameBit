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
      <li class="active"><a href="#">Home</a></li>
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

  <!-- <aside class="">
    <nav>
      <a class="activo" href="#"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M17 16a3 3 0 1 1-2.83 2H9.83a3 3 0 1 1-5.62-.1A3 3 0 0 1 5 12V4H3a1 1 0 1 1 0-2h3a1 1 0 0 1 1 1v1h14a1 1 0 0 1 .9 1.45l-4 8a1 1 0 0 1-.9.55H5a1 1 0 0 0 0 2h12zM7 12h9.38l3-6H7v6zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm10 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg>Pacientes</a>
      <a href="#"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M20 11.46V20a2 2 0 0 1-2 2h-3a2 2 0 0 1-2-2v-4h-2v4a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8.54A4 4 0 0 1 2 8V7a1 1 0 0 1 .1-.45l2-4A1 1 0 0 1 5 2h14a1 1 0 0 1 .9.55l2 4c.06.14.1.3.1.45v1a4 4 0 0 1-2 3.46zM18 12c-1.2 0-2.27-.52-3-1.35a3.99 3.99 0 0 1-6 0A3.99 3.99 0 0 1 6 12v8h3v-4c0-1.1.9-2 2-2h2a2 2 0 0 1 2 2v4h3v-8zm2-4h-4a2 2 0 1 0 4 0zm-6 0h-4a2 2 0 1 0 4 0zM8 8H4a2 2 0 1 0 4 0zm11.38-2l-1-2H5.62l-1 2h14.76z"/></svg> Farmacia </a>-->
      <!-- <a href="#"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm0 10v6h14v-6h-2.38l-1.45 2.9a2 2 0 0 1-1.79 1.1h-2.76a2 2 0 0 1-1.8-1.1L7.39 13H5zm14-2V5H5v6h2.38a2 2 0 0 1 1.8 1.1l1.44 2.9h2.76l1.45-2.9a2 2 0 0 1 1.79-1.1H19z"/></svg>Reportes</a>
    </nav>
  </aside> -->

  <div class="cuerpo">
    <p>
      <div class="formulario">    
      <!-- BLOQUE IMG-ADJUNTOS -->
        <div class="slide">
          <div class="item">
            <h3 name="Especial">BIENVENIDO DR. <?php echo  strtoupper($nombre);?>,
            INGRESE LOS DATOS DEL PACIENTE</h3>
            <div class="campos">
              <aside>
                <a href="#">
                  <svg class="imgperfil" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="90" height="90">
                  <path class="heroicon-ui" d="M12 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm9 11a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3H8a3 3 0 0 0-3 3v2a1 1 0 0 1-2 0v-2a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v2z"/></svg>
                  <span width="90" height="90">Adjuntar Archivo</span>
                </a>
              </aside>
          <form>
      <!-- PRIMER BLOQUE DE CAMPOS/ DATOS PACIENTE -->
          <div class="doble">
            <!-- DNI User -->
            <div class="campo">
              <label for="dni">Documento*</label>
              <input required type="tel" maxlength="20" id="dni" name= "Dni"  >
            </div>
            <!-- Name User -->
            <div class="campo">
              <label for="nombre">Nombres*</label>
              <input required type="text" id="nombre" nombre="Name"/>
            </div>
            <div class="campo">
              <label for="apellido">Apellidos*</label>
              <input required type="text" id="apellido" nombre="LastName"/>
            </div>
            <div class="campo">
              <label for="celular">Celular*</label>
              <input required type="tel" maxlength="10" id="celular"/>
            </div>
          </div>

<!-- SEGUNDO BLOQUE DE CAMPOS / DATOS OBSERVACIÓN-->

          <div class="doble">
            <div class="campo">
              <label for="fec_Com">Fecha comentario*</label>
              <input required type="date" id="Fec_Coment"/>
            </div>
            <!-- Hora en la que el encargado diligencio el campo -->
            <div class="campo">
              <label for="Com">Hora comentario*</label>
              <input required type="time" id="Hor_Coment"/>
            </div>
            <div class="campo">
              <label for="Com">Diagnostico* </label>
              <select class="Acpt" id="color">
                <option value="0">F319</option>
                <option value="1">F322</option>
                <option value="0">F412</option>
                <option value="1">F321</option>
              </select>
            </div>
            <div class="campo">
              <label for="Com">Aceptado*</label>
              <select id="color">
                <option value="1">SI</option>
                <option value="0">NO</option>
              </select>
            </div>
            <div class="campo">
              <label for="peso" >EPS</label>
              <select class="Acpt" id="color">
                <option value="0">E.P.S.  SANITAS  S.A.</option>
                <option value="1">NUEVA EPS S.A.</option>
                <option value="2">E.P.S.  FAMISANAR  LTDA.</option>
                <option value="3">SALUD  TOTAL  S.A.  E.P.S.</option>
                <option value="4">EPS SERVICIO OCCIDENTAL DE SALUD  S.A.</option>
                <option value="5">SALUDVIDA  S.A.  E.P.S.</option>
                <option value="6">EPS  CONVIDA</option>
                <option value="7">ANASWAYUU</option>

              </select>
            </div>
            <div class="campo">
              <label for="Com">EPS Activo*</label>
              <select id="color">
                <option value="1">SI</option>
                <option value="0">NO</option>
              </select>
            </div>
          </div>
<!-- TERCER BLOQUE DE CAMPOS / DATOS CONTACTO --> 
          <div class="doble"> 
            <div class="campo">
              <label for="apellido">Remitido Por*</label>
              <input required type="text" id="apellido" size="80Px">
            </div>
            <div class="campo">
              <label for="peso">IPS</label>
              <select class="Acpt" id="color">
                <option value="0">EXPERTA SALUD</option>
                <option value="1">SALUD Y VIDA </option>
                <option value="1">IPS COOMEVA S.A.S MED</option>
                <option value="1">RHS Alianza IPS</option>
              </select>
            </div>
          </div>
<!-- TERCER BLOQUE DE CAMPOS / DATOS CONTACTO EMERGENCIA --> 
          <div class="doble">
            <div class="campo">
              <label for="Com">Fecha Cita*</label>
              <input required type="date" id="Fec_Cita"/>
            </div>
            <div class="campo" >
              <label for="Com">Hora Cita*</label>
              <input required type="time" id="Hor_Cita"/>
            </div>
            <div class="campo">
              <label for="dni">No Llamadas*</label>
              <input required type="number" id="No_Llamadas"/>
            </div>
          </div>
<!-- TERCER BLOQUE DE CAMPOS / OBSERVACION --> 
          <div class="doble">
            <div class="campo">
              <label for="dni">Observación*</label>
              <input required type="text" id="No_Llamadas"  size="110px">
            </div>
          </div>
        </div>
      </form>
      </div>
    </div>
    <div class="item">
      <div class="clear_r">
      <input type="submit" class="boton_ok" value="Confirmar"/>
    </div>
  </div>

            <!-- <div class="item">
              <h3>Actividades</h3>
              <div class="campos">

                <div class="actividades">

                  <div class="item_act cardio">
                    <header>
                      <h3>Cardio</h3>
                      <span class="resumen"></span>
                      <a alt="360" class="check">
                        <svg class="x_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M4.93 19.07A10 10 0 1 1 19.07 4.93 10 10 0 0 1 4.93 19.07zm1.41-1.41A8 8 0 1 0 17.66 6.34 8 8 0 0 0 6.34 17.66zM13.41 12l1.42 1.41a1 1 0 1 1-1.42 1.42L12 13.4l-1.41 1.42a1 1 0 1 1-1.42-1.42L10.6 12l-1.42-1.41a1 1 0 1 1 1.42-1.42L12 10.6l1.41-1.42a1 1 0 1 1 1.42 1.42L13.4 12z"/></svg>
                        <svg class="check_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"/></svg>
                      </a>
                    </header>
                      <div class="detalles">
                        <div class="precio">
                          <h4>360</h4><span>mensual</span>
                        </div>
                        <div class="horarios fijo">
                          <h4>Horarios</h4>
                          <ul>
                            <li><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" width="20" height="20">  <path class="cls-1" id="Time" transform="translate(-256 -1419)" d="M 278 1419 a 22 22 0 1 0 22 22 A 22.025 22.025 0 0 0 278 1419 Z m 0 41 a 19 19 0 1 1 19 -19 A 19 19 0 0 1 278 1460 Z m 10 -20 h -9 v -11 a 1.5 1.5 0 0 0 -3 0 v 14 h 12 A 1.5 1.5 0 0 0 288 1440 Z" /></svg>8:00 - 13:00</li>
                            <li><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" width="20" height="20">  <path class="cls-1" id="Time" transform="translate(-256 -1419)" d="M 278 1419 a 22 22 0 1 0 22 22 A 22.025 22.025 0 0 0 278 1419 Z m 0 41 a 19 19 0 1 1 19 -19 A 19 19 0 0 1 278 1460 Z m 10 -20 h -9 v -11 a 1.5 1.5 0 0 0 -3 0 v 14 h 12 A 1.5 1.5 0 0 0 288 1440 Z" /></svg>16:00 - 22:00</li>
                          </ul>
                        </div>
                      </div>
                      <div class="clear_r">
                        <a href="#" alt="360" class="boton_ok">Añadir actividad</a>
                      </div>
                  </div>

                  <div class="item_act cross">
                    <header>
                      <h3>Crossfit</h3>
                      <span class="resumen"></span>
                      <a alt="380" class="check">
                        <svg  class="x_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M4.93 19.07A10 10 0 1 1 19.07 4.93 10 10 0 0 1 4.93 19.07zm1.41-1.41A8 8 0 1 0 17.66 6.34 8 8 0 0 0 6.34 17.66zM13.41 12l1.42 1.41a1 1 0 1 1-1.42 1.42L12 13.4l-1.41 1.42a1 1 0 1 1-1.42-1.42L10.6 12l-1.42-1.41a1 1 0 1 1 1.42-1.42L12 10.6l1.41-1.42a1 1 0 1 1 1.42 1.42L13.4 12z"/></svg>
                        <svg class="check_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"/></svg>
                      </a>
                    </header>
                      <div class="detalles">
                        <div class="precio">
                          <h4>380</h4><span>mensual</span>
                        </div>
                        <div class="horarios fijo">
                          <h4>Horarios</h4>
                          <ul>
                            <li><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" width="20" height="20">  <path class="cls-1" id="Time" transform="translate(-256 -1419)" d="M 278 1419 a 22 22 0 1 0 22 22 A 22.025 22.025 0 0 0 278 1419 Z m 0 41 a 19 19 0 1 1 19 -19 A 19 19 0 0 1 278 1460 Z m 10 -20 h -9 v -11 a 1.5 1.5 0 0 0 -3 0 v 14 h 12 A 1.5 1.5 0 0 0 288 1440 Z" /></svg>8:00 - 13:00</li>
                            <li><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" width="20" height="20">  <path class="cls-1" id="Time" transform="translate(-256 -1419)" d="M 278 1419 a 22 22 0 1 0 22 22 A 22.025 22.025 0 0 0 278 1419 Z m 0 41 a 19 19 0 1 1 19 -19 A 19 19 0 0 1 278 1460 Z m 10 -20 h -9 v -11 a 1.5 1.5 0 0 0 -3 0 v 14 h 12 A 1.5 1.5 0 0 0 288 1440 Z" /></svg>16:00 - 22:00</li>
                          </ul>
                        </div>
                      </div>
                      <div class="clear_r">
                        <a alt="380" href="#" class="boton_ok">Añadir actividad</a>
                      </div>
                  </div>

                  <div class="item_act zumba">
                    <header>
                      <h3>Zumba</h3>
                      <span class="resumen"></span>
                      <a alt="360" class="check">
                        <svg class="x_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M4.93 19.07A10 10 0 1 1 19.07 4.93 10 10 0 0 1 4.93 19.07zm1.41-1.41A8 8 0 1 0 17.66 6.34 8 8 0 0 0 6.34 17.66zM13.41 12l1.42 1.41a1 1 0 1 1-1.42 1.42L12 13.4l-1.41 1.42a1 1 0 1 1-1.42-1.42L10.6 12l-1.42-1.41a1 1 0 1 1 1.42-1.42L12 10.6l1.41-1.42a1 1 0 1 1 1.42 1.42L13.4 12z"/></svg>
                        <svg class="check_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"/></svg>
                      </a>
                    </header>
                      <div class="detalles">
                        <div class="precio">
                          <h4>360</h4><span>mensual</span>
                        </div>
                        <div class="horarios">
                          <h4>Seleccione un horario</h4>
                          <table class="horario">
                            <tr>
                              <th>Lunes</th>
                              <th>Martes</th>
                              <th>Miércoles</th>
                              <th>Jueves</th>
                              <th>Viernes</th>
                            </tr>
                            <tr class="t_man">
                              <td></td>
                              <td><span>9:00 - 10:30</span></td>
                              <td></td>
                              <td><span>9:00 - 10:30</span></td>
                              <td></td>
                            </tr>
                            <tr class="t_tar">
                              <td><span>17:00 - 18:30</span></td>
                              <td></td>
                              <td><span>17:00 - 18:30</span></td>
                              <td></td>
                              <td></td>
                            </tr>
                          </table>

                        </div>
                      </div>
                      <div class="clear_r">
                        <a alt="360" href="#" class="inactive boton_ok">Añadir actividad</a>
                      </div>
                  </div>

                  <div class="item_act pilates">
                    <header>
                      <h3>Pilates</h3>
                      <span class="resumen"></span>
                      <a alt="300" class="check">
                        <svg class="x_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M4.93 19.07A10 10 0 1 1 19.07 4.93 10 10 0 0 1 4.93 19.07zm1.41-1.41A8 8 0 1 0 17.66 6.34 8 8 0 0 0 6.34 17.66zM13.41 12l1.42 1.41a1 1 0 1 1-1.42 1.42L12 13.4l-1.41 1.42a1 1 0 1 1-1.42-1.42L10.6 12l-1.42-1.41a1 1 0 1 1 1.42-1.42L12 10.6l1.41-1.42a1 1 0 1 1 1.42 1.42L13.4 12z"/></svg>
                        <svg class="check_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"/></svg></a>
                    </header>
                      <div class="detalles">
                        <div class="precio">
                          <h4>300</h4><span>mensual</span>
                        </div>
                        <div class="horarios">
                          <h4>Seleccione un horario</h4>
                          <table class="horario">
                          <tr>
                            <th>Lunes</th>
                            <th>MArtes</th>
                            <th>Miércoles</th>
                            <th>Jueves</th>
                            <th>Viernes</th>
                          </tr>
                          <tr class="t_man">
                            <td></td>
                            <td><span>9:00 - 10:30</span></td>
                            <td></td>
                            <td><span>9:00 - 10:30</span></td>
                            <td></td>
                          </tr>
                          <tr class="t_tar">
                            <td><span>17:00 - 18:30</span></td>
                            <td></td>
                            <td><span>17:00 - 18:30</span></td>
                            <td></td>
                            <td></td>
                          </tr>
                        </table>
                      </div>
                    </div>
                    <div class="clear_r">
                      <a alt="300" href="#" class="inactive boton_ok">Añadir actividad</a>
                    </div>
                  </div>
                </div>

                <div class="importe">
                  <h3>Importe mensual</h3>
                  <span id="import" class="precio">0</span>
                </div>
              </div>

              <div class="clear_r">
                <a href="#" class="boton_ok inactive">Confirmar</a>
                <input id="back" type="submit" class="boton_pas" value="Volver">
              </div>
            </div> -->
          </div>
      </div>

      </p>
  </div>