<header id="cab">
  
  <?php
    // require('./config/conection.php');
    if($_SESSION['logged_in_user_id'] != '1'){
      //session_start();
      header('Location:http://localhost/SAME_BIT/login.html');
    }
    else{
    }
  ?>
    <!-- 2-Ago-2022
    Aquí se modifica la forma en que se llama el archivo porque paso de ser .html a .php
    -->
    <!--<link href="./index.css" rel="stylesheet" type="text/css">-->
    <style> <?php include 'index.css';?></style> 
    <meta charset="utf-8">
  <div class="maxw">

    <!-- Logo -->
    <div class="top_imagen">
      <img src='../img/SameinLogo.png' alt="logo" width="130px">
    </div>
    
    <nav>
      <!--
           <a href="#">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"><path class="heroicon-ui" d="M12 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm9 11a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3H8a3 3 0 0 0-3 3v2a1 1 0 0 1-2 0v-2a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v2z"/></svg>
        <span>
        </span>
      </a>
      -->
      <!-- <a href="#">Cerrar sesión</a> -->
     <div class="LogOut">
      <span>Cerrar Sesión</span>
      <a href="logout.php"><img src="../img/off.png" alt="logo" width="30px"></a>
     </div>
    </nav>
  </div>
</header>

<div class="maxw content">

  <aside class="">
    <nav>
      <a class="activo" href="#"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M17 16a3 3 0 1 1-2.83 2H9.83a3 3 0 1 1-5.62-.1A3 3 0 0 1 5 12V4H3a1 1 0 1 1 0-2h3a1 1 0 0 1 1 1v1h14a1 1 0 0 1 .9 1.45l-4 8a1 1 0 0 1-.9.55H5a1 1 0 0 0 0 2h12zM7 12h9.38l3-6H7v6zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm10 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg>Pacientes</a>
      <!--<a href="#"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M20 11.46V20a2 2 0 0 1-2 2h-3a2 2 0 0 1-2-2v-4h-2v4a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8.54A4 4 0 0 1 2 8V7a1 1 0 0 1 .1-.45l2-4A1 1 0 0 1 5 2h14a1 1 0 0 1 .9.55l2 4c.06.14.1.3.1.45v1a4 4 0 0 1-2 3.46zM18 12c-1.2 0-2.27-.52-3-1.35a3.99 3.99 0 0 1-6 0A3.99 3.99 0 0 1 6 12v8h3v-4c0-1.1.9-2 2-2h2a2 2 0 0 1 2 2v4h3v-8zm2-4h-4a2 2 0 1 0 4 0zm-6 0h-4a2 2 0 1 0 4 0zM8 8H4a2 2 0 1 0 4 0zm11.38-2l-1-2H5.62l-1 2h14.76z"/></svg> Farmacia </a>-->
      <a href="#"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm0 10v6h14v-6h-2.38l-1.45 2.9a2 2 0 0 1-1.79 1.1h-2.76a2 2 0 0 1-1.8-1.1L7.39 13H5zm14-2V5H5v6h2.38a2 2 0 0 1 1.8 1.1l1.44 2.9h2.76l1.45-2.9a2 2 0 0 1 1.79-1.1H19z"/></svg>Reportes</a>
    </nav>
  </aside>

  <div class="cuerpo">
      <p>
        <div class="formulario">
          <div class="slide">

            <div class="item">
              <h3>DATOS DEL PACIENTE</h3>
              <div class="campos">
                <aside>
                  <a href="#">
                    <svg class="imgperfil" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="90" height="90">
                      <path class="heroicon-ui" d="M12 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm9 11a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3H8a3 3 0 0 0-3 3v2a1 1 0 0 1-2 0v-2a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v2z"/></svg>
                    <span>Seleccionar Foto</span>
                  </a>
                  <a href="#">
                    <svg class="huella" xmlns="http://www.w3.org/2000/svg" width="90" height="90" viewBox="0 0 24 24">
                      <path d="M20.053 15.996c.153.06.234.216.188.365v.002c-.061.194-.291.297-.492.219-1.991-.773-3.259-1.657-3.918-2.716-.822-1.319-.562-2.682-.311-4 .122-.64.237-1.245.237-1.847 0-1.937-1.434-3.104-2.854-3.4-1.425-.297-3.494.152-4.635 2.399-.99 1.949-2.295 2.581-4.834 3.216-.223.055-.441-.105-.434-.32v-.004c.005-.144.11-.267.26-.304 2.57-.646 3.536-1.208 4.377-2.865 1.317-2.593 3.742-3.104 5.417-2.754 2.033.424 3.398 2.045 3.398 4.032 0 .659-.126 1.321-.248 1.96-.241 1.259-.467 2.447.227 3.561.579.928 1.772 1.738 3.622 2.456zm-7.789.97c-.807.516-2.358 1.508-6.463 2.724-.103.031-.186.104-.223.198-.098.245.142.506.415.425 4.219-1.248 5.83-2.278 6.666-2.812.502-.321.572-.318.924-.017.56.479 1.678 1.434 4.659 2.524.155.057.332 0 .416-.134l.001-.002c.108-.173.026-.396-.176-.47-2.846-1.042-3.899-1.942-4.429-2.394-.69-.591-.924-.596-1.79-.042zm2.419-3.852c-.122-.314-.196-.584-.502-.641-.309-.057-.542.156-1.002.581-1.008.929-3.279 3.02-8.553 4.405-.203.053-.309.258-.233.437.064.151.241.234.409.19 5.456-1.43 7.818-3.607 8.867-4.572.198-.182.32-.277.424-.013.422 1.065 1.261 2.771 5.261 4.252.184.068.393-.018.462-.191.064-.162-.022-.342-.195-.406-3.933-1.454-4.57-3.093-4.938-4.042zm-.455 2.115c-.504-.63-.72-.77-1.603-.071-.812.642-2.607 2.062-7.469 3.434-.208.059-.31.278-.213.459.074.14.246.209.406.164 5.008-1.413 6.881-2.893 7.727-3.562.26-.206.432-.239.594-.036.551.688 1.581 1.973 5.176 3.283.173.063.37-.01.449-.167.083-.167-.003-.365-.188-.432-3.414-1.242-4.368-2.432-4.879-3.072zm3.509-2.265c-.546-.958-.359-2.095-.161-3.298.241-1.466.515-3.128-.574-4.685-.922-1.318-2.538-2.209-4.323-2.382-1.821-.178-3.603.408-4.891 1.606-.583.542-.91 1.105-1.226 1.649-.581 1.001-1.094 1.882-3.198 2.359-.143.033-.249.145-.263.282v.001c-.023.221.195.398.428.345 2.409-.551 3.037-1.631 3.646-2.678.292-.503.595-1.024 1.106-1.5 1.139-1.059 2.716-1.577 4.326-1.42 1.579.153 3.004.936 3.812 2.091.944 1.35.703 2.816.469 4.233-.356 2.158-.721 4.38 3.223 5.85.19.071.404-.034.449-.22.039-.163-.055-.329-.221-.391-1.323-.496-2.181-1.105-2.602-1.842zm2.642-3.672c.169-1.294.362-2.76-.207-4.29-1.01-2.72-3.804-4.518-6.893-4.917-.445-.057-.886-.085-1.321-.085-2.839 0-5.398 1.21-7.018 3.369-.439.586-.735 1.091-1.052 1.464-.133.157-.084.394.113.488.147.071.328.035.43-.086.356-.417.642-.911 1.08-1.496 1.714-2.286 4.583-3.412 7.672-3.012 2.977.385 5.462 2.146 6.332 4.487.515 1.388.334 2.775.174 4-.185 1.409-.361 2.761.772 3.533.213.146.516.017.538-.228.011-.122-.045-.242-.148-.317-.779-.566-.65-1.548-.472-2.91zm-1.378.334c.213-1.446.455-3.084-.461-4.824-.936-1.778-2.743-3.028-4.959-3.431-2.355-.428-4.73.18-6.516 1.667-.744.619-1.156 1.272-1.52 1.848-.492.778-.905 1.431-2.007 1.842-.246.091-.296.397-.09.552.098.074.23.092.347.049 1.323-.491 1.825-1.285 2.35-2.115.355-.561.721-1.141 1.384-1.693 1.622-1.351 3.779-1.902 5.918-1.514 2 .363 3.629 1.486 4.467 3.08.822 1.562.596 3.097.397 4.452-.27 1.83-.527 3.576 2.047 4.625.198.081.425-.035.458-.234.026-.153-.063-.303-.215-.366-2.057-.839-1.865-2.141-1.6-3.938zm-6.925 8.949c-.747.42-2.129 1.197-5.467 2.198-.232.069-.319.334-.167.513l.002.002c.087.103.235.145.369.104 3.423-1.026 4.853-1.829 5.624-2.262.681-.383.581-.384 1.271.059.664.427 1.778 1.143 3.772 1.877.152.056.327.01.423-.114l.001-.001c.131-.167.057-.405-.15-.481-1.935-.711-3.01-1.402-3.651-1.814-.894-.574-1.05-.63-2.027-.081zm.243-10.292c.07-.167-.019-.354-.198-.419s-.381.018-.451.184c-1.162 2.772-4.184 4.867-8.124 5.651-.18.036-.298.196-.269.365v.005c.032.181.219.301.414.263 1.852-.369 3.565-1.029 4.977-1.92 1.733-1.093 2.961-2.482 3.651-4.129zm2.595.942c.384-.916.272-1.893-.306-2.681-.586-.799-1.604-1.295-2.655-1.295-1.293 0-2.396.712-2.875 1.858-.792 1.889-2.939 3.356-5.806 3.994-.162.036-.275.173-.269.329v.001c.007.203.213.348.426.301 3.091-.688 5.421-2.301 6.297-4.39.374-.892 1.227-1.446 2.227-1.446.824 0 1.621.388 2.08 1.013.447.608.531 1.367.232 2.08-.846 2.02-2.334 3.712-4.423 5.031-1.62 1.023-3.57 1.779-5.664 2.202-.095.019-.178.074-.227.153s-.062.172-.033.259v.002c.053.161.228.255.403.22 2.181-.439 4.215-1.229 5.91-2.299 2.209-1.394 3.784-3.188 4.683-5.332zm-1.298-.471c.215-.511.157-1.05-.158-1.479-.332-.451-.909-.731-1.506-.731-.717 0-1.308.386-1.578 1.033-.981 2.339-3.584 4.125-7.009 4.832-.171.035-.286.183-.27.344v.006c.021.192.216.324.419.283 3.653-.755 6.441-2.688 7.506-5.229.184-.437.555-.621.93-.621.66 0 1.333.569 1.016 1.327-.743 1.771-2.058 3.261-3.909 4.43-1.49.94-3.295 1.635-5.24 2.021-.192.038-.312.215-.269.393.042.17.224.278.408.241 2.034-.402 3.926-1.131 5.491-2.119 1.973-1.244 3.375-2.836 4.169-4.731zm-1.91 14.158c-.301.115-.695.266-1.229.453-.143.05-.235.181-.228.323.009.178.169.312.339.312.115 0 1.275-.448 1.382-.489.977-.374.851-.373 1.643-.012l.503.227c.215.096.457-.075.462-.294.002-.131-.077-.251-.204-.308l-.456-.206c-.956-.436-.997-.471-2.212-.006zm.223-2.829c-.679.321-1.869.885-4.385 1.657-.116.036-.204.125-.231.237-.06.245.188.458.444.38 2.57-.789 3.792-1.367 4.488-1.697.769-.365.649-.361 1.451.062.6.316 1.525.803 2.888 1.34.259.103.54-.106.482-.363-.023-.104-.099-.19-.204-.231-1.334-.526-2.238-1.002-2.824-1.31-.979-.516-1.063-.571-2.109-.075zm-.113 1.409c-.565.236-1.473.615-3.088 1.132-.126.041-.217.145-.233.268-.032.237.213.424.455.346 1.647-.527 2.575-.914 3.151-1.155.873-.365.747-.367 1.552.023.432.208 1.032.499 1.849.834.25.104.548-.069.515-.328-.014-.11-.09-.206-.199-.25-.818-.334-1.416-.624-1.844-.831-.976-.472-1.032-.51-2.158-.039z"/></svg>
                    <span>Registrar huella digital</span>
                  </a>
                  </aside>
                <form>
<!-- PRIMER BLOQUE DE CAMPOS -->

                  <div class="doble">
                    <!-- DNI User -->
                    <div class="campo">
                      <label for="dni">Documento*</label>
                      <input required type="number" id="dni"/>
                    </div>
                    <!-- Name User -->
                    <div class="campo">
                      <label for="nombre">Nombres*</label>
                      <input required type="text" id="nombre"/>
                    </div>

                    <div class="campo">
                      <label for="apellido">Apellidos*</label>
                      <input required type="text" id="apellido"/>
                    </div>
                    <div class="campo">
                      <label for="celular">Celular*</label>
                      <input required type="tel" minlength="10" maxlength="14" id="celular"/>
                    </div>
                  </div>

<!-- SEGUNDO BLOQUE DE CAMPOS -->

                  <div class="doble">
                    <div class="campo">
                      <label for="fec_Com">Fecha comentario*</label>
                      <input required type="date" id="Fec_Coment"/>
                    </div>
                    
                    <div class="campo">
                      <label for="Com">Hora comentario*</label>
                      <input required type="time" id="Hor_Coment"/>
                    </div>
                    <div class="campo">
                      <label for="Com">Aceptado*</label>
                      
                      <select class="Acpt" id="color">
                          <option value="1">SI</option>
                          <option value="0">NO</option>
                      </select>
                      <!-- <label for="option">Aceptado</label>
                      <input required type="text" id="Acp" name="Acp" value="Aceptado">
                    -->
                    </div>
                    <div class="campo">
                      <label for="Com">Fecha Cita*</label>
                      <input required type="date" id="Hor_Coment"/>
                    </div>
                  </div>
                  
                  <div class="doble">
                    
                    <div class="campo">
                      <label for="email">Email</label>
                      <input type="email" pattern="[^ @]+@[^ @]+.[a-z]+" id="email"/>
                    </div>
                  </div>

                  <div class="doble">
                    <div class="campo">
                      <label for="peso">Peso</label>
                      <input type="number" min="1" max="300" id="peso"/>
                      <span>kg</span>
                    </div>
                    <div class="campo">
                      <label for="altura">Altura</label>
                      <input type="number" min="1" max="3" step="0.01" id="altura"/>
                      <span>metros</span>
                    </div> 
                  </div>

                  <span class="label">Adjuntar ficha médica</span>
                  <input type="file" value="Seleccionar foto" id="file" class="custom-file-input">
                  <div class="clear_r">
                    <input id="continuar" type="submit" class="boton_ok" value="Continuar">
                  </div>
                </form>
              </div>
            </div>

            <div class="item">
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

            </div>

            <div class="item">
              <form>
                <div class="campos">
                  <figure>
                    <h3>Para finalizar, ingrese la clave de cliente.</h3>
                    <div class="formy">
                        <div class="">
                          <span>Usuario</span>
                          <label for="clave_usuario">Clave</label>
                        </div>
                        <div class="">
                          <span class="deni"></span>
                          <input size="10" required id="clave_usuario" type="password" pattern="[0-9]{5,8}" title="La clave debe contener entre 5 y 8 caracteres numéricos." value="">
                        </div>
                    </div>
                    <span class="aclar">La clave debe contener entre 5 y 8 caracteres numéricos.</span>
                  </figure>
                </div>
                <div class="clear_r">
                  <input type="submit" class="boton_ok" value="Confirmar"/>
                </div>
              </form>
            </div>

          </div>
      </div>

      </p>
  </div>