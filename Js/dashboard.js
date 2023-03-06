var user = [];
var user = JSON.parse(localStorage.getItem('user'));


function showCustomDialog(data = '') {

    if (data.length == 0) {
        alert('parametros vacios');
        return false;
    }

    Swal.fire({
        icon: data.icon,
        title: data.title == '' ? data.icon : data.title,
        text: data.text,
        timer: data.time
    });

    return false;
}

function generateDate(date = new Date(), h = 0, m = 0, s = 0, ss = 0) {
    return new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate(), h, m, s, ss));
}

function showReportCard(defaultDate = '') {


    if (defaultDate) {

        let startDate = generateDate(undefined),
            endDate = generateDate(undefined, 23, 59, 59, 59);

        let checkIn_start = startDate.toJSON().slice(0, 19),
            checkIn_end = endDate.toJSON().slice(0, 19);

        $('#checkin-start').val(checkIn_start);
        $('#checkin-end').val(checkIn_end);
    }

    checkInStartToJson = typeof checkIn_start === 'undefined' ? $('#checkin-start').val() : checkIn_start
    checkInEndToJson = typeof checkIn_end === 'undefined' ? $('#checkin-end').val() : checkIn_end

    const postData = {
        dni: $('#dni-request').val(),
        user: $('#user-request').val(),
        checkinStart: checkInStartToJson,
        checkinEnd: checkInEndToJson,
        checkOutStart: $('#checkOut-start').val(),
        checkOutEnd: $('#checkOut-end').val(),
        appointmentStart: $('#appointment-start').val(),
        appointmentEnd: $('#appointment-end').val(),
    };

    $('#recordsSummary').DataTable({

        "ajax": {
            "type": "POST",
            "url": '../config/getPriorities.php',
            "data": postData,
        },

        "columns": [
            { "data": "RECEPCION_CORREO" },
            { "data": "HORA_RECEPCION" },
            { "data": "RESPUESTA_CORREO" },
            { "data": "HORA_RESPUESTA" },
            { "data": "DOCUMENTO" },
            { "data": "PACIENTE" },
            { "data": "ENVIADO_POR" },
            { "data": "IPS" },
            { "data": "EPS" },
            {
                "data": "RANGO",
                "render": function (data, type) {

                    if (type === 'display') {

                        let response_data = '';
                        switch (data) {
                            case "0":
                                response_data = 'A';
                                break;
                            case "1":
                                response_data = 'B';
                                break;
                            case "2":
                                response_data = 'C';
                                break;
                            case "3":
                                response_data = 'Sisben';
                                break;
                            default:
                                break;
                        }
                        return `${response_data}`;
                    }

                    return data;
                }
            },
            { "data": "DIAGNOSTICO" },
            {
                "data": "APROBADO",
                "render": function (data, type) {

                    if (type === 'display') {

                        let response_data = '';
                        switch (data) {
                            case "1":
                                response_data = 'Sí';
                                break;
                            case "0":
                                response_data = 'No';
                                break;
                            default:
                                break;
                        }
                        return `${response_data}`;
                    }

                    return data;
                }
            },
            { "data": "FECHA_CITA" },
            { "data": "HORA_CITA" },
            {
                "data": "ANEXO_9",
                "render": function (data, type) {

                    if (type === 'display') {

                        let response_data = '';
                        switch (data) {
                            case "1":
                                response_data = 'Sí';
                                break;
                            case "0":
                                response_data = 'No';
                                break;
                            default:
                                break;
                        }
                        return `${response_data}`;
                    }

                    return data;
                }
            },
            {
                "data": "ANEXO_10",
                "render": function (data, type) {

                    if (type === 'display') {

                        let response_data = '';
                        switch (data) {
                            case "1":
                                response_data = 'Sí';
                                break;
                            case "0":
                                response_data = 'No';
                                break;
                            default:
                                break;
                        }
                        return `${response_data}`;
                    }

                    return data;
                }
            },
            { "data": "ENVIADO_A" },
            { "data": "COMENTARIO_RECEPCION" },
            { "data": "COMENTARIO_CONTRAREF" },
            { "data": "CREADO_POR" },
            { "data": "DIAS_RESPUESTA" },
            { "data": "HORAS_RESPUESTA" },
            { "data": "DIAS_CITA" },
            { "data": "HORAS_CITA" }
        ],
        "paging": true,
        'scrollY': '300px',
        'scrollX': '300px',
        'scrollCollapse': true,
        'responsive': true,
        'destroy': true,
        "deferRender": true,
        "orderClasses": false,
        "order": [2],
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
        "serverSide": true,
        "language": {
            "url": "http://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        }

    });

    table_id = 'recordsSummary';
    cols_positions = [0, 1, 9, 14, 15, 17, 18, 19, 20, 21, 22, 23];
    hideColums(table_id, cols_positions);


}

function atentionswitch() {

    if ($('#approved').val() != 1) {
        $('#attention-date').val('');
        $('#attention-date').prop("disabled", true);
        return false;
    }

    $('#attention-date').prop("disabled", false);

};


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
        // Capturar el elemnento padre y posterior tomar el atributo almacenado en el triggerClass=pacientid
        let row = $(this).closest("tr");
        let data = $('#table-patients').DataTable().row(row).data();
        let pk_uuid = data['UUID'];

        if (localStorage.getItem('Error')) {
            localStorage.removeItem('Error');
        }

        $.post('../config/usepatient.php', { pk_uuid }, function (response) {

            const patient = JSON.parse(response);
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


$(document).ready(function () {

    if (user == null) {
        location.href = 'http://localhost/samebit/pages/login.php';
        return false;
    } else {
        user = JSON.parse(user);
    }

    cargar_ips();
    cargar_eps();
    atentionswitch();
    cargar_diagnosis();

    //Ocultar barras de busqueda e historico
    $('#search-patients').hide();
    $('#history-patient').hide();

});

$(document).on('change', '#approved', function () {
    atentionswitch();
});

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
                checkInDate: $('#check-in-date').val(),
                commentDate: $('#CommentDate').val(),
                approved: $('#approved').val(),
                AtentionDate: $('#attention-date').val(),
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

                $('#bitregister').trigger('reset');
                $('#search-patients').hide();
                $('#history-patient').hide();


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
    $('#getInformation').trigger('reset');
    let defaultDate = new Date();
    showReportCard(defaultDate);
});

$('#contacttype').on('change', (e) => {
    let value = e.target.value;
    $('.switchTitle').html(value.toUpperCase());

});

$('input[type="date"] , input[type="time"], input[type="datetime-local"]').on('change', (e) => {

    const pullTrigger = e.target,
        triggerClass = (pullTrigger.className).split(' ')[0];

    if (!triggerClass.match('_in|_out')) {
        return false;
    }

    let triggerType = pullTrigger.type,
        triggerClassId = triggerClass.split('_')[0],
        compareInput = triggerClass.match('in') ? 'out' : 'in',
        triggerCall = !pullTrigger.id ? `input.${triggerClass}[type="${triggerType}"]` : `#${pullTrigger.id}`,
        fieldTrigger = $(triggerCall),
        fieldCompare = $(`input.${triggerClassId}_${compareInput}[type="${triggerType}"]`);

    if (fieldTrigger.length === 0 || fieldCompare.length === 0) {
        return false;
    }

    if ((fieldTrigger.length > 0 && !fieldTrigger[0].id) || (fieldCompare.length > 0 && !fieldCompare[0].id)) {
        return false;
    }

    if (fieldTrigger.length > 1 || fieldCompare.length > 1) {

        if (fieldTrigger.length > 1) {
            fieldTrigger = $(`#${fieldTrigger[0].id}`);
        }

        if (fieldCompare.length > 1) {
            fieldCompare = $(`#${fieldCompare[0].id}`);
        }
    }

    let in_field = compareInput == 'in' ? fieldCompare : fieldTrigger,
        out_field = compareInput == 'out' ? fieldCompare : fieldTrigger;

    var in_val = in_field.val(), out_val = out_field.val();

    if (!in_val || !out_val) {
        return false;
    }

    if (in_val >= out_val) {

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

$('#cleanRequest').on('click', (e) => {
    Swal.fire({
        icon: 'warning',
        title: 'Atención',
        text: '¿Está seguro de reiniciar el formularío',
        showCancelButton: true,
        cancelButtonText: 'No',
        showConfirmButton: true,
        confirmButtonText: 'Limpiar',
        dangermode: true,
    }).then((result) => {
        if (result.isConfirmed) {
            $('#getInformation').trigger('reset');
        }
    });
});

function hideColums(table_id, cols_positions) {
    table = $('#' + table_id);
    table = table.DataTable();
    table.columns(cols_positions).visible(false);
}
