// admin_profiles.js — Profiles CRUD
// Depends on sb.js loaded globally via header.php

var _profData = {
    profiles: [], modules: [], permissions: [],
    matrix: [], grants: [], user_counts: {}
};
var _activeProfId = null;

$(document).ready(function () {
    loadProfilesData();

    $('#prof-name').on('input', function () {
        if (!$('#prof-id').val()) {
            $('#prof-slug').val(SB.toSlug($(this).val()));
        }
    });
});

// ── Data loading ──────────────────────────────────────────────────────────
function loadProfilesData() {
    SB.api('../config/manage_profiles.php', { action: 'list' })
        .done(function (r) {
            if (r.success) {
                _profData = r;
                renderProfilesTable();
            } else {
                SB.toast(r.message || SB.t('error'), 'error');
            }
        })
        .fail(function () { SB.toast(SB.t('connection_error'), 'error'); });
}

// ── Render table ──────────────────────────────────────────────────────────
function renderProfilesTable() {
    var html = '';
    _profData.profiles.forEach(function (p) {
        var userCnt = _profData.user_counts[p.id] || 0;
        html +=
            '<tr>' +
            '<td><strong>' + SB.esc(p.name) + '</strong></td>' +
            '<td><code>' + SB.esc(p.slug) + '</code></td>' +
            '<td><small class="text-muted">' + SB.esc(p.description || '') + '</small></td>' +
            '<td class="text-center"><span class="badge bg-secondary">' + userCnt + '</span></td>' +
            '<td class="text-center"><span class="badge ' + (p.active ? 'bg-success' : 'bg-secondary') + '">' +
                SB.esc(p.active ? SB.t('active') : SB.t('inactive')) + '</span></td>' +
            '<td class="text-center">' +
                '<button class="btn btn-sm btn-outline-primary me-1" onclick="profilesOpenEdit(\'' + p.id + '\')" title="' + SB.t('edit') + '"><i class="bi bi-pencil"></i></button>' +
                (p.slug !== 'admin'
                    ? '<button class="btn btn-sm btn-outline-danger" onclick="profilesDelete(\'' + p.id + '\', \'' + SB.esc(p.name) + '\')" title="' + SB.t('delete') + '"><i class="bi bi-trash"></i></button>'
                    : '') +
            '</td>' +
            '</tr>';
    });
    $('#profiles-body').html(html);
    SB.table('#profiles-table', { order: [[0, 'asc']] });
}

// ── Open panel: create mode ───────────────────────────────────────────────
function profilesOpenCreate() {
    document.getElementById('profiles-form').reset();
    document.getElementById('prof-id').value = '';
    document.getElementById('prof-slug').readOnly = false;
    document.getElementById('prof-perms-section').classList.add('d-none');
    SB.form.clear('profiles-error');
    SB.panel.setTitle('profiles-panel',
        '<i class="bi bi-plus-circle me-2 text-primary"></i>' + SB.t('new_profile'));
    SB.panel.show('profiles-panel');
}

// ── Open panel: edit mode ─────────────────────────────────────────────────
function profilesOpenEdit(id) {
    var p = _profData.profiles.find(function (x) { return x.id === id; });
    if (!p) return;
    _activeProfId = id;

    document.getElementById('prof-id').value         = p.id;
    document.getElementById('prof-name').value        = p.name;
    document.getElementById('prof-slug').value        = p.slug;
    document.getElementById('prof-slug').readOnly     = true;
    document.getElementById('prof-description').value = p.description || '';
    document.getElementById('prof-active').checked    = p.active == 1;
    SB.form.clear('profiles-error');

    document.getElementById('prof-perms-section').classList.remove('d-none');
    _renderProfilePerms(id);

    SB.panel.setTitle('profiles-panel',
        '<i class="bi bi-pencil me-2 text-primary"></i>' + SB.t('edit_profile'));
    SB.panel.show('profiles-panel');
}

// ── Permissions grid inside offcanvas ─────────────────────────────────────
function _renderProfilePerms(profileId) {
    var html = '<div class="table-responsive"><table class="table table-bordered table-sm align-middle mb-0">' +
        '<thead class="table-light"><tr><th>' + (SB.t('module') || 'Módulo') + '</th>';
    _profData.permissions.forEach(function (perm) {
        html += '<th class="text-center small">' + SB.esc(perm.name) + '</th>';
    });
    html += '</tr></thead><tbody>';

    _profData.modules.forEach(function (mod) {
        html += '<tr><td class="small"><strong>' + SB.esc(mod.name) + '</strong></td>';
        _profData.permissions.forEach(function (perm) {
            var supported = _profData.matrix.some(function (mp) {
                return mp.module_id === mod.id && mp.permission_id === perm.id;
            });
            if (!supported) { html += '<td class="text-center text-muted bg-light">—</td>'; return; }

            var grant = _profData.grants.find(function (g) {
                return g.profile_id === profileId &&
                       g.module_slug === mod.slug &&
                       g.permission_slug === perm.slug;
            });
            var checked = grant && grant.can_access == 1;
            html += '<td class="text-center">' +
                '<div class="form-check form-switch d-flex justify-content-center m-0">' +
                '<input class="form-check-input" type="checkbox" role="switch"' +
                ' data-profile="' + profileId + '" data-module="' + mod.slug + '" data-perm="' + perm.slug + '"' +
                (checked ? ' checked' : '') + ' onchange="profilesToggleGrant(this)">' +
                '</div></td>';
        });
        html += '</tr>';
    });
    html += '</tbody></table></div>';
    document.getElementById('prof-perms-grid').innerHTML = html;
}

// ── Form submit ───────────────────────────────────────────────────────────
$(document).on('submit', '#profiles-form', function (e) {
    e.preventDefault();
    SB.form.clear('profiles-error');

    var name = document.getElementById('prof-name').value.trim();
    var slug = document.getElementById('prof-slug').value.trim();

    if (!name) { SB.form.error(SB.t('name') + ': ' + SB.t('required'), 'profiles-error'); return; }
    if (!slug) { SB.form.error('Slug: ' + SB.t('required'), 'profiles-error'); return; }

    SB.api('../config/manage_profiles.php', {
        action:      'save_profile',
        id:          document.getElementById('prof-id').value,
        name:        name,
        slug:        slug,
        description: document.getElementById('prof-description').value.trim(),
        active:      document.getElementById('prof-active').checked ? 1 : 0
    }).done(function (r) {
        if (r.success) {
            SB.panel.hide('profiles-panel');
            SB.toast(r.message || SB.t('success')).then(loadProfilesData);
        } else {
            SB.form.error(r.message || SB.t('error_saving'), 'profiles-error');
        }
    }).fail(function () {
        SB.form.error(SB.t('connection_error'), 'profiles-error');
    });
});

// ── Delete ────────────────────────────────────────────────────────────────
function profilesDelete(id, name) {
    SB.confirm({
        html: 'Se eliminará el perfil <strong>' + SB.esc(name) + '</strong> y todos sus permisos.',
        confirmButtonText:  SB.t('delete') || 'Eliminar',
        confirmButtonColor: '#dc3545'
    }).then(function (r) {
        if (!r.isConfirmed) return;
        SB.api('../config/manage_profiles.php', { action: 'delete_profile', id: id })
            .done(function (res) {
                if (res.success) SB.toast(res.message || SB.t('deleted')).then(loadProfilesData);
                else SB.swal({ icon: 'error', title: res.message });
            })
            .fail(function () { SB.swal({ icon: 'error', title: SB.t('connection_error') }); });
    });
}

// ── Toggle permission grant ───────────────────────────────────────────────
function profilesToggleGrant(checkbox) {
    var profileId  = $(checkbox).data('profile');
    var moduleSlug = $(checkbox).data('module');
    var permSlug   = $(checkbox).data('perm');
    var canAccess  = $(checkbox).is(':checked') ? 1 : 0;

    SB.api('../config/manage_profiles.php', {
        action:          'toggle_grant',
        profile_id:      profileId,
        module_slug:     moduleSlug,
        permission_slug: permSlug,
        can_access:      canAccess
    }).done(function (r) {
        if (r.success) {
            _profData.grants = _profData.grants.filter(function (g) {
                return !(g.profile_id === profileId &&
                         g.module_slug === moduleSlug &&
                         g.permission_slug === permSlug);
            });
            _profData.grants.push({
                profile_id:      profileId,
                module_slug:     moduleSlug,
                permission_slug: permSlug,
                can_access:      canAccess
            });
        } else {
            $(checkbox).prop('checked', !canAccess);
            SB.swal({ icon: 'error', title: r.message });
        }
    }).fail(function () { $(checkbox).prop('checked', !canAccess); });
}
