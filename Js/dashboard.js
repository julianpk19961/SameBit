var user = JSON.parse(localStorage.getItem('user'));

// ──────────────────────────────────────────────
// NAVEGACIÓN
// ──────────────────────────────────────────────
$(document).on('click', '#btn-new-record-card', function () {
    location.href = './calls.php';
});

// ──────────────────────────────────────────────
// UTILIDADES DE REPORTE
// ──────────────────────────────────────────────
function showCustomDialog(data) {
    if (!data || !data.length) {
        alert('parametros vacios');
        return false;
    }
    Swal.fire({
        icon: data.icon,
        title: data.title === '' ? data.icon : data.title,
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
            endDate   = generateDate(defaultDate, 23, 59, 59, 59);

        let checkIn_start = startDate.toJSON().slice(0, 19),
            checkIn_end   = endDate.toJSON().slice(0, 19);

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
        dni:              $('#dni-request').val(),
        user:             $('#user-request').val(),
        checkinStart:     checkInStartToJson,
        checkinEnd:       checkInEndToJson,
        checkOutStart:    $('#checkout-start').val(),
        checkOutEnd:      $('#checkout-end').val(),
        appointmentStart: $('#appointment-start').val(),
        appointmentEnd:   $('#appointment-end').val(),
    };

    $('#table-resumen').DataTable({
        ajax: { type: 'POST', url: '../config/getPriorities.php', data: postData },
        columns: [
            { data: 'RECEPCION_CORREO' },
            { data: 'HORA_RECEPCION' },
            { data: 'RESPUESTA_CORREO' },
            { data: 'HORA_RESPUESTA' },
            { data: 'DOCUMENTO' },
            { data: 'PACIENTE' },
            { data: 'ENVIADO_POR' },
            { data: 'IPS' },
            { data: 'EPS' },
            {
                data: 'RANGO',
                render: function (data, type) {
                    if (type !== 'display') return data;
                    return { '0': 'A', '1': 'B', '2': 'C', '3': 'Sisben' }[data] || data;
                }
            },
            { data: 'DIAGNOSTICO' },
            {
                data: 'APROBADO',
                render: function (data, type) {
                    if (type !== 'display') return data;
                    return data === '1' ? 'Sí' : 'No';
                }
            },
            { data: 'FECHA_CITA' },
            { data: 'HORA_CITA' },
            {
                data: 'ANEXO_9',
                render: function (data, type) {
                    if (type !== 'display') return data;
                    return data === '1' ? 'Sí' : 'No';
                }
            },
            {
                data: 'ANEXO_10',
                render: function (data, type) {
                    if (type !== 'display') return data;
                    return data === '1' ? 'Sí' : 'No';
                }
            },
            { data: 'ENVIADO_A' },
            {
                data: 'COMENTARIO_RECEPCION',
                render: function (data, type) {
                    if (type === 'display' && data && data.length > 50) return data.substr(0, 50) + '...';
                    return data;
                }
            },
            {
                data: 'COMENTARIO_CONTRAREF',
                render: function (data, type) {
                    if (type === 'display' && data && data.length > 50) return data.substr(0, 50) + '...';
                    return data;
                }
            },
            { data: 'CREADO_POR' },
            { data: 'DIAS_RESPUESTA' },
            { data: 'HORAS_RESPUESTA' },
            { data: 'DIAS_CITA' },
            { data: 'HORAS_CITA' }
        ],
        destroy: true,
        paging: true,
        serverSide: false,
        scrollY: '300px',
        scrollX: '300px',
        scrollCollapse: true,
        responsive: true,
        deferRender: true,
        orderClasses: false,
        processing: true,
        order: [2],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="bi bi-filetype-xls"></i>',
                className: 'bg-success text-white',
                titleAttr: 'Generar Archivo: Excel',
                exportOptions: { orthogonal: 'export' }
            },
            {
                extend: 'csvHtml5',
                text: '<i class="bi bi-filetype-csv"></i>',
                className: 'bg-info text-white',
                titleAttr: 'Generar Archivo: CSV',
                exportOptions: { orthogonal: 'export' }
            }
        ],
        lengthMenu: [30, 50, 100, 200],
        language: { url: 'https://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json' },
        initComplete: function () {
            $('#table-resumen').DataTable().columns([0, 1, 9, 14, 15, 17, 18, 19, 20, 21, 22, 23]).visible(false);
        }
    });
}

// ──────────────────────────────────────────────
// MODAL REPORTES
// ──────────────────────────────────────────────
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

    $('#modal-report').modal('show');
    $('#form-reporte').trigger('reset');
    showReportCard(new Date());
});

$('#btn-limpiar-reporte').on('click', function () {
    Swal.fire({
        icon: 'warning',
        title: 'Atención',
        text: '¿Está seguro de reiniciar el formulario?',
        showCancelButton: true,
        cancelButtonText: 'No',
        confirmButtonText: 'Limpiar',
    }).then(function (result) {
        if (result.isConfirmed) {
            $('#form-reporte').trigger('reset');
        }
    });
});

// ──────────────────────────────────────────────
// VALIDACIÓN FECHAS DEL MODAL (in < out)
// ──────────────────────────────────────────────
$('input[type="date"], input[type="time"], input[type="datetime-local"]').on('change', function (e) {
    const pullTrigger = e.target,
        triggerClass  = pullTrigger.className.split(' ')[0];

    if (!triggerClass.match('_in|_out')) return false;

    let triggerType    = pullTrigger.type,
        triggerClassId = triggerClass.split('_')[0],
        compareInput   = triggerClass.match('in') ? 'out' : 'in',
        triggerCall    = !pullTrigger.id ? `input.${triggerClass}[type="${triggerType}"]` : `#${pullTrigger.id}`,
        fieldTrigger   = $(triggerCall),
        fieldCompare   = $(`input.${triggerClassId}_${compareInput}[type="${triggerType}"]`);

    if (fieldTrigger.length === 0 || fieldCompare.length === 0) return false;
    if (!fieldTrigger[0].id || !fieldCompare[0].id) return false;

    if (fieldTrigger.length > 1) fieldTrigger = $(`#${fieldTrigger[0].id}`);
    if (fieldCompare.length > 1) fieldCompare = $(`#${fieldCompare[0].id}`);

    let in_field  = compareInput === 'in' ? fieldCompare : fieldTrigger,
        out_field = compareInput === 'out' ? fieldCompare : fieldTrigger,
        in_val    = in_field.val(),
        out_val   = out_field.val();

    if (!in_val || !out_val) return false;

    if (in_val >= out_val) {
        showCustomDialog({
            icon: 'error',
            title: 'Valor no valido',
            text: 'El valor ingresado no puede ser inferior al valor inicial: ' + in_val,
            time: '5000',
        });
        out_field.val('');
        in_field.focus();
    }
});

// ──────────────────────────────────────────────
// INIT
// ──────────────────────────────────────────────
$(document).ready(function () {
    if (user == null) {
        location.href = '/pages/login.php';
        return false;
    }
});
