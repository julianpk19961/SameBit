var user = [];
var user = JSON.parse(localStorage.getItem('user'));

$(document).ready(function () {

    if (user == null) {
        location.href = 'http://localhost/samebit/pages/login.php';
        return false;
    } else {
        user = JSON.parse(user);
    }

    //Ocultar barras de busqueda e historico
    $('#search-patients').hide();
    $('#history-patient').hide();
});


// Acción para input de número de documento
$(document).on('keyup', '#dni', function () {
    let dni = $('#dni').val();
    // La función se activa cuando el tamaño del input cumpla con minimo 4 caracteres
    if (dni.length >= 4) {

        table = $('#table-patients');

        table.DataTable({

            "ajax": {
                "method": 'POST',
                "data": { dni },
                "url": '../config/getPatients.php',
            },
            "columns": [
                { 'data': 'UUID' },
                { 'data': 'PACIENTE' },
                { 'data': 'DOC_NUMBER' },
                null,
            ],
            "paging": true,
            "scrollCollapse": true,
            "responsive": true,
            "destroy": true,
            "deferRender": true,
            "order": [2]
            ,
            columnDefs: [
                {
                    targets: 3,
                    data: null,
                    defaultContent: "<button class='patient-select btn btn-info' style='width:100%; word-wrap: break-word;'>Selecionar</button>"
                }
            ],
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            }
        });

        table_id = 'table-patients';
        cols_positions = [0];
        hideColums(table_id, cols_positions);

        $('#history-patient').hide();
        $('#search-patients').show();

    } else {
        // ocultar barras de selección e historico
        $('#search-patients').hide();
        $('#history-patient').hide();
    }
});

// Accion: seleccionar paciente del listado [Se ejecuta al oprimir el botón Seleccionar en la tabla generada con la función anterior]
$(document).on('click', '.patient-select', function () {

    if (confirm('¿Está seguro de querer selecionar el paciente')) {
        // Capturar el elemnento padre y posterior tomar el atributo almacenado en el inputClass=pacientid
        let row = $(this).closest("tr");
        let data = $('#table-patients').DataTable().row(row).data();
        let pk_uuid = data['UUID'];

        if (localStorage.getItem('Error')) {
            localStorage.removeItem('Error');
        }

        $.post('../config/usepatient.php', { pk_uuid }, function (response) {

            const patient = JSON.parse(response);
            console.log(patient);
            $('#bitregister').trigger('reset');
            $('#pk_uuid').val(patient.pk_uuid);
            $('#dni').val(patient.dni);
            $('#nombre').val(patient.name);
            $('#apellido').val(patient.lastname);
            $('#documenttype').val(patient.documentType);
            $('#Eps').val(patient.eps);
            $('#ips').val(patient.ips);
            $('#EpsClassification').val(patient.range);
            $('#search-patients').hide();
            $('#history-patient').show();

            cargar_historico(patient.dni);
        });
    }
});


function atentionswitch() {

    let accept = $('#approved').val();
    var AtentionDate = document.getElementById('AtentionDate');
    var AtentionTime = document.getElementById('AtentionTime');

    if (accept == 1) {
        AtentionDate.disabled = false;
        AtentionTime.disabled = false;
    } else {
        AtentionDate.disabled = true;
        AtentionTime.disabled = true;
        document.getElementById('AtentionDate').value = "";
        document.getElementById('AtentionTime').value = "";
    }
};
$(document).ready(atentionswitch);


$(document).on('change', '#approved', function () {
    atentionswitch();
});

function cargar_eps() {
    $.ajax({
        url: '../config/calleps.php',
        type: 'GET',
        success: function (response) {
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
$(document).ready(cargar_eps);

function cargar_ips() {
    $.ajax({
        url: '../config/callips.php',
        type: 'GET',
        success: function (response) {
            let ipslist = JSON.parse(response);
            let template = '<option value="" title="">Seleccione una opción</option>';
            ipslist.forEach(ipslist => {
                template += `
                    <option value=${ipslist.pk_uuid}>${ipslist.name}</option>
                    `
            });
            $('#ips').html(template);
        }
    });
}
$(document).ready(cargar_ips);

function cargar_diagnosis() {
    $.ajax({
        url: '../config/calldiagnosis.php',
        type: 'GET',
        success: function (response) {
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
//Cuando la página esté cargada ejecutará la función.
$(document).ready(cargar_diagnosis);

// Función para cargar el historico
function cargar_historico(dni) {

    $.ajax({
        url: '../config/callhistory.php',
        type: 'POST',
        data: { dni },
        success: function (response) {
            // Sin respuesta
            if (response == 'error') {
                //Ocultar tablas por error.
                $('#history-patient').hide();
                $('#search-patients').hide();

            }
            else {

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
$(document).on('click', '.bit-clean', function () {
    // confirmacion
    if (confirm('¿Está seguro de limpiar el formulario? Los datos no serán recuperados')) {
        // limpiar datos y ocultar tablass
        $('#bitregister').trigger('reset');
        $('#search-patients').hide();
        $('#history-patient').hide();
    }
});

// Accion: Oprimir el botón enviar
$(document).on('submit', '#bitregister', function (event) {
    // confirmacion
    if (confirm('¿Está seguro de enviar el formulario')) {

        //Validar campos vacios
        if ($('#documenttype').val() == '' || $('#dni').val() == '' || $('#nombre').val() == '' || $('#apellido').val() == '' ||
            $('#contacttype').val() == '' || $('#CommentDate').val() == '' || $('#CommentTime').val() == '' || $('#Eps').val() == '' ||
            $('#ips').val() == '' || $('#SentBy').val() == '' || $('#EpsStatus').val() == '' || $('#EpsClassification').val() == '') {
            // Error por campos vacios.
            Swal.fire({
                icon: 'error',
                title: 'Faltan datos',
                text: 'Campos Obligatorios vacios',
                timer: 5000
            });
            return false;
        }
        else {

            // Capturar datos a enviar
            const postData = {

                pk_uuid: $('#pk_uuid').val(),
                dni: $('#dni').val(),
                documenttype: $('#documenttype').val(),
                name: $('#nombre').val(),
                lastname: $('#apellido').val(),
                contacttype: $('#contacttype').val(),
                CommentDate: $('#CommentDate').val(),
                CommentTime: $('#CommentTime').val(),
                checkInDate: $('#check-in-date').val(),
                checkInTime: $('#check-in-time').val(),
                approved: $('#approved').val(),
                AtentionDate: $('#AtentionDate').val(),
                AtentionTime: $('#AtentionTime').val(),
                Eps: $('#Eps').val(),
                ips: $('#ips').val(),
                EpsStatus: $('#EpsStatus').val(),
                EpsClassification: $('#EpsClassification').val(),
                diagnosis: $('#diagnosis').val(),
                CallNumber: $('#CallNumber').val(),
                SentBy: $('#SentBy').val(),
                ObservationIn: $('#ObservationIn').val(),
                exhibitNine: $('#exhibitNine').val(),
                exhibitTen: $('#exhibitTen').val(),
                sendTo: $('#sendTo').val(),
                ObservationOut: $('#ObservationOut').val()

            };

            event.preventDefault();


            $.post('../config/commit.php', postData, function (response) {

                // Error por campos vacios.
                // $('#bitregister').trigger('reset');
                // $('#search-patients').hide();
                // $('#history-patient').hide();


            });
        }


    }
});

$('#reportSamebitModal').on('click', function () {
    if (user.privilegeSet != 'root' && user.privilegeSet != 'administrador') {
        Swal.fire({
            icon: 'error',
            title: 'ACCESO RESTRINGIDO',
            text: 'El usuario no está autorizado',
            timer: 5000
        })
        return false;
    }

    $('#modal-report').modal('show');

    $('#recordsSummary').DataTable({

        "ajax": {
            "method": "GET",
            "url": '../config/getPriorities.php',
        },
        "columns": [
            { "data": "RECEPCION_CORREO" },
            { "data": "RESPUESTA_CORREO" },
            { "data": "HORA_COMENTARIO" },
            { "data": "CC" },
            { "data": "PACIENTE" },
            { "data": "ENVIADO_POR" },
            { "data": "IPS" },
            { "data": "EPS" },
            { "data": "RANGO" },
            { "data": "DIAGNOSTICO" },
            { "data": "APROBADO" },
            { "data": "FECHA_CITA" },
            { "data": "CREADO_POR" },
            { "data": "ANEXO_9" },
            { "data": "ANEXO_10" },
            { "data": "ENVIADO_A" },
            { "data": "COMENTARIO_RECEPCION" },
            { "data": "COMENTARIO_CONTRAREF" },

        ],
        "paging": true,
        'scrollY': '300px',
        'scrollX': '300px',
        'scrollCollapse': true,
        'responsive': true,
        'destroy': true,
        "deferRender": true,
        "orderClasses": false,
        "order": [1],
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: '<i class="bi bi-filetype-xls"></i>',
            className: 'bg-success text-white',
            titleAttr: 'Generar Archivo: Excel',
        }, {
            extend: 'csvHtml5',
            text: '<i class="bi bi-filetype-csv"></i>',
            className: 'bg-info text-white',
            titleAttr: 'Generar Archivo: CSV',
        }, {
            extend: 'pdfHtml5',
            orientation: 'landscape',
            pageSize: 'LEGAL',
            autoWidth: true,
            text: '<i class="bi bi-filetype-pdf"></i>',
            className: 'bg-danger text-white',
            titleAttr: 'Generar Archivo: PDF',
            exportOptions: {
                columns: ':visible'
                // columns: 'th:not(:last-child)'
            }
        }
        ],
        "lengthMenu": [30, 50, 100, 200], /*"All"*/
        // "processing": true,
        "language": {
            "url": "http://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        }

    });

    table_id = 'recordsSummary';
    cols_positions = [0, 7, 12, 13, 14, 15, 16];
    hideColums(table_id, cols_positions);
});

$('#contacttype').on('change', (e) => {
    let value = e.target.value;
    $('.switchTitle').html(value.toUpperCase());

});

function hideColums(table_id, cols_positions) {
    table = $('#' + table_id).DataTable();
    table.columns([cols_positions]).visible(false);
}



$('input[type="date"] , input[type="time"]').on('change', (e) => {

    debugger;
    let inputClass = (e.target.className).split(' ')[0];

    if (!inputClass.match('_in|_out')) {
        return false;
    }

    let fieldName = inputClass.split('_')[0];

    let inFields = $(`${fieldName}`);


    let current_type = e.target.type,
        another_type = current_type == 'date' ? 'time' : 'date';



    let deppendField = inputClass.match('in') ? 'out' : 'in',
        fyelType = e.target.type,
        getField = $(`input.${fieldName}_${deppendField}[type="${fyelType}"]`);

    if (getField.length > 1 || getField.length === 0) {
        return false;
    }

    let fieldTrigger = $(`input.${inputClass}[type="${fyelType}"]`),
        in_field = deppendField == 'in' ? getField : fieldTrigger,
        out_field = deppendField == 'out' ? getField : fieldTrigger;

    if (fieldTrigger.length > 1 || fieldTrigger.length === 0) {
        return false;
    }

    var in_val = in_field.val();
    var out_val = out_field.val();

    console.log(in_val);
    console.log(out_val);

    if (!in_val || !out_val) {
        return false;
    }

    if (in_val > out_val) {
        if (fyelType === 'time') {

            let date_in = $(`input.${fieldName}_in[type="date"]`),
                date_out = $(`input.${fieldName}_out[type="date"]`)



            if (date_in.val().length === 0 || date_out.val().lenght === 0) {
                return false;
            };


        };

        data = {

            'icon': 'error',
            'title': 'Valor no valido',
            'text': 'El valor ingresado no puede ser inferior al valor inicial: ' + in_val,
            'time': '5000',
        };

        out_field.val('');
        in_field.focus();
        showCustomDialog(data);
    }

});


function showCustomDialog(data = '') {

    if (data.length == 0) {
        alert('parametros vacios');
        return false;
    }

    console.log(data);

    Swal.fire({
        icon: data.icon,
        title: data.title == '' ? data.icon : data.title,
        text: data.text,
        timer: data.time
    });

    return false;
}