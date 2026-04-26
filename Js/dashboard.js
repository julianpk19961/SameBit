var user = JSON.parse(localStorage.getItem('user'));

// ====== NAVEGACIÓN DEL DASHBOARD ======
function showMenu() {
    // Ocultar todas las secciones
    $('#section-registro').hide();
    $('#history-patient').hide();
    
    // Mostrar menú principal (si existe)
    // El menú está al inicio, solo aseguramos que sea visible
    document.querySelector('.container-fluid') ? document.querySelector('.container-fluid').style.display = 'block' : null;
}

function showRegistroSection() {
    // Ocultar menú
    const menuContainer = document.querySelectorAll('.container-fluid')[0];
    if (menuContainer && menuContainer.querySelector('.card')) {
        menuContainer.style.display = 'none';
    }
    
    // Mostrar formulario de registro
    $('#section-registro').show();
    $('#history-patient').hide();
    cargar_hoy();
    $('#search-patients').show();
    window.scrollTo(0, 0);
}

function showReportesSection() {
    // Ocultar menú
    const menuContainer = document.querySelectorAll('.container-fluid')[0];
    if (menuContainer && menuContainer.querySelector('.card')) {
        menuContainer.style.display = 'none';
    }
    
    // Mostrar tabla de reportes
    $('#section-registro').hide();
    // Scroll a la tabla de reportes
    window.scrollTo(0, document.querySelector('#table-resumen')?.offsetTop || 0);
}

// ====== EVENT LISTENERS DE NAVEGACIÓN ======
$(document).on('click', '#btn-back-to-menu', function (e) {
    e.preventDefault();
    showMenu();
});

$(document).on('click', '#btn-new-record-card', function (e) {
    location.href = './calls.php';
});

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
    let checkInStartToJson, checkInEndToJson;

    if (defaultDate) {

        let startDate = generateDate(defaultDate),
            endDate = generateDate(defaultDate, 23, 59, 59, 59);

        let checkIn_start = startDate.toJSON().slice(0, 19),
            checkIn_end = endDate.toJSON().slice(0, 19);

        $('#checkout-start').val(checkIn_start);
        $('#checkout-end').val(checkIn_end);
    }

    if ($('#checkin-start').length) {
        checkInStartToJson = typeof checkIn_start === 'undefined' ? $('#checkin-start').val() : checkIn_start;
    }
    if ($('#checkin-end').length) {
        checkInEndToJson = typeof checkIn_end === 'undefined' ? $('#checkin-end').val() : checkIn_end;
    }

    const postData = {
        dni: $('#dni-request').val(),
        user: $('#user-request').val(),
        checkinStart: checkInStartToJson,
        checkinEnd: checkInEndToJson,
        checkOutStart: $('#checkout-start').val(),
        checkOutEnd: $('#checkout-end').val(),
        appointmentStart: $('#appointment-start').val(),
        appointmentEnd: $('#appointment-end').val(),
    };

    $('#table-resumen').DataTable({

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
                            case "0": response_data = 'A'; break;
                            case "1": response_data = 'B'; break;
                            case "2": response_data = 'C'; break;
                            case "3": response_data = 'Sisben'; break;
                            default: break;
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
                            case "1": response_data = 'Sí'; break;
                            case "0": response_data = 'No'; break;
                            default: break;
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
                            case "1": response_data = 'Sí'; break;
                            case "0": response_data = 'No'; break;
                            default: break;
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
                            case "1": response_data = 'Sí'; break;
                            case "0": response_data = 'No'; break;
                            default: break;
                        }
                        return `${response_data}`;
                    }
                    return data;
                }
            },
            { "data": "ENVIADO_A" },
            {
                "data": "COMENTARIO_RECEPCION",
                "render": function (data, type, row) {
                    if (type === 'display' && data && data.length > 50) {
                        return data.substr(0, 50) + '...';
                    }
                    return data;
                }
            },
            {
                "data": "COMENTARIO_CONTRAREF",
                "render": function (data, type, row) {
                    if (type === 'display' && data && data.length > 50) {
                        return data.substr(0, 50) + '...';
                    }
                    return data;
                }
            },
            { "data": "CREADO_POR" },
            { "data": "DIAS_RESPUESTA" },
            { "data": "HORAS_RESPUESTA" },
            { "data": "DIAS_CITA" },
            { "data": "HORAS_CITA" }
        ],
        "destroy": true,
        "paging": true,
        "serverSide": false,
        "scrollY": '300px',
        "scrollX": '300px',
        "scrollCollapse": true,
        "responsive": true,
        "deferRender": true,
        "orderClasses": false,
        "processing": true,
        "order": [2],
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: '<i class="bi bi-filetype-xls"></i>',
            className: 'bg-success text-white',
            titleAttr: 'Generar Archivo: Excel',
            exportOptions: { orthogonal: 'export' }
        }, {
            extend: 'csvHtml5',
            text: '<i class="bi bi-filetype-csv"></i>',
            className: 'bg-info text-white',
            titleAttr: 'Generar Archivo: CSV',
            exportOptions: { orthogonal: 'export' }
        }],
        "lengthMenu": [30, 50, 100, 200],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        },
        "initComplete": function () {
            $('#table-resumen').DataTable().columns([0, 1, 9, 14, 15, 17, 18, 19, 20, 21, 22, 23]).visible(false);
        }
    });
}

function atentionswitch() {
    if ($('#approved').val() != 1) {
        $('#attention-date').val('');
        $('#attention-date').prop("disabled", true);
        return false;
    }
    $('#attention-date').prop("disabled", false);
}

function cargar_ips() {
    $.ajax({
        url: '../config/callips.php',
        type: 'GET',
        success: function (response) {
            let ipslist = JSON.parse(response);
            let template = '<option value="">Seleccione una opción</option>';
            ipslist.forEach(item => {
                template += `<option value="${item.pk_uuid}">${item.name}</option>`;
            });
            $('#ips').html(template);
        },
        error: function () {
            console.error('Error cargando IPS');
        }
    });
}

function cargar_eps() {
    $.ajax({
        url: '../config/callEps.php',
        type: 'GET',
        success: function (response) {
            let eps = JSON.parse(response);
            let template = '<option value="">Seleccione una opción</option>';
            eps.forEach(item => {
                template += `<option value="${item.pk_uuid}">${item.name}</option>`;
            });
            $('#eps').html(template);
        },
        error: function () {
            console.error('Error cargando EPS');
        }
    });
}

function cargar_diagnosis() {
    $.ajax({
        url: '../config/calldiagnosis.php',
        type: 'GET',
        success: function (response) {
            let diagnosis = JSON.parse(response);
            let template = '<option value="">Seleccione una opción</option>';
            diagnosis.forEach(item => {
                template += `<option value="${item.KP_UUID}" title="${item.Observation}">${item.Codigo}</option>`;
            });
            $('#diagnosis').html(template);
        },
        error: function () {
            console.error('Error cargando diagnósticos');
        }
    });
}

function cargar_hoy() {
    $('#table-patients').DataTable({
        ajax: {
            method: 'GET',
            url: '../config/getTodayPriorities.php',
        },
        columns: [
            { data: 'UUID' },
            { data: 'PACIENTE' },
            { data: 'DOC_NUMBER' },
            null,
        ],
        paging: true,
        scrollCollapse: true,
        responsive: true,
        destroy: true,
        deferRender: true,
        order: [[1, 'asc']],
        columnDefs: [
            { targets: 0, visible: false },
            {
                targets: 3,
                data: null,
                defaultContent: "<button class='patient-select btn btn-info' style='width:100%; word-wrap: break-word;'>Seleccionar</button>"
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json'
        }
    });
}

// Carga historial del paciente en #tbody-historial sin mostrar el panel
function cargar_historico(dni) {
    $.ajax({
        url: '../config/callhistory.php',
        type: 'POST',
        data: { dni },
        success: function (response) {
            if (response == 'error') {
                $('#tbody-historial').html('');
                return;
            }
            let history = JSON.parse(response);
            let template = '';
            history.forEach(item => {
                template +=
                    `<tr>
                    <td style="vertical-align:middle;">${item.commentdate}</td>
                    <td style="vertical-align:middle;">${item.commenttime}</td>
                    <td style="vertical-align:middle;">${item.createdUser}</td>
                    <td style="vertical-align:middle;">${item.comment0}</td>
                    </tr>`;
            });
            $('#tbody-historial').html(template);
        }
    });
}

// Búsqueda por número de documento
$(document).on('keyup', '#dni', function () {
    let dni = $('#dni').val();
    if (dni.length >= 4) {

        $('#table-patients').DataTable({
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
            "order": [2],
            columnDefs: [
                { targets: 0, visible: false },
                {
                    targets: 3,
                    data: null,
                    defaultContent: "<button class='patient-select btn btn-info' style='width:100%; word-wrap: break-word;'>Seleccionar</button>"
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            }
        });

        $('#history-patient').hide();
        $('#search-patients').show();

    } else {
        cargar_hoy();
        $('#search-patients').show();
        $('#history-patient').hide();
    }
});

// Seleccionar paciente del listado
$(document).on('click', '.patient-select', function () {

    if (confirm('¿Está seguro de querer seleccionar el paciente?')) {
        let row = $(this).closest("tr");
        let data = $('#table-patients').DataTable().row(row).data();
        let pk_uuid = data['UUID'];

        if (localStorage.getItem('Error')) {
            localStorage.removeItem('Error');
        }

        $.post('../config/usepatient.php', { pk_uuid }, function (response) {

            const patient = JSON.parse(response);
            $('#form-registro').trigger('reset');
            $('#pk-uuid').val(patient.pk_uuid);
            $('#dni').val(patient.dni);
            $('#nombre').val(patient.name);
            $('#apellido').val(patient.lastname);
            $('#document-type').val(patient.documentType);
            $('#eps').val(patient.eps);
            $('#ips').val(patient.ips);
            $('#eps-classification').val(patient.range);
            $('#search-patients').hide();
            $('#history-patient').hide();

            cargar_historico(patient.dni);
        });
    }
});


$(document).ready(function () {

    if (user == null) {
        location.href = '/pages/login.php';
        return false;
    }

    cargar_ips();
    cargar_eps();
    atentionswitch();
    cargar_diagnosis();

    $('#section-registro').hide();
    $('#history-patient').hide();
    cargar_hoy();
    $('#search-patients').show();

});

// Reportes: muestra la tabla de reportes
$(document).on('click', '#btn-reportes', function (e) {
    e.preventDefault();
    showReportesSection();
});

// Registro Novedades: muestra el formulario de registro
$(document).on('click', '#btn-new-record', function (e) {
    e.preventDefault();
    showRegistroSection();
});

$(document).on('change', '#approved', function () {
    atentionswitch();
});

// Limpiar formulario
$(document).on('click', '.bit-clean', function () {
    if (confirm('¿Está seguro de limpiar el formulario? Los datos no serán recuperados')) {
        $('#form-registro').trigger('reset');
        $('#tbody-historial').html('');
        $('#history-patient').hide();
        cargar_hoy();
        $('#search-patients').show();
    }
});

// Enviar formulario
$(document).on('submit', '#form-registro', function (event) {
    if (confirm('¿Está seguro de enviar el formulario?')) {

        if ($('#document-type').val() == '' || $('#dni').val() == '' || $('#nombre').val() == '' || $('#apellido').val() == '' ||
            $('#contact-type').val() == '' || $('#comment-date').val() == '' || $('#eps').val() == '' ||
            $('#ips').val() == '' || $('#sent-by').val() == '' || $('#eps-status').val() == '' || $('#eps-classification').val() == '') {
            Swal.fire({
                icon: 'error',
                title: 'Faltan datos',
                text: 'Campos obligatorios vacíos',
                timer: 5000
            });
            return false;
        }

        const postData = {
            pk_uuid:           $('#pk-uuid').val(),
            dni:               $('#dni').val(),
            documenttype:      $('#document-type').val(),
            name:              $('#nombre').val(),
            lastname:          $('#apellido').val(),
            contacttype:       $('#contact-type').val(),
            checkInDate:       $('#check-in-date').val(),
            commentDate:       $('#comment-date').val(),
            approved:          $('#approved').val(),
            AtentionDate:      $('#attention-date').val(),
            Eps:               $('#eps').val(),
            ips:               $('#ips').val(),
            EpsStatus:         $('#eps-status').val(),
            EpsClassification: $('#eps-classification').val(),
            diagnosis:         $('#diagnosis').val(),
            CallNumber:        $('#call-number').val(),
            SentBy:            $('#sent-by').val(),
            ObservationIn:     $('#observation-in').val(),
            exhibitNine:       $('#exhibit-nine').val(),
            exhibitTen:        $('#exhibit-ten').val(),
            sendTo:            $('#send-to').val(),
            ObservationOut:    $('#observation-out').val()
        };

        event.preventDefault();

        $.post('../config/Commit.php', postData, function (response) {
            $('#form-registro').trigger('reset');
            $('#tbody-historial').html('');
            $('#history-patient').hide();
            $('#pk-uuid').val('');
            cargar_hoy();
            $('#search-patients').show();
        });
    }
});

$('#btn-reportes').on('click', function () {

    if (user.privilegeSet != 'root' && user.privilegeSet != 'administrador') {
        Swal.fire({
            icon: 'error',
            title: 'ACCESO RESTRINGIDO',
            text: 'El usuario no está autorizado',
            timer: 5000
        });
        return false;
    }

    if ($('#pk-uuid').val() && $('#dni').val()) {
        $('#history-patient').show();
    } else {
        $('#history-patient').hide();
    }

    $('#modal-report').modal('show');
    $('#form-reporte').trigger('reset');
    let defaultDate = new Date();
    showReportCard(defaultDate);
});

$('#contact-type').on('change', (e) => {
    let value = e.target.value;
    $('.switchTitle').html(value.toUpperCase());
});

$('input[type="date"], input[type="time"], input[type="datetime-local"]').on('change', (e) => {

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

$('#btn-limpiar-reporte').on('click', (e) => {
    Swal.fire({
        icon: 'warning',
        title: 'Atención',
        text: '¿Está seguro de reiniciar el formulario?',
        showCancelButton: true,
        cancelButtonText: 'No',
        showConfirmButton: true,
        confirmButtonText: 'Limpiar',
        dangermode: true,
    }).then((result) => {
        if (result.isConfirmed) {
            $('#form-reporte').trigger('reset');
        }
    });
});

function hideColums(table_id, cols_positions) {
    let table = $('#' + table_id).DataTable();
    table.columns(cols_positions).visible(false);
}
