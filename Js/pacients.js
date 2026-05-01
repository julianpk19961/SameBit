// pacients.js — Patients CRUD
// Depends on sb.js loaded globally via header.php
//
// Backend endpoints expected:
//   POST ../config/list_patients.php   → { data: [...] }   (DataTable source)
//   POST ../config/save_patient.php    → { success, message, data }
//   POST ../config/get_patient.php     → { success, data }
//   POST ../config/delete_patient.php  → { success, message }

var _pacientsTable = null;

$(document).ready(function () {
    _pacientsTable = SB.table('#pacients-table', {
        ajax: {
            url:     '../config/list_patients.php',
            type:    'POST',
            dataSrc: 'data'
        },
        columns: [
            { data: 'document_number' },
            { data: 'first_name' },
            { data: 'last_name' },
            { data: 'eps_name',  defaultContent: '—' },
            {
                data: 'active',
                render: function (v) {
                    return v == 1
                        ? '<span class="badge bg-success">' + SB.t('active') + '</span>'
                        : '<span class="badge bg-secondary">' + SB.t('inactive') + '</span>';
                }
            },
            {
                data: 'id',
                orderable: false,
                render: function (id) {
                    return '<button class="btn btn-sm btn-outline-primary" ' +
                           'onclick="pacientsOpenEdit(\'' + id + '\')" title="' + SB.t('edit') + '">' +
                           '<i class="bi bi-pencil"></i></button>';
                }
            }
        ],
        order: [[1, 'asc']]
    });
});

// ── Helpers ───────────────────────────────────────────────────────────────
function _pacientsReset() {
    document.getElementById('pacients-form').reset();
    document.getElementById('pac-id').value = '';
    SB.form.clear('pacients-error');
}

// ── Open panel: create mode ───────────────────────────────────────────────
function pacientsOpenCreate() {
    _pacientsReset();
    SB.panel.setTitle('pacients-panel',
        '<i class="bi bi-person-plus me-2 text-primary"></i>' + (SB.t('new_patient') || 'Nuevo Paciente'));
    SB.panel.show('pacients-panel');
}

// ── Open panel: edit mode ─────────────────────────────────────────────────
function pacientsOpenEdit(id) {
    SB.api('../config/get_patient.php', { id: id })
        .done(function (r) {
            if (!r.success) { SB.toast(r.message || SB.t('error'), 'error'); return; }
            var p = r.data;
            _pacientsReset();
            document.getElementById('pac-id').value         = p.id;
            document.getElementById('pac-doc-type').value   = p.document_type || '13';
            document.getElementById('pac-dni').value        = p.document_number || '';
            document.getElementById('pac-first-name').value = p.first_name || '';
            document.getElementById('pac-last-name').value  = p.last_name  || '';
            document.getElementById('pac-gender').value     = p.gender      || '';
            document.getElementById('pac-birth-date').value = p.birth_date  || '';
            document.getElementById('pac-phone').value      = p.phone       || '';
            document.getElementById('pac-mobile').value     = p.mobile      || '';
            document.getElementById('pac-email').value      = p.email       || '';
            document.getElementById('pac-address').value    = p.address     || '';
            document.getElementById('pac-status').value     = p.active      || '1';
            SB.panel.setTitle('pacients-panel',
                '<i class="bi bi-person-gear me-2 text-primary"></i>' + (SB.t('edit_patient') || 'Editar Paciente'));
            SB.panel.show('pacients-panel');
        })
        .fail(function () { SB.toast(SB.t('connection_error'), 'error'); });
}

// ── Form submit ───────────────────────────────────────────────────────────
$(document).on('submit', '#pacients-form', function (e) {
    e.preventDefault();
    SB.form.clear('pacients-error');

    var dni       = document.getElementById('pac-dni').value.trim();
    var firstName = document.getElementById('pac-first-name').value.trim();
    var lastName  = document.getElementById('pac-last-name').value.trim();

    if (!dni)       { SB.form.error('Identificación: ' + SB.t('required'), 'pacients-error'); return; }
    if (!firstName) { SB.form.error(SB.t('first_name') + ': ' + SB.t('required'), 'pacients-error'); return; }
    if (!lastName)  { SB.form.error(SB.t('last_name')  + ': ' + SB.t('required'), 'pacients-error'); return; }

    SB.api('../config/save_patient.php', {
        id:              document.getElementById('pac-id').value,
        document_type:   document.getElementById('pac-doc-type').value,
        document_number: dni,
        first_name:      firstName,
        last_name:       lastName,
        gender:          document.getElementById('pac-gender').value,
        birth_date:      document.getElementById('pac-birth-date').value,
        phone:           document.getElementById('pac-phone').value.trim(),
        mobile:          document.getElementById('pac-mobile').value.trim(),
        email:           document.getElementById('pac-email').value.trim(),
        address:         document.getElementById('pac-address').value.trim(),
        active:          document.getElementById('pac-status').value
    }).done(function (r) {
        if (r.success) {
            SB.panel.hide('pacients-panel');
            SB.toast(r.message || SB.t('success'));
            if (_pacientsTable) _pacientsTable.ajax.reload(null, false);
        } else {
            SB.form.error(r.message || SB.t('error_saving'), 'pacients-error');
        }
    }).fail(function (xhr) {
        var msg = SB.t('connection_error');
        try { var resp = JSON.parse(xhr.responseText); if (resp.message) msg = resp.message; } catch (e) {}
        SB.form.error(msg, 'pacients-error');
    });
});
