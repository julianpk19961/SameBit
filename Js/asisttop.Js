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
    $('#form').on('click keyup', function() {
            var input = document.activeElement.getAttribute("id");
            if(!input){
                return false;
            }
            var select = document.activeElement.parentElement.parentElement.getAttribute("id");
            if (!input && !select){
                return false;
            }else if (input != null && select != null ){
                calculoconsumo(input,select);
            }else{

                if (input.includes('state') == 1 )
                {
                   if( $('#'+input+'').val() == '' ){
                    $('#'+input+'').val(0);
                   }
                   $(this).select();
                   barrasprogreso('#'+input+'')
                }
            }

    });

    $('#modBtn').on('click', function () {

        // Vars
        var modBtn  = $('#modBtn'),
        modal   = $('#modal'),
        close   = modal.find('.close'),
        modContent = modal.find('.modal-content');

        modal.css('display', 'block');
        modContent.removeClass('modal-animated-out').addClass('modal-animated-in');
        
        chart();
      })


      function chart (){

        var names = [];

        var mycategories = [
            "Ingreso",
            "Egreso",
            "En tratamiento",
            "Seguimiento"
          ];

        const colors = [
            "#fe8856","#fec339","#d9242b","#9062a0","#144377","#bafac5","#009975"
        ];
        
        

        var array = $('#Drugs').find('.text').each(function(){

            id = $(this).attr('id');
            row = document.getElementById(id);
            value = row.getAttribute('name');
            names.push(value);

        });

        console.log(names);
        return false;
        
        var myseries1 = [
            // Alcohol
            {
                color: colors[0],
                data: [1,3,5,7],
                stack: "MONEDA",
                name: "Alcohol",
                yAxis: 0
            },
            // Marihuana
            {
                color: colors[1],
                data: [1, 2, 3, 4],
                stack: "OPERACI\u00d3N",
                name: "Marihuana",
                yAxis: 1
            },
            // PBC
            {
                // linkedTo: ":previous",
                color: colors[2],
                data: [5, 10, 15, 20],
                stack: "OPERACI\u00d3N",
                name: "Pasta Base",
                yAxis: 0
              },
            //   Estimulantes
              {
                color: colors[3],
                data: [12, 24, 36, 48],
                stack: "OPERACI\u00d3N",
                name: "Estimulantes",
                yAxis: 1
              },
            //   Sedantes
              {
                color: colors[4],
                data: [12, 24, 36, 48],
                stack: "OPERACI\u00d3N",
                name: "Sedantes",
                yAxis: 0
              },
            //   Opiaceos
              {
                color: colors[5],
                data: [12, 24, 36, 48],
                stack: "OPERACI\u00d3N",
                name: "Opiáceos",
                yAxis: 1
              }
              ,
            //   Ludopatia
              {
                color: colors[6],
                data: [12, 24, 36, 48],
                stack: "OPERACI\u00d3N",
                name: "Ludopatia",
                yAxis: 1
              }

          ];

          var config = {
            chart: {type: "bar"},
            title: {text: "Evolución Consumo"},
            xAxis: {categories: mycategories},

            yAxis: [
                { title: { text: "Consumo"}},// Primary yAxis 
                {title: { text: "cobertura"},opposite: true} // Secondary yAxis
            ],

            tooltip: {
              formatter: function () {
                return (
                  "<b>" +
                  this.x +
                  "</b><br/>" +
                  this.series.name +
                  ": " +
                  this.y +
                  "<br/>" +
                  "Total: " +
                  this.point.stackTotal
                );
              }
            },

            plotOptions: {
              column: { stacking: "normal"}},
            series: []

           };

          config.series = myseries1;

          $("#chart1").highcharts(config);
          $("#chart-container").bind("mousedown", function () {
          $(this).toggleClass("modal");
          $(".chart", this).highcharts().reflow();
          });

      } 




      // close modal when click on close button or somewhere out the modal content 
      $('#modCls').on('click', function(e) {

        var modBtn  = $('#modBtn'),
        modal   = $('#modal'),
        close   = modal.find('.close'),
        modContent = modal.find('.modal-content');

        modal.css('display', 'none');
        modContent.removeClass('modal-animated-in').addClass('modal-animated-out').delay(300);
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
        totalConsumo = 0;


        let und = document.getElementById('und'+ select +'');
        if (und == null ){
            und = '';
        }else{
            und = und.textContent;
        }

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

            dailytotal =  values*daylyAverage;

            // Recalcular valores a formato número.
            $(tag).val(Number(values));
            $(tagdaily).val(Number(dailytotal));
            $(tagdaily).attr("title" , 'Consumo semanal: '+dailytotal + ' '+und);


            // calcular totales.
            total = total + values;
            totalConsumo = totalConsumo + dailytotal;

        });

        $('#MonthTotal'+ select +'').val(Number(total));

        if (und == '') {
            $('#MonthTotal'+ select +'').attr("title" , 'Total días: '+total);
            }else{
            $('#MonthTotal'+ select +'').attr("title" , 'Consumió en total: '+totalConsumo + ' '+und);
            }
    }

    // Función para barras de progreso
    function barrasprogreso(id){
        // obtener el valor del id
        let values = $(id).val();
        let percent = 0;
        let maxvalue = 20;
        let progressId = '_progress-wrap'+id.substring(1,4)+'';


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
        
        // Definir el nivel de peligro
        if (percent <= 29){
                typeBar = "bg-success";
            } else if(percent>= 30 && percent<= 49){
                typeBar = "bg-info";
            }
            else if(percent>= 30 && percent<= 69){
                typeBar = "bg-warning";
            }else{
                typeBar = "bg-danger";
            };

        // Actualizar el valor del input con el valor númerico asignado
        $(id).val( Number(values));

        // Actualización barra de progreso
        $('#'+progressId).css("width",percent+"%");
        // document.getElementById(progressId).innerHTML=''+percent+'%';
        document.getElementById(progressId).className+= "progress-bar"+ " "+typeBar ;

    }

    // Limpiar inputs de formulario
    $('#clearForm').click(()=>{
        var elements = document.getElementsByTagName("input");
        for (var i=0; i < elements.length; i++) {
                console.log(elements[i]);
                console.log(elements[i]).value;
                elements[i].value = "";
        }
        $('#comment').val('');
    });


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