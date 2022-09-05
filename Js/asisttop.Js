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

    // Al finalizar la edición del campo se ejecuta la funcion fecha actual.
    $(document).on('blur','#interviewDate', function(){fechaActual();});

    // Funcion generica para calcular si se debe ejecutar la función de calculo de calculoconsumo, 
    // barra de progreso o solo dejar que el formulario ejecute el evento automático esperado
    $(document).on('click keyup', function() {
            var exit = document.activeElement.getAttribute("id");
            if(!exit){
                return false;
            }
            var select = document.activeElement.parentElement.parentElement.getAttribute("id");
            if (!exit && !select){
                return false;
            }else if (exit != null && select != null ){
                calculoconsumo(exit,select);
            }else{
            
                if (exit.includes('state') == 1 )
                {
                   if( $('#'+exit+'').val() == '' ){
                    $('#'+exit+'').val(0);
                   }
                   $(this).select();
                   barrasprogreso('#'+exit+'')
                }
            }

    });

    // Calcular la cantidad consumida al mes del paciente
    function calculoconsumo (input,select){

        // Verificar el valor de la dosis.
        daylyAverage = Number($('#daylyAverage'+ select +'').val());
        if (!daylyAverage || daylyAverage < 0 ){
            daylyAverage = 0;
        }
        $('#daylyAverage'+ select +'').val(Number(daylyAverage));

        // salir por vacios
        if (!input || !select){
            return false;
        }
        let i = 0;
        let total = 0;
        // let und = $('#und'+ select +'').val();
        // console.log(und);

        $('#'+ select +'').find(':input').each(function(){
            i = i + 1 ;
            tag = ''+ '#week'+i+ select +'';
            tagdaily = ''+ '#week'+i+'daily'+select +'';

            values = Number($(tag).val());
            // Validación de valores.
            if (!values || values < 0 ){
                values = 0; 
            }
            if ( values > 7 ){
                values = 7;
            }
            // Recalcular valores a formato número.
            $(tag).val(Number(values));
            $(tagdaily).val(Number(values*daylyAverage));
            // calcular totales.
            total = total + values;
        });

        $('#MonthTotal'+ select +'').val(Number(total));
        
        
        

    } 
    
    // Función para barras de progreso
    function barrasprogreso(id){
        // obtener el valor del id
        let values = $(id).val();
        let percent = 0;
        let maxvalue = 20;

        // validaciones previas a cálculos
        if (!values ){
            values = 0;
        }
        else if(values>maxvalue){
            values = maxvalue;
        }

        // Calculos
        if (values > 0 ){
            percent = Math.round((values*100)/maxvalue);
        }
        // Actualizar el valor del input con el valor númerico asignado
        $(id).val( Number(values));
        // Actualizar el width de la barra de progreso
        $('#_progress-wrap'+id.substring(1,4)+'').css("width",percent+"%");
    }



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

// Limpiar formulario

    $('#clearForm').click(()=>{
        var elements = document.getElementsByTagName("input");
        for (var ii=0; ii < elements.length; ii++) {
                elements[ii].value = ""; 
        }
    }); 

