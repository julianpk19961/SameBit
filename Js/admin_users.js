// admin_users.js — Users CRUD
// Depends on sb.js loaded globally via header.php

$(document).ready(function () {
    SB.table('#users-table', { order: [[1, 'asc']] });
});

// ── Open panel: create mode ───────────────────────────────────────────────
function usersOpenCreate() {
    document.getElementById('users-form').reset();
    document.getElementById('users-id').value = '';
    document.getElementById('users-password').required = true;
    document.getElementById('users-pass-label').innerHTML =
        SB.t('password') + ' <span class="text-danger">*</span>';
    document.getElementById('users-pass-hint').textContent = SB.t('min_6_chars');
    document.getElementById('users-active').checked = true;
    SB.form.clear('users-error');
    SB.panel.setTitle('users-panel',
        '<i class="bi bi-person-plus me-2 text-primary"></i>' + SB.t('new_user'));
    SB.panel.show('users-panel');
}

// ── Open panel: edit mode ─────────────────────────────────────────────────
function usersOpenEdit(id) {
    SB.api('../config/get_user.php', { user_id: id })
        .done(function (r) {
            if (!r.success) {
                SB.toast(r.message || SB.t('error'), 'error');
                return;
            }
            var u = r.data;
            document.getElementById('users-id').value        = u.id;
            document.getElementById('users-username').value  = u.username;
            document.getElementById('users-first-name').value = u.first_name;
            document.getElementById('users-last-name').value  = u.last_name;
            document.getElementById('users-profile-id').value = u.profile_id;
            document.getElementById('users-active').checked   = u.active == 1;
            document.getElementById('users-password').value   = '';
            document.getElementById('users-password').required = false;
            document.getElementById('users-pass-label').innerHTML =
                SB.t('password') +
                ' <small class="text-muted fw-normal">(' + SB.t('leave_blank_to_keep') + ')</small>';
            SB.form.clear('users-error');
            SB.panel.setTitle('users-panel',
                '<i class="bi bi-person-gear me-2 text-primary"></i>' + SB.t('edit_user'));
            SB.panel.show('users-panel');
        })
        .fail(function () {
            SB.toast(SB.t('error_fetching_user'), 'error');
        });
}

// ── Form submit ───────────────────────────────────────────────────────────
$(document).on('submit', '#users-form', function (e) {
    e.preventDefault();
    SB.form.clear('users-error');

    var id        = document.getElementById('users-id').value;
    var username  = document.getElementById('users-username').value.trim();
    var password  = document.getElementById('users-password').value;
    var firstName = document.getElementById('users-first-name').value.trim();
    var lastName  = document.getElementById('users-last-name').value.trim();
    var profileId = document.getElementById('users-profile-id').value;
    var isEdit    = !!id;

    if (!username)                    { SB.form.error(SB.t('username')   + ': ' + SB.t('required'), 'users-error'); return; }
    if (!isEdit && !password)         { SB.form.error(SB.t('password')   + ': ' + SB.t('required'), 'users-error'); return; }
    if (password && password.length < 6) { SB.form.error(SB.t('min_6_chars'), 'users-error'); return; }
    if (!firstName)                   { SB.form.error(SB.t('first_name') + ': ' + SB.t('required'), 'users-error'); return; }
    if (!lastName)                    { SB.form.error(SB.t('last_name')  + ': ' + SB.t('required'), 'users-error'); return; }
    if (!profileId)                   { SB.form.error(SB.t('profile')    + ': ' + SB.t('required'), 'users-error'); return; }

    SB.api('../config/save_user.php', {
        user_id:    id,
        username:   username,
        password:   password,
        first_name: firstName,
        last_name:  lastName,
        profile_id: profileId,
        active:     document.getElementById('users-active').checked ? 1 : 0
    }).done(function (r) {
        if (r.success) {
            SB.panel.hide('users-panel');
            SB.toast(r.message || SB.t('success')).then(function () { location.reload(); });
        } else {
            SB.form.error(r.message || SB.t('error_saving'), 'users-error');
        }
    }).fail(function (xhr) {
        var msg = SB.t('connection_error');
        try { var resp = JSON.parse(xhr.responseText); if (resp.message) msg = resp.message; } catch (e) {}
        SB.form.error(msg, 'users-error');
    });
});

// ── View permissions (read-only modal) ───────────────────────────────────
function usersViewPermissions(id) {
    var $body = $('#users-perms-body');
    $body.html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');
    bootstrap.Modal.getOrCreateInstance(document.getElementById('users-perms-modal')).show();

    SB.api('../config/get_user_permissions.php', { user_id: id })
        .done(function (r) {
            if (!r.success) {
                $body.html('<div class="alert alert-danger">' + SB.esc(r.message) + '</div>');
                return;
            }
            $('#users-perms-title').html(
                '<i class="bi bi-shield-lock me-2"></i>' + SB.esc(r.profile.name));

            var html = '<div class="table-responsive">' +
                '<table class="table table-sm table-bordered align-middle">' +
                '<thead class="table-light"><tr>' +
                '<th>' + SB.t('module') + '</th>' +
                '<th>' + SB.t('permission') + '</th>' +
                '<th class="text-center">Acceso</th>' +
                '</tr></thead><tbody>';

            for (var mod in r.permissions) {
                for (var perm in r.permissions[mod]) {
                    var hasAccess = r.permissions[mod][perm];
                    html += '<tr>' +
                        '<td>' + SB.esc(mod) + '</td>' +
                        '<td>' + SB.esc(perm) + '</td>' +
                        '<td class="text-center">' +
                        (hasAccess
                            ? '<i class="bi bi-check-circle-fill text-success"></i>'
                            : '<i class="bi bi-x-circle text-muted"></i>') +
                        '</td></tr>';
                }
            }
            html += '</tbody></table></div>';
            $body.html(html);
        })
        .fail(function () {
            $body.html('<div class="alert alert-danger">' + SB.t('connection_error') + '</div>');
        });
}

// ── Delete user ───────────────────────────────────────────────────────────
function usersDelete(id, username) {
    SB.confirm({
        html: '¿Eliminar usuario <strong>' + SB.esc(username) + '</strong>?',
        confirmButtonText:  SB.t('yes_delete') || 'Sí, eliminar',
        confirmButtonColor: '#dc3545'
    }).then(function (r) {
        if (!r.isConfirmed) return;
        SB.api('../config/delete_user.php', { user_id: id })
            .done(function (res) {
                if (res.success) {
                    SB.toast(res.message || SB.t('deleted'))
                        .then(function () { location.reload(); });
                } else {
                    SB.swal({ icon: 'error', title: res.message || SB.t('error_deleting') });
                }
            })
            .fail(function () {
                SB.swal({ icon: 'error', title: SB.t('connection_error') });
            });
    });
}
