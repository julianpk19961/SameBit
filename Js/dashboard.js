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

$(document).on('click', '.patient-select', function(){
    if(confirm('¿Está seguro de querer selecionar el paciente')){
        let element = $(this)[0].parentElement.parentElement;
        let PK_UUID = $(element).attr('pacientid');

        $.post('../config/usepatient.php', {PK_UUID}, function(response){
            
            const patient = JSON.parse(response);
            $('#PK_UUID').val(patient.PK_UUID);
            $('#Dni').val(patient.dni);
            $('#nombre').val(patient.name);
            $('#apellido').val(patient.lastname);
            $('#search-patients').hide();
            $('#history-patient').show();
            // edit = true;

            // console.log(patient);

            // fetchTask();
        });
    }
});












$(document).on('click', '#diagnosis', function(){
    $.ajax({
        url: '../config/calldiagnosis.php',
        type: 'GET',
        success: function(response){
            let diagnosis = JSON.parse(response);
            let template = '';
            diagnosis.forEach(diagnosis => {
                template += `
                    <option value=${diagnosis.KP_UUID}>${diagnosis.Codigo}</option>
                    ` 
            });
            $('#diagnosis').html(template);
        }
    });

});