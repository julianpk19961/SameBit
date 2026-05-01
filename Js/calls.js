var user = JSON.parse(localStorage.getItem('user'));
var diagnosisList = [];

// Delegate to SB library (loaded globally via header.php)
var t = SB.t.bind(SB);

function swalInOffcanvas(opts) { return SB.swal(opts); }

function showFormError(msg) { SB.form.error(msg, 'form-error-banner'); }
function clearFormError()    { SB.form.clear('form-error-banner'); }

$(document).ready(function () {
    if (!user) {
        location.href = '/pages/login.php';
        return;
    }

    const hoy = new Date();
    $('#fecha-hoy').text(hoy.toLocaleDateString(navigator.language, {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    }));

    initMainTable();
    cargarSelectsForm();

    // Auto-refresh table every 5 seconds
    setInterval(function () {
        $('#table-calls').DataTable().ajax.reload(null, false);
    }, 5000);

    document.getElementById('btn-nueva-llamada').addEventListener('click', function () {
        limpiarFormulario();
        bootstrap.Offcanvas.getOrCreateInstance(document.getElementById('offcanvas-registro')).show();
    });

    document.getElementById('offcanvas-registro').addEventListener('shown.bs.offcanvas', function () {
        document.getElementById('call-dni').focus();
    });

    document.getElementById('offcanvas-registro').addEventListener('hide.bs.offcanvas', function () {
        const focused = this.querySelector(':focus');
        if (focused) focused.blur();
    });

    $(document).on('keydown', '#form-registro-call input, #form-registro-call select', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });
});

// ──────────────────────────────────────────────
// MAIN TABLE
// ──────────────────────────────────────────────
function initMainTable() {
    const langUrl = t('datatables_lang_url');
    const langOpt = langUrl ? { url: langUrl } : {};

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
                    return v == 1 ? t('js_approved_yes') : t('js_approved_no');
                }
            },
            { data: 'registrado_por' },
            {
                data: 'call_id',
                orderable: false,
                render: function (id) {
                    return `<button class="btn btn-sm btn-outline-primary btn-edit-call" data-id="${id}" title="Edit">
                        <i class="bi bi-pencil-square"></i>
                    </button>`;
                }
            }
        ],
        pageLength: 50,
        lengthMenu: [[10, 25, 50], [10, 25, 50]],
        order: [[0, 'desc']],
        language: langOpt,
        dom: '<"d-flex justify-content-between align-items-center mb-2"lf>rtip',
        responsive: true,
        processing: true
    });
}

$('#btn-refresh').on('click', function () {
    $('#table-calls').DataTable().ajax.reload(null, false);
});

// ──────────────────────────────────────────────
// EDIT CALL
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
            swalInOffcanvas({ icon: 'error', title: t('js_error_loading_call'), timer: 2500, showConfirmButton: false });
        });
}

function enterEditMode(data) {
    limpiarFormulario();

    $('#call-priority-id').val(data.call_id);
    $('#call-pk-uuid').val(data.patient_id);

    $('#offcanvas-registro-title').html(t('js_edit_call_title'));

    $('#search-section').hide();
    $('#edit-patient-section').show();
    $('#edit-patient-name-display').text(data.first_name + ' ' + data.last_name);
    $('#edit-patient-dni-display').text(getDocTypeName(data.document_type) + ' · ' + data.document_number);

    $('#call-nombre').val(data.first_name);
    $('#call-apellido').val(data.last_name);
    $('#call-eps').val(data.eps_id).trigger('change');
    $('#call-ips').val(data.ips_id).trigger('change');
    $('#call-eps-classification').val(data.range_level).trigger('change');
    $('#call-eps-status').val(data.eps_status).trigger('change');
    $('#call-contact-type').val(data.contact_type).trigger('change');
    $('#call-approved').val(data.approved).trigger('change');

    if (data.diag_id) {
        $('#call-diagnosis').val(data.diag_id);
        const label = data.diag_code + (data.diag_desc ? ' - ' + data.diag_desc : '');
        $('#call-diagnosis-search').val(label);
        $('#call-diagnosis-name').text(label);
        $('#call-diagnosis-selected').show();
    }

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

    $('#call-send-to').val(data.sent_to);
    $('#call-observation-out').val(data.outgoing_notes);
    $('#call-exhibit-ten').val(data.annex_ten != null ? data.annex_ten : '');
}

function enterCreateMode() {
    $('#offcanvas-registro-title').html(t('js_new_call_title'));
    $('#search-section').show();
    $('#edit-patient-section').hide();
    $('#call-priority-id').val('');
}

function getDocTypeName(code) {
    const map = {
        '11': t('doc_civil_registry'),
        '12': t('doc_id_card'),
        '13': t('doc_national_id'),
        '21': t('doc_foreign_card'),
        '22': t('doc_foreign_id'),
        '31': t('doc_nit'),
        '41': t('doc_passport'),
        '42': t('doc_foreign_doc'),
        '43': t('doc_undefined_dian')
    };
    return map[String(code)] || String(code);
}

// ──────────────────────────────────────────────
// REAL-TIME PATIENT SEARCH
// ──────────────────────────────────────────────
var searchTimer = null;

$(document).on('keyup', '#call-dni', function () {
    clearTimeout(searchTimer);
    const q = $(this).val().trim();

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
// SELECT PATIENT
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

$(document).on('click', '#call-clear-patient', function () {
    $('#call-pk-uuid').val('');
    $('#call-selected-patient').hide();
    $('#call-nombre, #call-apellido').val('');
    $('#call-dni').val('').focus();
});

// ──────────────────────────────────────────────
// FORM SELECTS
// ──────────────────────────────────────────────
function cargarSelectsForm() {
    $.get('../config/callips.php', function (res) {
        const list = typeof res === 'object' ? res : JSON.parse(res);
        let opts = '<option value="">' + t('select_ips') + '</option>';
        list.forEach(i => opts += `<option value="${i.pk_uuid}">${i.name}</option>`);
        $('#call-ips').html(opts);
    });

    $.get('../config/callEps.php', function (res) {
        const list = typeof res === 'object' ? res : JSON.parse(res);
        let opts = '<option value="">' + t('select_eps') + '</option>';
        list.forEach(i => opts += `<option value="${i.pk_uuid}">${i.name}</option>`);
        $('#call-eps').html(opts);
    });

    $.get('../config/calldiagnosis.php', function (res) {
        diagnosisList = typeof res === 'object' ? res : JSON.parse(res);
    });
}

// ──────────────────────────────────────────────
// APPOINTMENT DATE based on APPROVED
// ──────────────────────────────────────────────
$(document).on('change', '#call-approved', function () {
    const aprobado = $(this).val() == '1';
    $('#call-attention-date').prop('disabled', !aprobado);
    if (!aprobado) $('#call-attention-date').val('');
});

// ──────────────────────────────────────────────
// CLEAR FORM
// ──────────────────────────────────────────────
$('#call-btn-clean').on('click', function () {
    swalInOffcanvas({
        icon: 'warning',
        title: t('js_cancel_record'),
        text: t('js_data_will_be_lost'),
        showCancelButton: true,
        cancelButtonText: t('js_keep_editing'),
        confirmButtonText: t('js_cancel'),
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
// DIAGNOSIS SEARCH
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
    $('#call-number').focus();
});

$(document).on('click', '#call-clear-diagnosis', function () {
    $('#call-diagnosis').val('');
    $('#call-diagnosis-search').val('').focus();
    $('#call-diagnosis-selected').hide();
});

// ──────────────────────────────────────────────
// DATES: Now button + calendar on click
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
// SUBMIT FORM
// ──────────────────────────────────────────────
var guardandoLlamada = false;

$(document).on('submit', '#form-registro-call', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    if (guardandoLlamada) return;
    console.log('[Save Call] Submit triggered');

    const isEditMode = !!$('#call-priority-id').val();

    const requeridosComunes = [
        ['#call-nombre',           'first_name'],
        ['#call-apellido',         'last_name'],
        ['#call-eps',              'eps'],
        ['#call-ips',              'ips'],
        ['#call-eps-classification','range_label'],
        ['#call-eps-status',       'eps_status'],
        ['#call-contact-type',     'contact_type'],
        ['#call-approved',         'approved'],
        ['#call-check-in-date',    'request_date'],
        ['#call-comment-date',     'comment_date'],
        ['#call-sent-by',          'sent_from'],
        ['#call-observation-in',   'observation'],
        ['#call-send-to',          'sent_to'],
        ['#call-observation-out',  'observation']
    ];

    const requeridos = isEditMode ? requeridosComunes : [
        ['#call-document-type', 'doc_national_id'],
        ['#call-dni',           'document'],
        ...requeridosComunes
    ];

    const faltante = requeridos.find(([id]) => !$(id).val());
    if (faltante) {
        const fieldLabel = t(faltante[1]);
        console.warn('[Save Call] Missing field:', fieldLabel);
        showFormError(t('js_required_field').replace('%s', fieldLabel));
        $(faltante[0]).focus();
        return;
    }

    if (!isEditMode && !$('#call-pk-uuid').val()) {
        console.warn('[Save Call] No patient selected');
        showFormError(t('js_select_patient'));
        $('#call-dni').focus();
        return;
    }

    console.log('[Save Call] Validation OK — mode:', isEditMode ? 'edit' : 'create');
    clearFormError();

    const confirmTitle = isEditMode ? t('js_save_changes') : t('js_save_call');
    const confirmHtml  = isEditMode
        ? `<div style="font-size:1.1em">${t('js_confirm_save_changes')}</div>`
        : `<div style="font-size:1.1em">${t('js_confirm_save_call')}<br><span class="text-secondary" style="font-size:.95em;">${t('js_can_edit_later')}</span></div>`;

    swalInOffcanvas({
        icon: 'info',
        title: confirmTitle,
        html: confirmHtml,
        showCancelButton: true,
        cancelButtonText: t('js_cancel'),
        confirmButtonText: '<i class="bi bi-check-circle"></i> ' + (isEditMode ? t('js_update') : t('js_save')),
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

        console.log('[Save Call] Posting to', endpoint);
        guardandoLlamada = true;
        $.post(endpoint, postData, function (response) {
            guardandoLlamada = false;
            const successMsg = (typeof response === 'object' && response.message)
                ? response.message
                : (isEditMode ? t('js_call_updated') : t('js_call_registered'));
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
            var msg = isEditMode ? t('js_could_not_update') : t('js_could_not_save');
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
                    title: t('js_server_error') + ' (' + xhr.status + ')',
                    text: msg,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
            }
        });
    });
});
