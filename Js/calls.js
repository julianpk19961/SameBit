var user = JSON.parse(localStorage.getItem('user'));
var diagnosisList = [];

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
        language: { url: 'https://cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json' },
        dom: '<"d-flex justify-content-between align-items-center mb-2"lf>rtip',
        responsive: true,
        processing: true
    });
}

$('#btn-refresh').on('click', function () {
    $('#table-calls').DataTable().ajax.reload(null, false);
});

// ──────────────────────────────────────────────
// BÚSQUEDA EN TIEMPO REAL (nombre o documento)
// ──────────────────────────────────────────────
var searchTimer = null;

$(document).on('keyup', '#call-dni', function () {
    clearTimeout(searchTimer);
    const q = $(this).val().trim();

    // Si había un paciente seleccionado y el usuario volvió a escribir, limpiar selección
    if ($('#call-pk-uuid').val()) {
        $('#call-pk-uuid').val('');
        $('#call-nombre, #call-apellido').val('');
        $('#call-selected-patient').hide();
    }

    if (q.length < 3) {
        $('#call-patient-list').empty().hide();
        return;
    }

    searchTimer = setTimeout(function () {
        $.post('../config/getPatients.php', { q: q })
            .done(function (res) {
                var rows;
                try { rows = (typeof res === 'object') ? res : JSON.parse(res); } catch(e) { return; }
                if (!Array.isArray(rows)) rows = [];

                var $list = $('#call-patient-list').empty();
                if (rows.length === 0) { $list.hide(); return; }

                rows.forEach(function (p) {
                    $('<li>')
                        .append($('<span class="pd-doc">').text(p.DOC_NUMBER))
                        .append($('<span class="pd-name">').text(p.PACIENTE))
                        .append($('<i class="bi bi-arrow-right-circle-fill pd-icon">'))
                        .data('patient', p)
                        .appendTo($list);
                });
                $list.show();
            });
    }, 300);
});

// ──────────────────────────────────────────────
// SELECCIONAR PACIENTE
// ──────────────────────────────────────────────
$(document).on('click', '#call-patient-list li', function () {
    var p = $(this).data('patient');
    if (!p) return;

    $('#call-pk-uuid').val(p.UUID);
    $('#call-document-type').val(p.DOC_TYPE).trigger('change');
    $('#call-dni').val(p.DOC_NUMBER);
    $('#call-nombre').val(p.NOMBRE);
    $('#call-apellido').val(p.APELLIDO);
    $('#call-eps').val(p.EPS).trigger('change');
    $('#call-ips').val(p.IPS).trigger('change');
    $('#call-eps-classification').val(p.RANGO).trigger('change');

    $('#call-selected-name').text(p.NOMBRE + ' ' + p.APELLIDO + ' · Doc: ' + p.DOC_NUMBER);
    $('#call-selected-patient').show();
    $('#call-patient-list').empty().hide();
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
        diagnosisList = typeof res === 'object' ? res : JSON.parse(res);
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
    document.activeElement && document.activeElement.blur();
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
    $('#call-patient-list').empty().hide();
    $('#call-selected-patient').hide();
    $('#call-attention-date').prop('disabled', true);
    $('#call-diagnosis').val('');
    $('#call-diagnosis-search').val('');
    $('#call-diagnosis-selected').hide();
    $('#call-diagnosis-list').empty().hide();
}

// ──────────────────────────────────────────────
// BÚSQUEDA DIAGNÓSTICO
// ──────────────────────────────────────────────
var diagnosisTimer = null;

$(document).on('keyup', '#call-diagnosis-search', function () {
    clearTimeout(diagnosisTimer);
    const q = $(this).val().trim().toLowerCase();

    if ($('#call-diagnosis').val()) {
        $('#call-diagnosis').val('');
        $('#call-diagnosis-selected').hide();
    }

    if (q.length < 1) { $('#call-diagnosis-list').empty().hide(); return; }

    diagnosisTimer = setTimeout(function () {
        const filtered = diagnosisList.filter(d =>
            d.Codigo.toLowerCase().includes(q) ||
            (d.Observation && d.Observation.toLowerCase().includes(q))
        ).slice(0, 10);

        const $list = $('#call-diagnosis-list').empty();
        if (filtered.length === 0) { $list.hide(); return; }

        filtered.forEach(function (d) {
            $('<li>')
                .append($('<span class="pd-doc">').text(d.Codigo))
                .append($('<span class="pd-name">').text(d.Observation || ''))
                .append($('<i class="bi bi-arrow-right-circle-fill pd-icon">'))
                .data('diag', d)
                .appendTo($list);
        });
        $list.show();
    }, 200);
});

$(document).on('click', '#call-diagnosis-list li', function () {
    const d = $(this).data('diag');
    if (!d) return;
    const label = d.Codigo + (d.Observation ? ' - ' + d.Observation : '');
    $('#call-diagnosis').val(d.KP_UUID);
    $('#call-diagnosis-search').val(label);
    $('#call-diagnosis-name').text(label);
    $('#call-diagnosis-selected').show();
    $('#call-diagnosis-list').empty().hide();
});

$(document).on('click', '#call-clear-diagnosis', function () {
    $('#call-diagnosis').val('');
    $('#call-diagnosis-search').val('').focus();
    $('#call-diagnosis-selected').hide();
});

// ──────────────────────────────────────────────
// FECHAS: botón Ahora + abrir calendario al clic
// ──────────────────────────────────────────────
function setNow(inputId) {
    const now = new Date();
    const pad = n => String(n).padStart(2, '0');
    const val = `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`;
    $(inputId).val(val).trigger('change');
}

$(document).on('click', 'input[type="datetime-local"]:not(:disabled)', function () {
    try { this.showPicker(); } catch (e) {}
});

// ──────────────────────────────────────────────
// ENVIAR FORMULARIO
// ──────────────────────────────────────────────
var guardandoLlamada = false;

$(document).on('submit', '#form-registro-call', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    if (guardandoLlamada) return;
    document.activeElement && document.activeElement.blur();

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
        // Mostrar alerta dentro del offcanvas, enfocarse en el campo
        const $offcanvas = document.getElementById('offcanvas-registro');
        const offcanvasInstance = bootstrap.Offcanvas.getInstance($offcanvas);
        
        Swal.fire({
            icon: 'error',
            title: 'Campo requerido',
            text: `"${faltante[1]}" es obligatorio.`,
            timer: 4000,
            willOpen: function() {
                // Asegurar que el offcanvas esté visible
                if (offcanvasInstance && !offcanvasInstance._isShown) {
                    offcanvasInstance.show();
                }
            }
        });
        setTimeout(() => $(faltante[0]).focus(), 100);
        return;
    }

    if (!$('#call-pk-uuid').val()) {
        Swal.fire({
            icon: 'error',
            title: 'Paciente no válido',
            text: 'Debe seleccionar un paciente de la lista de resultados.',
            timer: 4000
        });
        $('#call-dni').focus();
        return;
    }

    Swal.fire({
        icon: 'question',
        title: '¿Guardar llamada?',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Guardar',
        confirmButtonColor: '#198754',
        allowOutsideClick: false,
        allowEscapeKey: false
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

        guardandoLlamada = true;
        $.post('../config/Commit.php', postData, function (response) {
            guardandoLlamada = false;
            let successMsg = '¡Llamada registrada!';
            
            // Intentar extraer mensaje de success del backend
            if (typeof response === 'object' && response.message) {
                successMsg = response.message;
            }
            
            Swal.fire({
                icon: 'success',
                title: successMsg,
                timer: 2000,
                showConfirmButton: false,
                didClose: function() {
                    limpiarFormulario();
                    bootstrap.Offcanvas.getInstance(document.getElementById('offcanvas-registro'))?.hide();
                    $('#table-calls').DataTable().ajax.reload(null, false);
                }
            });
        }).fail(function (xhr) {
            guardandoLlamada = false;
            
            // Evitar mostrar múltiples alertas
            if (Swal.isVisible()) return;
            
            var msg = 'No se pudo guardar la llamada.';
            try {
                var resp = typeof xhr.responseText === 'string' ? JSON.parse(xhr.responseText) : xhr.responseText;
                if (resp && resp.error) msg = resp.error;
            } catch(e) {
                if (xhr.responseText) msg = xhr.responseText.substring(0, 300);
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error (' + xhr.status + ')',
                text: msg,
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });
    });
});
