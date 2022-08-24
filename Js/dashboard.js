//Ocultar barras de busqueda e historico
$('#search-patients').hide();
$('#history-patient').hide();

// Acción para input de número de documento
$(document).on('keyup', '#Dni', function(){
    let dni = $('#Dni').val();
    // La función se activa cuando el tamaño del input cumpla con minimo 5 caracteres
    if (dni.length >= 4){
        // Metodo post con ajax para consulta de DNI
        $.ajax({
            // script para verificación de cc existentes
            url:'../config/dniverification.php',
            type: 'POST',
            data:{dni},
            // Funcion para recorrer los resultados y dibujarlos en pantalla o seleccionarlo para ser usado (Cuando solo sea una registro)
            success: function(response){
                // Sin respuesta
                if (response == 'error'){
                    $('#search-patients').hide();
                }
                else{
                    // Decomponer el json que se capturo en el script ejecutado y medir su cantidad de resultados
                    let pacients = JSON.parse(response);
                    let Cantpacients = pacients.length;
                    var thedate = Date().getTime();
                    confirm (thedate.toString());

                    if ( Cantpacients == 1 ){
                        // Cuando solo obtenga un resultado se selecciona automaticamente
                        pacients.forEach(pacients => {
                        $('#PK_UUID').val(pacients.PK_UUID);
                        $('#Dni').val(pacients.dni);
                        $('#nombre').val(pacients.Name);
                        $('#apellido').val(pacients.LastName);
                        $('#documenttype').val(pacients.documentType);
                        });
                        // Ocultar el cuadro de selección y mostrar el cuadro del historico
                        cargar_historico();               
                        
                    }else{
                        // Cuando exista más de un resultado, el sistema debe dibujar las opciones para ser seleccionadas
                        let template = '';
                        pacients.forEach(pacients => {
                            template += 
                                `<tr pacientid="${pacients.PK_UUID}">
                                <td style="display:none;">${pacients.PK_UUID}</td>
                                <td style="vertical-align:middle;" >${pacients.dni} </td>
                                <td style="vertical-align:middle;" >${pacients.Name} ${pacients.LastName}</td>
                                <td ><button class="patient-select btn btn-info" style="width:100%; word-wrap: break-word;">Selecionar</button>
                                </td>
                                </tr>`
                        });
                        // Mostrar el template en la etiqueta pacientes
                        // Mostrar cuadro de selección y ocultar cuadro de historico
                        $('#patients').html(template);
                        $('#search-patients').show();
                        $('#history-patient').hide();
                    }
                }
            }
        });
    }else{
        // ocultar barras de selección e historico
        $('#search-patients').hide();
        $('#history-patient').hide();
    }
});

// Accion: seleccionar paciente del listado [Se ejecuta al oprimir el botón Seleccionar en la tabla generada con la función anterior]
$(document).on('click', '.patient-select', function(){
    if(confirm('¿Está seguro de querer selecionar el paciente')){
        // Capturar el elemnento padre y posterior tomar el atributo almacenado en el id=pacientid
        let element = $(this)[0].parentElement.parentElement;
        let PK_UUID = $(element).attr('pacientid');

        // Ajax para solicitar los datos del paciente desde script 
        $.post('../config/usepatient.php', {PK_UUID}, function(response){
            // Establecer los datos del Json encontrado en los campos indicados.
            const patient = JSON.parse(response);
            $('#PK_UUID').val(patient.PK_UUID);
            $('#Dni').val(patient.dni);
            $('#nombre').val(patient.name);
            $('#apellido').val(patient.lastname);
            $('#documenttype').val(patient.documentType);
            $('#search-patients').hide();
            $('#history-patient').show();
           
        });
         
        cargar_historico();
    }
});


// Funcion para habilitar e inhabilitar campos de atencion  dependiedo de la casilla aceptado
function atentionswitch(){

    // se captura el valor de el input asi como los dos campos que tendran el switch
    let accept = $('#approved').val();
    var AtentionDate = document.getElementById('AtentionDate');
    var AtentionTime = document.getElementById('AtentionTime');

    if(accept == 1){
    // si el valor es igual a 1 en aprobado, el sistema habilitara los campos
        AtentionDate.disabled = false;
        AtentionTime.disabled = false;
    }else{
    // si el valor es igual !a 1 en aprobado, el sistema inhabilitara los campos
        AtentionDate.disabled = true;
        AtentionTime.disabled = true;
        document.getElementById('AtentionDate').value ="";
        document.getElementById('AtentionTime').value ="";

    }
};
  //Cuando la página esté cargada ejecutará la función.
$(document).ready(atentionswitch);


// Lllamado a la funcion de inactivar o activar casillas de atencion depndiendo del contenido de el input aprobado
$(document).on('change','#approved',function(){
    atentionswitch();
});

// Funcion creada para llamar las eps registradas en la base de datos
function cargar_eps(){

    // Ajax para llamado de datos mediante metodo get para llamar eps
    $.ajax({
        url: '../config/calleps.php',
        type: 'GET',
        success: function(response){
            // Descomponer datos llamados en opciones de selección 
            let eps = JSON.parse(response);
            let template = '<option value="" title="">Seleccione una opción</option>';
            eps.forEach(eps => {
                // Recorrer Json 
                template += `
                    <option value=${eps.pk_uuid}>${eps.name} </option>
                    `
            });
            // Dibujar opciones en eps 
            $('#Eps').html(template);
        }
    });
}
  //Cuando la página esté cargada ejecutará la función.
$(document).ready(cargar_eps);

// Funcion creada para llamar las ips registradas en la base de datos
function cargar_ips(){
    // Ajax para llamado de datos mediante metodo get para llamar ips
    $.ajax({
        url: '../config/callips.php',
        type: 'GET',
        success: function(response){
            // Descomponer datos llamados en opciones de selección 
            let ipslist = JSON.parse(response);
            let template = '<option value="" title="">Seleccione una opción</option>';
            ipslist.forEach(ipslist => {
                // Recorrer Json 
                template += `
                    <option value=${ipslist.pk_uuid}>${ipslist.name}</option>
                    ` 
            });
            // Dibujar opciones en ips
            $('#Ips').html(template);
        }
    });
}
//Cuando la página esté cargada ejecutará la función.
$(document).ready(cargar_ips);

// Funcion creada para llamar los diagnosticos registrados en la base de datos
function cargar_diagnosis(){
    // Ajax para llamado de datos mediante metodo get para llamar diagnosticos
    $.ajax({
        url: '../config/calldiagnosis.php',
        type: 'GET',
        success: function(response){
            // Descomponer datos llamados en opciones de selección
            let diagnosis = JSON.parse(response);
            let template = '<option value="" title="">Seleccione una opción</option>';
            diagnosis.forEach(diagnosis => {
                // Recorrer Json y ajustarlos como opciones de un select
                template += `
                    <option value=${diagnosis.KP_UUID} title=${diagnosis.Observation}>${diagnosis.Codigo}</option>
                    ` 
            });
            // Dibujar opciones en diagnostico
            $('#diagnosis').html(template);
        }
    });
}
//Cuando la página esté cargada ejecutará la función.
$(document).ready(cargar_diagnosis);

// Función para cargar el historico
function cargar_historico(){
    // Establezco el valor de la CC
    let dni = $('#Dni').val();
    
    $.ajax({
        url:'../config/callhistory.php',
        type: 'POST',
        data:{dni},
        success: function(response){
            // Sin respuesta
            if (response == 'error'){
                //Ocultar tablas por error.
                $('#history-patient').hide();
                $('#search-patients').hide();
                
            }
            else{
                
                // Decomponer el json que se capturo en el script ejecutado y medir su cantidad de resultados
                let history = JSON.parse(response);
                    // Cuando exista más de un resultado, el sistema debe dibujar las opciones para ser seleccionadas
                let template = '';
                history.forEach(history => {
                    template += 
                        `<tr>
                        <td style="vertical-align:middle;">${history.commentdate} </td>
                        <td style="vertical-align:middle;">${history.commenttime} </td>
                        <td style="vertical-align:middle;">${history.createdUser}</td>
                        <td style="vertical-align:middle;">${history.comment0}</td>
                        </tr>`
                });
                // Mostrar el template en la etiqueta pacientes
                // Mostrar cuadro de selección y ocultar cuadro de historico
                $('#patienshistory').html(template);
                $('#search-patients').hide();
                $('#history-patient').show();
                }
        }
    });
}

// Acción: clic en el botón de limpiar formulario
$(document).on('click','.bit-clean',function(){
    // confirmacion
    if(confirm('¿Está seguro de limpiar el formulario? Los datos no serán recuperados')){
        // limpiar datos y ocultar tablass
        $('#bitregister').trigger('reset');
        $('#search-patients').hide();
        $('#history-patient').hide();
    }
});

// Accion: Oprimir el botón enviar
$(document).on('click', '.bit-submmmit', function(){
    // confirmacion
    if(confirm('¿Está seguro de enviar el formulario')){
        
        //Validar campos vacios
        if( $('#documenttype').val().length == 0 || $('#Dni').val().length == 0 || $('#nombre').val().length == 0 || $('#apellido').val().length == 0 
        || $('#contacttype').val().length == 0 || $('#CommentDate').val().length == 0 || $('#CommentTime').val().length == 0 || $('#Eps').val().length == 0 
        || $('#Ips').val().length == 0 || $('#SentBy').val().length == 0 || $('#EpsStatus').val().length == 0 || $('#EpsClassification').val().length == 0
        ){
            // Error campos vacois 
            confirm('Datos obligatorios vacios');
            return;
        }

        // Capturar datos a enviar
        const postData = {
            pk_uuid: $('#PK_UUID').val(),
            dni: $('#Dni').val(),
            documenttype:$('#documenttype').val(),
            name: $('#nombre').val(),
            lastname: $('#apellido').val(),
            contacttype: $('#contacttype').val(),
            CommentDate: $('#CommentDate').val(),
            CommentTime: $('#CommentTime').val(),
            approved: $('#approved').val(),
            AtentionDate: $('#AtentionDate').val(),
            AtentionTime: $('#AtentionTime').val(),
            Eps: $('#Eps').val(),
            Ips: $('#Ips').val(),
            EpsStatus: $('#EpsStatus').val(),
            EpsClassification: $('#EpsClassification').val(),
            diagnosis: $('#diagnosis').val(),
            CallNumber: $('#CallNumber').val(),
            SentBy: $('#SentBy').val(),
            Observation: $('#Observation0').val()
        };

        // confirm(postData)
        // Ajax para envio de datos 
        $.post('../config/commit.php', postData, function(response){
            confirm(response)
            $('#bitregister').trigger('reset');
            $('#search-patients').hide();
            $('#history-patient').hide();

        });
    }  
});