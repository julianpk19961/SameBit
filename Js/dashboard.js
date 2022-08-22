$('#search-patients').hide();
$('#history-patient').hide();


$(document).on('keyup', '#Dni', function(){
    let dni = $('#Dni').val();
    
    if (dni.length >= 5){
        $.ajax({
            url:'../config/dniverification.php',
            type: 'POST',
            data:{dni},
            success: function(response){
                // console.log (response);
                if (response == 'error'){
                    $('#search-patients').hide();
                }
                else{
                    let pacients = JSON.parse(response);
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
                    $('#patients').html(template);
                    $('#search-patients').show();
                }

                // 
              
            }
        });
    }else{
        $('#search-patients').hide();
        console.log ('minimo de carácteres invalidos');
    }
});

$(document).on('click', '.bit-submmmit', function(){
    if(confirm('¿Está seguro de querer selecionar el paciente')){
        
        //Validar campos vacios
        if( $('#documenttype').val().length == 0 || $('#Dni').val().length == 0 || $('#nombre').val().length == 0 || $('#apellido').val().length == 0 
        || $('#contacttype').val().length == 0 || $('#CommentDate').val().length == 0 || $('#CommentTime').val().length == 0 || $('#Eps').val().length == 0 
        || $('#Ips').val().length == 0 || $('#SentBy').val().length == 0 || $('#EpsStatus').val().length == 0 || $('#EpsClassification').val().length == 0
        ){
            
            confirm('Datos obligatorios vacios');
            return;
        }

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
            Observation: $('#Observation').val()
        };

        confirm(postData)

        $.post('../config/commit.php', postData, function(response){

            confirm(response)

        });
    }  
});



$(document).on('click', '.patient-select', function(){
    if(confirm('¿Está seguro de querer selecionar el paciente')){
        let element = $(this)[0].parentElement.parentElement;
        let PK_UUID = $(element).attr('pacientid');

        $.post('../config/usepatient.php', {PK_UUID}, function(response){
            // console.log(response);
            const patient = JSON.parse(response);
            $('#PK_UUID').val(patient.PK_UUID);
            $('#Dni').val(patient.dni);
            $('#nombre').val(patient.name);
            $('#apellido').val(patient.lastname);
            $('#documenttype').val(patient.documentType);
            $('#search-patients').hide();
            $('#history-patient').show();
           
        });
    }
});




$(document).on('click','.bit-clean',function(){
    if(confirm('¿Está seguro de limpiar el formulario? Los datos no serán recuperados')){
        $('#bitregister').trigger('reset');
        $('#search-patients').hide();
        $('#history-patient').hide();
    }
});



function cargar_eps(){
    $.ajax({
        url: '../config/calleps.php',
        type: 'GET',
        success: function(response){
            let eps = JSON.parse(response);
            let template = '<option value="" title="">Seleccione una opción</option>';
            eps.forEach(eps => { 
                template += `
                    <option value=${eps.pk_uuid}>${eps.name} </option>
                    ` 
            });
            $('#Eps').html(template);

        }
    });
}
  //Cuando la página esté cargada ejecutará la función resaltar
$(document).ready(cargar_eps);

function cargar_diagnosis(){
    $.ajax({
        url: '../config/calldiagnosis.php',
        type: 'GET',
        success: function(response){
            let diagnosis = JSON.parse(response);
            let template = '<option value="" title="">Seleccione una opción</option>';
            diagnosis.forEach(diagnosis => {
                template += `
                    <option value=${diagnosis.KP_UUID} title=${diagnosis.Observation}>${diagnosis.Codigo}</option>
                    ` 
            });

            $('#diagnosis').html(template);
        }
    });
}
$(document).ready(cargar_diagnosis);

function cargar_ips(){
    $.ajax({
        url: '../config/callips.php',
        type: 'GET',
        success: function(response){
            // console.log(response);
            let ipslist = JSON.parse(response);
            let template = '<option value="" title="">Seleccione una opción</option>';
            ipslist.forEach(ipslist => {
                template += `
                    <option value=${ipslist.pk_uuid}>${ipslist.name}</option>
                    ` 
            });
            $('#Ips').html(template);
        }
    });
}
$(document).ready(cargar_ips);