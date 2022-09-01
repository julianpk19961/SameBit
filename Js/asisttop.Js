//VALIDACIÓN: LA FECHA DEL FORMATO SIEMPRE SE LLENE CON EL VALOR DE LA FECHA ACTUAL
    // Se inactiva campo por propuesta a equipo de trabajo
    // Capturar fecha
    var d = new Date();

    // Ejecutar función: validar que la fecha del formato nunca sea vacio
    fechaActual ();

    function fechaActual (){
        let fechaFormulario = $('#interviewDate').val();
    //    preguntar por la posibilidad de crear formularios para días anteriores
        if ( fechaFormulario == '' ){
            year = '' + d.getFullYear(),
            month = '' + (d.getMonth() +1 )
            day = ''+ d.getDate();

            if (month.length < 2){
                month = '0' + month;
            }
            if (day.length < 2) {
                day = '0' + day;
            }
            $('#interviewDate').val(year + '-' + month + '-' + day);
        }
    }

    // al finalizar la edición del campo se ejecuta la funcion fecha actual
    $(document).on('blur','#interviewDate', function(){fechaActual();});


    
    
    
   
    
    
    
    
    
    
    
    // estado psicologico
    $('#psychologicalstate').on('click', function() {
        $(this).select();
    });
    // estado fisico
    $('#physicalstate').on('click', function() {
        $(this).select();
    });
    // estado vivienda
    $('#housestate').on('click', function() {
        $(this).select();
    });

    // Lanza función para barra de progreso del estado psicologico, se valida que no se envíe valor vacio
    $('#psychologicalstate').on('keyup',function(){
        // capturar el valor ingresado en el input
        let values = $('#psychologicalstate').val();
        let percent = 0;
        let maxvalue = 20;

        // Si el valor está vacio lo establece como 0
        if (!values ){
            values = 0;
        }
        else if(values>maxvalue){
            values = maxvalue;
        }

        if (values > 0 ){
            percent = Math.round((values*100)/maxvalue);
        }

        $('#psychologicalstate').val( values);  
        $('#_progress-wrappsy').css("width",percent+"%");
    });

    $('#physicalstate').on('keyup',function(){
        // capturar el valor ingresado en el input
        let values = $('#physicalstate').val();
        let percent = 0;
        let maxvalue = 20;

        // Si el valor está vacio lo establece como 0
        if (!values ){
            values = 0;
        }
        else if(values>maxvalue){
            values = maxvalue;
        }

        if (values > 0 ){
            percent = Math.round((values*100)/maxvalue);
        }

        $('#physicalstate').val( values);  
        $('#_progress-wrapphy').css("width",percent+"%");
    });

    $('#housestate').on('keyup',function(){
        // capturar el valor ingresado en el input
        let values = $('#housestate').val();
        let percent = 0;
        let maxvalue = 20;

        // Si el valor está vacio lo establece como 0
        if (!values ){
            values = 0;
        }
        else if(values>maxvalue){
            values = maxvalue;
        }

        if (values > 0 ){
            percent = Math.round((values*100)/maxvalue);
        }

        $('#housestate').val( values);  
        $('#_progress-wraphou').css("width",percent+"%");
    });
    
    




// $(document).ready(atentionswitch);

// // $("input:radio[name='pregunta']:checked").val();

// guardar todos los datos.
// $('#saveForm').click(() => {
//     var datas = {
//         tda: $('#tipoAtencion'),
//         fac: $('#fechaActual'),
//         fat: $('#fechaAtencion'),
//         man: $('#nombreCompleto'),
//         edd: $('#edadEncuestado'),
//         idn: $('#idIdentificacion'),
//         tel: $('#telefono'),
//         eps: $('#epsPaciente'),
//         p01: $('input:radio[name=pregunta1]:checked'),
//         p02: $('input:radio[name=pregunta2]:checked'),
//         p03: $('input:radio[name=pregunta3]:checked'),
//         p04: $('input:radio[name=pregunta4]:checked'),
//         p05: $('input:radio[name=pregunta5]:checked'),
//         p06: $('input:radio[name=pregunta6]:checked'),
//         p07: $('input:radio[name=pregunta7]:checked'),
//         p08: $('input:radio[name=pregunta8]:checked'),
//         p09: $('input:radio[name=pregunta9]:checked'),
//         p10: $('input:radio[name=pregunta10]:checked'),
//         p11: $('input:radio[name=pregunta11]:checked'),
//         p12: $('input:radio[name=pregunta12]:checked'),
//         p13: $('#pregunta13'),
//         p14: $('#pregunta14'),
//         com: $('#comentarios'),
//     }
//     console.log('ejecutando...')
//     console.log(datas)
//     console.log(d)
// });

// Estado psicologico

// Seleccionar todo el contenido de un input

// $('#clearForm').click(()=>{
//   alert('hola mundos')
// });

