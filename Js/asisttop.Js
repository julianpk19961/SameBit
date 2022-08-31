var d = new Date();

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


//   Lanzar funciones y validar valores vacios

    // Lanza función para barra de progreso del estado psicologico, se valida que no se envíe valor vacio
    $('#psychologicalstate').on('keyup',function(){
        // capturar el valor ingresado en el input
        let values = $('#psychologicalstate').val();
        // Si el valor está vacio lo establece como 0
        if (!values ){
            $('#psychologicalstate').val(0);
        }
        moveProgressBarPsy();
    });
     // Lanza función para barra de progreso del estado fisico, se valida que no se envíe valor vacio
    $('#physicalstate').on('keyup',function(){
        // capturar el valor ingresado en el input
        let values = $('#physicalstate').val();
        // Si el valor está vacio lo establece como 0
        if (!values ){
            $('#physicalstate').val(0);
        }
        moveProgressBarPhy();
    });
     // Lanza función para barra de progreso del estado familiar o de vivienda, se valida que no se envíe valor vacio
    $('#housestate').on('keyup',function(){
        // capturar el valor ingresado en el input
        let values = $('#housestate').val();
        // Si el valor está vacio lo establece como 0
        if (!values ){
            $('#housestate').val(0);
        }
        moveProgressBarHou();
    });

//  Funciones para barra de progreso
    // Barra de progreso estado psicologico
    function moveProgressBarPsy() {
        var progressPercent = $('#psychologicalstate').val();
        var limit = 20;

        if (progressPercent > limit){
            progressPercent = limit;
            $('#psychologicalstate').val(limit);
        }
        var getPercent = ( progressPercent / limit);
        var getProgressWrapWidth = $('#progress-wrappsy').width();
        var progressTotal = getPercent * getProgressWrapWidth;
        var animationLength = 1000;
    
        // on page load, animate percentage bar to data percentage length
        // .stop() used to prevent animation queueing
        $('#progress-barpsy').stop().animate({ 
            left: progressTotal
        }, 
        animationLength);
    }

    // Barra de progreso estado físico
    function moveProgressBarPhy() {
        var progressPercent = $('#physicalstate').val();
        var limit = 20;

        if (progressPercent > limit){
            progressPercent = limit;
            $('#physicalstate').val(limit);
        }
        var getPercent = ( progressPercent / limit);
        var getProgressWrapWidth = $('#progress-wrapphy').width();
        var progressTotal = getPercent * getProgressWrapWidth;
        var animationLength = 1000;
        
        // on page load, animate percentage bar to data percentage length
        // .stop() used to prevent animation queueing
        $('#progress-barphy').stop().animate({ 
            left: progressTotal
        }, 
        animationLength);
    }

    // Barra de progreso estado hogar / situacion convivencia
    function moveProgressBarHou() {
        var progressPercent = $('#housestate').val();
        var limit = 20;

        if (progressPercent > limit){
            progressPercent = limit;
            $('#housestate').val(limit);
        }
        var getPercent = ( progressPercent / limit);
        var getProgressWrapWidth = $('#progress-wrahou').width();
        var progressTotal = getPercent * getProgressWrapWidth;
        var animationLength = 1000;
        
        // on page load, animate percentage bar to data percentage length
        // .stop() used to prevent animation queueing
        $('#progress-barhou').stop().animate({ 
            left: progressTotal
        }, 
        animationLength);
    }






// $('#clearForm').click(()=>{
//   alert('hola mundos')
// });

