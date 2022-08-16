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