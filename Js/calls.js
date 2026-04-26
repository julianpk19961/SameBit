var user = JSON.parse(localStorage.getItem('user'));
var diagnosisList = [];

function swalInOffcanvas(opts) {
    // Blur focused element before opening Swal to avoid aria-hidden warnings on offcanvas
    const focused = document.activeElement;
    if (focused && focused !== document.body) focused.blur();
    return Swal.fire(opts);
}

function showFormError(msg) {
    $('#form-error-text').text(msg);
    $('#form-error-banner').removeClass('d-none');
    $('#form-error-banner')[0]?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function clearFormError() {
    $('#form-error-banner').addClass('d-none');
    $('#form-error-text').text('');
}

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

    // Auto-refresh de la tabla cada 5 segundos
    setInterval(function () {
        $('#table-calls').DataTable().ajax.reload(null, false);
    }, 5000);

    document.getElementById('btn-nueva-llamada').addEventListener('click', function () {
        limpiarFormulario();
        bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('offcanvas-registro')).show();
    });

    // Focus en el campo documento al abrir el offcanvas
    document.getElementById('offcanvas-registro').addEventListener('shown.bs.offcanvas', function () {
        document.getElementById('call-dni').focus();
    });

    // Mover foco fuera del offcanvas cuando se cierra para evitar aria-hidden warnings
    document.getElementById('offcanvas-registro').addEventListener('hide.bs.offcanvas', function () {
        const focused = this.querySelector(':focus');
        if (focused) focused.blur();
    });

    // Prevenir que Enter en inputs dispare el submit del formulario
    $(document).on('keydown', '#form-registro-call input, #form-registro-call select', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
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
            { data: 'registrado_por' },
            {
                data: 'call_id',
                orderable: false,
                render: function (id) {
                    return `<button class="btn btn-sm btn-outline-primary btn-edit-call" data-id="${id}" title="Editar">
                        <i class="bi bi-pencil-square"></i>
                    </button>`;
                }
            }
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
// EDITAR LLAMADA
// ──────────────────────────────────────────────
$(document).on('click', '.btn-edit-call', function () {
    const callId = $(this).data('id');
    openEditCall(callId);
});

function openEditCall(callId) {
    $.get('../config/getCall.php', { id: callId })
        .done(function (data) {
            enterEditMode(data);
            bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('offcanvas-registro')).show();
        })
        .fail(function () {
            swalInOffcanvas({ icon: 'error', title: 'Error al cargar la llamada', timer: 2500, showConfirmButton: false });
        });
}

function enterEditMode(data) {
    limpiarFormulario();

    $('#call-priority-id').val(data.call_id);
    $('#call-pk-uuid').val(data.patient_id);

    $('#offcanvas-registro-title').html('<i class="bi bi-pencil-square me-2 text-warning"></i>Editar Llamada');

    // Ocultar búsqueda, mostrar bloque de paciente fijo
    $('#search-section').hide();
    $('#edit-patient-section').show();
    $('#edit-patient-name-display').text(data.first_name + ' ' + data.last_name);
    $('#edit-patient-dni-display').text(getDocTypeName(data.document_type) + ' · ' + data.document_number);

    // Datos del paciente
    $('#call-nombre').val(data.first_name);
    $('#call-apellido').val(data.last_name);
    $('#call-eps').val(data.eps_id).trigger('change');
    $('#call-ips').val(data.ips_id).trigger('change');
    $('#call-eps-classification').val(data.range_level).trigger('change');
    $('#call-eps-status').val(data.eps_status).trigger('change');
    $('#call-contact-type').val(data.contact_type).trigger('change');
    $('#call-approved').val(data.approved).trigger('change');

    // Diagnóstico
    if (data.diag_id) {
        $('#call-diagnosis').val(data.diag_id);
        const label = data.diag_code + (data.diag_desc ? ' - ' + data.diag_desc : '');
        $('#call-diagnosis-search').val(label);
        $('#call-diagnosis-name').text(label);
        $('#call-diagnosis-selected').show();
    }

    // Referencia
    $('#call-number').val(data.calls_count || 0);
    $('#call-sent-by').val(data.sent_by);
    $('#call-observation-in').val(data.reception_notes);
    $('#call-exhibit-nine').val(data.annex_nine != null ? data.annex_nine : '');

    if (data.checkin_date && data.checkin_time) {
        $('#call-check-in-date').val(data.checkin_date + 'T' + data.checkin_time.substring(0, 5));
    }
    if (data.response_date && data.response_time) {
        $('#call-comment-date').val(data.response_date + 'T' + data.response_time.substring(0, 5));
    }
    if (data.approved == 1 && data.appointment_date && data.appointment_time) {
        $('#call-attention-date').prop('disabled', false).val(data.appointment_date + 'T' + data.appointment_time.substring(0, 5));
    }

    // Contra-referencia
    $('#call-send-to').val(data.sent_to);
    $('#call-observation-out').val(data.outgoing_notes);
    $('#call-exhibit-ten').val(data.annex_ten != null ? data.annex_ten : '');
}

function enterCreateMode() {
    $('#offcanvas-registro-title').html('<i class="bi bi-telephone-plus me-2 text-warning"></i>Registrar Llamada');
    $('#search-section').show();
    $('#edit-patient-section').hide();
    $('#call-priority-id').val('');
}

function getDocTypeName(code) {
    const map = { '11': 'Reg. Civil', '12': 'Tarjeta Identidad', '13': 'Cédula', '21': 'T. Extranjería', '22': 'C. Extranjería', '31': 'NIT', '41': 'Pasaporte', '42': 'Doc. Extranjero', '43': 'No definido DIAN' };
    return map[String(code)] || String(code);
}

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
        const list = typeof res === 'object' ? res : JSON.parse(res);
        let opts = '<option value="">— Seleccione IPS —</option>';
        list.forEach(i => opts += `<option value="${i.pk_uuid}">${i.name}</option>`);
        $('#call-ips').html(opts);
    });

    $.get('../config/callEps.php', function (res) {
        const list = typeof res === 'object' ? res : JSON.parse(res);
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
    swalInOffcanvas({
        icon: 'warning',
        title: '¿Cancelar registro?',
        text: 'Los datos ingresados se perderán.',
        showCancelButton: true,
        cancelButtonText: 'Continuar editando',
        confirmButtonText: 'Cancelar',
        confirmButtonColor: '#6c757d'
    }).then(r => {
        if (r.isConfirmed) {
            limpiarFormulario();
            bootstrap.Offcanvas.getInstance(document.getElementById('offcanvas-registro'))?.hide();
        }
    });
});

function limpiarFormulario() {
    $('#form-registro-call')[0].reset();
    $('#call-pk-uuid').val('');
    $('#call-priority-id').val('');
    $('#call-patient-list').empty().hide();
    $('#call-selected-patient').hide();
    $('#call-attention-date').prop('disabled', true);
    $('#call-diagnosis').val('');
    $('#call-diagnosis-search').val('');
    $('#call-diagnosis-selected').hide();
    $('#call-diagnosis-list').empty().hide();
    clearFormError();
    enterCreateMode();
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
    // Mover foco al siguiente campo para que el navegador no lo envíe a btn-close
    $('#call-number').focus();
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
    console.log('[Guardar Llamada] Submit disparado');

    const isEditMode = !!$('#call-priority-id').val();

    const requeridosComunes = [
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

    const requeridos = isEditMode ? requeridosComunes : [
        ['#call-document-type', 'Tipo de Identificación'],
        ['#call-dni', 'Identificación'],
        ...requeridosComunes
    ];

    const faltante = requeridos.find(([id]) => !$(id).val());
    if (faltante) {
        console.warn('[Guardar Llamada] Campo faltante:', faltante[1]);
        showFormError(`El campo "${faltante[1]}" es obligatorio.`);
        $(faltante[0]).focus();
        return;
    }

    if (!isEditMode && !$('#call-pk-uuid').val()) {
        console.warn('[Guardar Llamada] No hay paciente seleccionado');
        showFormError('Debe seleccionar un paciente de la lista de resultados antes de guardar.');
        $('#call-dni').focus();
        return;
    }

    console.log('[Guardar Llamada] Validación OK — modo:', isEditMode ? 'edición' : 'creación');
    clearFormError();

    const confirmTitle = isEditMode ? '¿Guardar cambios?' : '¿Guardar llamada?';
    const confirmHtml  = isEditMode
        ? '<div style="font-size:1.1em">¿Confirma que desea guardar los cambios en esta llamada?</div>'
        : '<div style="font-size:1.1em">¿Confirma que desea registrar esta llamada con los datos ingresados?<br><span class="text-secondary" style="font-size:.95em;">Podrá editarla luego desde el historial.</span></div>';

    swalInOffcanvas({
        icon: 'info',
        title: confirmTitle,
        html: confirmHtml,
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: '<i class="bi bi-check-circle"></i> ' + (isEditMode ? 'Actualizar' : 'Guardar'),
        confirmButtonColor: '#198754',
        focusConfirm: true,
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then(r => {
        if (!r.isConfirmed) return;

        const commonData = {
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

        let endpoint, postData;
        if (isEditMode) {
            endpoint = '../config/UpdateCall.php';
            postData = Object.assign({ call_id: $('#call-priority-id').val() }, commonData);
        } else {
            endpoint = '../config/Commit.php';
            postData = Object.assign({
                pk_uuid:      $('#call-pk-uuid').val(),
                dni:          $('#call-dni').val(),
                documenttype: $('#call-document-type').val()
            }, commonData);
        }

        console.log('[Guardar Llamada] Enviando POST a', endpoint);
        guardandoLlamada = true;
        $.post(endpoint, postData, function (response) {
            guardandoLlamada = false;
            const successMsg = (typeof response === 'object' && response.message) ? response.message
                : (isEditMode ? '¡Llamada actualizada!' : '¡Llamada registrada!');
            swalInOffcanvas({
                icon: 'success',
                title: successMsg,
                timer: 2000,
                showConfirmButton: false,
                didClose: function () {
                    limpiarFormulario();
                    bootstrap.Offcanvas.getInstance(document.getElementById('offcanvas-registro'))?.hide();
                    $('#table-calls').DataTable().ajax.reload(null, false);
                }
            });
        }).fail(function (xhr) {
            guardandoLlamada = false;
            var msg = isEditMode ? 'No se pudo actualizar la llamada.' : 'No se pudo guardar la llamada.';
            try {
                var resp = typeof xhr.responseText === 'string' ? JSON.parse(xhr.responseText) : xhr.responseText;
                if (resp && resp.error) msg = resp.error;
            } catch(e) {
                if (xhr.responseText) msg = xhr.responseText.substring(0, 300);
            }
            if (xhr.status === 400) {
                showFormError(msg);
            } else {
                if (Swal.isVisible()) return;
                swalInOffcanvas({
                    icon: 'error',
                    title: 'Error del servidor (' + xhr.status + ')',
                    text: msg,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
            }
        });
    });
});
