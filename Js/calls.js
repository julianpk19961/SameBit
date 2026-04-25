var user = JSON.parse(localStorage.getItem('user'));

$(document).ready(function () {
    if (!user) {
        location.href = '/pages/login.php';
        return;
    }

    const hoy = new Date();
    $('#fecha-hoy').text(hoy.toLocaleDateString('es-CO', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    }));

    initMainTable();
    cargarSelectsForm();
    initPatientTable();

    // Abrir offcanvas de registro
    document.getElementById('btn-nueva-llamada').addEventListener('click', function () {
        bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('offcanvas-registro')).show();
    });
});

// ──────────────────────────────────────────────
// TABLA PRINCIPAL
// ──────────────────────────────────────────────
function initMainTable() {
    $('#table-calls').DataTable({
        ajax: { url: '../config/getCalls.php', type: 'GET', dataSrc: 'data' },
        columns: [
            { data: 'hora' },
            { data: 'paciente' },
            { data: 'documento' },
            { data: 'eps' },
            { data: 'ips' },
            { data: 'diagnostico' },
            { data: 'tipo_contacto' },
            {
                data: 'aprobado',
                render: function (v) {
                    return v == 1
                        ? '<span class="badge bg-success">Sí</span>'
                        : '<span class="badge bg-secondary">No</span>';
                }
            },
            { data: 'registrado_por' }
        ],
        pageLength: 50,
        lengthMenu: [[10, 25, 50], [10, 25, 50]],
        order: [[0, 'desc']],
        language: { url: 'http://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json' },
        dom: '<"d-flex justify-content-between align-items-center mb-2"lf>rtip',
        responsive: true,
        processing: true
    });
}

$('#btn-refresh').on('click', function () {
    $('#table-calls').DataTable().ajax.reload(null, false);
});

// ──────────────────────────────────────────────
// TABLA DE BÚSQUEDA DE PACIENTES (en offcanvas)
// ──────────────────────────────────────────────
var patientDT = null;

function initPatientTable() {
    patientDT = $('#call-table-patients').DataTable({
        data: [],
        columns: [
            { data: 'PACIENTE', title: 'Paciente' },
            { data: 'DOC_NUMBER', title: 'Documento' },
            {
                data: null,
                title: 'Acción',
                orderable: false,
                defaultContent: '<button type="button" class="btn btn-sm btn-primary call-patient-select">Seleccionar</button>'
            }
        ],
        pageLength: 5,
        lengthChange: false,
        language: { url: 'http://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json' },
        dom: '<"mb-1"f>rtip'
    });
}

// ──────────────────────────────────────────────
// BÚSQUEDA EN TIEMPO REAL POR DNI
// ──────────────────────────────────────────────
var searchTimer = null;

$(document).on('keyup', '#call-dni', function () {
    clearTimeout(searchTimer);
    const dni = $(this).val().trim();

    if (dni.length < 4) {
        $('#call-patient-list').hide();
        return;
    }

    searchTimer = setTimeout(function () {
        $.post('../config/getPatients.php', { dni: dni }, function (res) {
            const resp = JSON.parse(res);
            const rows = (resp.data && Array.isArray(resp.data)) ? resp.data : [];

            patientDT.clear();
            if (rows.length > 0) {
                patientDT.rows.add(rows).draw();
                $('#call-patient-list').show();
            } else {
                $('#call-patient-list').hide();
            }
        });
    }, 300);
});

// ──────────────────────────────────────────────
// SELECCIONAR PACIENTE
// ──────────────────────────────────────────────
$(document).on('click', '.call-patient-select', function () {
    const rowData = patientDT.row($(this).closest('tr')).data();
    if (!rowData) return;

    $.post('../config/usepatient.php', { pk_uuid: rowData.UUID }, function (res) {
        const p = JSON.parse(res);

        $('#call-pk-uuid').val(p.pk_uuid);
        $('#call-dni').val(p.dni);
        $('#call-nombre').val(p.name);
        $('#call-apellido').val(p.lastname);
        $('#call-document-type').val(p.documentType);
        $('#call-eps').val(p.eps);
        $('#call-ips').val(p.ips);
        $('#call-eps-classification').val(p.range);

        // Mostrar tag del paciente seleccionado
        $('#call-selected-name').text(p.name + ' ' + p.lastname + '  ·  Doc: ' + p.dni);
        $('#call-selected-patient').show();
        $('#call-patient-list').hide();
    });
});

// Limpiar paciente seleccionado
$(document).on('click', '#call-clear-patient', function () {
    $('#call-pk-uuid').val('');
    $('#call-selected-patient').hide();
    $('#call-nombre, #call-apellido').val('');
    $('#call-dni').val('').focus();
});

// ──────────────────────────────────────────────
// SELECTS DEL FORMULARIO
// ──────────────────────────────────────────────
function cargarSelectsForm() {
    $.get('../config/callips.php', function (res) {
        const list = JSON.parse(res);
        let opts = '<option value="">— Seleccione IPS —</option>';
        list.forEach(i => opts += `<option value="${i.pk_uuid}">${i.name}</option>`);
        $('#call-ips').html(opts);
    });

    $.get('../config/callEps.php', function (res) {
        const list = JSON.parse(res);
        let opts = '<option value="">— Seleccione EPS —</option>';
        list.forEach(i => opts += `<option value="${i.pk_uuid}">${i.name}</option>`);
        $('#call-eps').html(opts);
    });

    $.get('../config/calldiagnosis.php', function (res) {
        const list = JSON.parse(res);
        let opts = '<option value="">— Seleccione —</option>';
        list.forEach(i => opts += `<option value="${i.KP_UUID}" title="${i.Observation}">${i.Codigo}</option>`);
        $('#call-diagnosis').html(opts);
    });
}

// ──────────────────────────────────────────────
// FECHA CITA según APROBADO
// ──────────────────────────────────────────────
$(document).on('change', '#call-approved', function () {
    const aprobado = $(this).val() == '1';
    $('#call-attention-date').prop('disabled', !aprobado);
    if (!aprobado) $('#call-attention-date').val('');
});

// ──────────────────────────────────────────────
// LIMPIAR FORMULARIO
// ──────────────────────────────────────────────
$('#call-btn-clean').on('click', function () {
    Swal.fire({
        icon: 'warning',
        title: '¿Limpiar formulario?',
        text: 'Los datos ingresados se perderán.',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Limpiar',
        confirmButtonColor: '#dc3545'
    }).then(r => { if (r.isConfirmed) limpiarFormulario(); });
});

function limpiarFormulario() {
    $('#form-registro-call')[0].reset();
    $('#call-pk-uuid').val('');
    $('#call-patient-list').hide();
    $('#call-selected-patient').hide();
    $('#call-attention-date').prop('disabled', true);
    patientDT.clear().draw();
}

// ──────────────────────────────────────────────
// ENVIAR FORMULARIO
// ──────────────────────────────────────────────
$(document).on('submit', '#form-registro-call', function (e) {
    e.preventDefault();

    const requeridos = [
        ['#call-document-type', 'Tipo de Identificación'],
        ['#call-dni', 'Identificación'],
        ['#call-nombre', 'Nombres'],
        ['#call-apellido', 'Apellidos'],
        ['#call-eps', 'EPS'],
        ['#call-ips', 'IPS'],
        ['#call-eps-classification', 'Rango'],
        ['#call-eps-status', 'Estado EPS'],
        ['#call-contact-type', 'Tipo Contacto'],
        ['#call-approved', 'Aprobado'],
        ['#call-check-in-date', 'Fecha Solicitud'],
        ['#call-comment-date', 'Fecha Comentario'],
        ['#call-sent-by', 'Remitido Desde'],
        ['#call-observation-in', 'Observación'],
        ['#call-send-to', 'Remitido A'],
        ['#call-observation-out', 'Observación Contra-ref.']
    ];

    const faltante = requeridos.find(([id]) => !$(id).val());
    if (faltante) {
        Swal.fire({ icon: 'error', title: 'Campo requerido', text: `"${faltante[1]}" es obligatorio.`, timer: 4000 });
        $(faltante[0]).focus();
        return;
    }

    Swal.fire({
        icon: 'question',
        title: '¿Guardar llamada?',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Guardar',
        confirmButtonColor: '#198754'
    }).then(r => {
        if (!r.isConfirmed) return;

        const postData = {
            pk_uuid:           $('#call-pk-uuid').val(),
            dni:               $('#call-dni').val(),
            documenttype:      $('#call-document-type').val(),
            name:              $('#call-nombre').val(),
            lastname:          $('#call-apellido').val(),
            contacttype:       $('#call-contact-type').val(),
            checkInDate:       $('#call-check-in-date').val(),
            commentDate:       $('#call-comment-date').val(),
            approved:          $('#call-approved').val(),
            AtentionDate:      $('#call-attention-date').val() || $('#call-comment-date').val(),
            Eps:               $('#call-eps').val(),
            ips:               $('#call-ips').val(),
            EpsStatus:         $('#call-eps-status').val(),
            EpsClassification: $('#call-eps-classification').val(),
            diagnosis:         $('#call-diagnosis').val(),
            CallNumber:        $('#call-number').val() || 0,
            SentBy:            $('#call-sent-by').val(),
            ObservationIn:     $('#call-observation-in').val(),
            exhibitNine:       $('#call-exhibit-nine').val() || 0,
            exhibitTen:        $('#call-exhibit-ten').val() || 0,
            sendTo:            $('#call-send-to').val(),
            ObservationOut:    $('#call-observation-out').val()
        };

        $.post('../config/Commit.php', postData, function () {
            Swal.fire({ icon: 'success', title: '¡Llamada registrada!', timer: 2000, showConfirmButton: false });
            limpiarFormulario();
            bootstrap.Offcanvas.getInstance(document.getElementById('offcanvas-registro'))?.hide();
            $('#table-calls').DataTable().ajax.reload(null, false);
        }).fail(function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo guardar la llamada. Intente de nuevo.' });
        });
    });
});
