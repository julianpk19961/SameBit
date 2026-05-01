// admin_modules.js — Modules CRUD
// Depends on sb.js loaded globally via header.php

var _modData = { modules: [], permissions: [], profiles: [], matrix: [], grants: [] };

$(document).ready(function () {
    loadModulesData();

    $('#mod-name').on('input', function () {
        if (!$('#mod-id').val()) {
            $('#mod-slug').val(SB.toSlug($(this).val()));
        }
    });
});

// ── Data loading ──────────────────────────────────────────────────────────
function loadModulesData() {
    SB.api('../config/manage_modules.php', { action: 'list' })
        .done(function (r) {
            if (r.success) {
                _modData = r;
                renderModulesTable();
            } else {
                SB.toast(r.message || SB.t('error'), 'error');
            }
        })
        .fail(function () { SB.toast(SB.t('connection_error'), 'error'); });
}

// ── Render table ──────────────────────────────────────────────────────────
function renderModulesTable() {
    var html = '';
    _modData.modules.forEach(function (mod) {
        html += '<tr>' +
            '<td><strong>' + SB.esc(mod.name) + '</strong></td>' +
            '<td><code>' + SB.esc(mod.slug) + '</code></td>' +
            '<td><small class="text-muted">' + SB.esc(mod.description || '') + '</small></td>' +
            '<td class="text-center"><span class="badge ' + (mod.active ? 'bg-success' : 'bg-secondary') + '">' +
                SB.esc(mod.active ? SB.t('active') : SB.t('inactive')) + '</span></td>' +
            '<td class="text-center">' +
                '<button class="btn btn-sm btn-outline-primary me-1" onclick="modulesViewPermissions(\'' + mod.id + '\')" title="Permisos"><i class="bi bi-shield-lock"></i></button>' +
                '<button class="btn btn-sm btn-outline-warning me-1" onclick="modulesOpenEdit(\'' + mod.id + '\')" title="' + SB.t('edit') + '"><i class="bi bi-pencil"></i></button>' +
                '<button class="btn btn-sm btn-outline-danger" onclick="modulesDelete(\'' + mod.id + '\', \'' + SB.esc(mod.name) + '\')" title="' + SB.t('delete') + '"><i class="bi bi-trash"></i></button>' +
            '</td>' +
        '</tr>';
    });
    $('#modules-body').html(html);
    SB.table('#modules-table', { order: [[0, 'asc']] });
}

// ── Render permission + profile checkboxes inside the panel ───────────────
function _renderModuleCheckboxes(moduleId) {
    var mod = moduleId
        ? _modData.modules.find(function (m) { return m.id === moduleId; })
        : null;

    var permHtml = '';
    _modData.permissions.forEach(function (p) {
        var linked = mod && _modData.matrix.some(function (mp) {
            return mp.module_id === moduleId && mp.permission_id === p.id;
        });
        permHtml +=
            '<div class="col-6 col-md-4"><div class="form-check">' +
            '<input class="form-check-input mod-perm-check" type="checkbox" id="modp_' + p.id + '" value="' + p.id + '"' +
            (!mod || linked ? ' checked' : '') + '>' +
            '<label class="form-check-label" for="modp_' + p.id + '">' + SB.esc(p.name) + '</label>' +
            '</div></div>';
    });
    $('#mod-perms').html(permHtml);

    var profHtml = '';
    _modData.profiles.forEach(function (pr) {
        var hasAny = mod && _modData.grants.some(function (g) {
            return g.module_slug === mod.slug && g.profile_id === pr.id && g.can_access == 1;
        });
        profHtml +=
            '<div class="col-6"><div class="form-check">' +
            '<input class="form-check-input mod-prof-check" type="checkbox" id="modpr_' + pr.id + '" value="' + pr.id + '"' +
            (hasAny ? ' checked' : '') + '>' +
            '<label class="form-check-label" for="modpr_' + pr.id + '">' + SB.esc(pr.name) + '</label>' +
            '</div></div>';
    });
    $('#mod-profiles').html(profHtml);
}

// ── Open panel: create mode ───────────────────────────────────────────────
function modulesOpenCreate() {
    document.getElementById('modules-form').reset();
    document.getElementById('mod-id').value = '';
    document.getElementById('mod-slug').readOnly = false;
    SB.form.clear('modules-error');
    _renderModuleCheckboxes(null);
    SB.panel.setTitle('modules-panel',
        '<i class="bi bi-plus-circle me-2 text-primary"></i>' + SB.t('new_module'));
    SB.panel.show('modules-panel');
}

// ── Open panel: edit mode ─────────────────────────────────────────────────
function modulesOpenEdit(id) {
    var mod = _modData.modules.find(function (m) { return m.id === id; });
    if (!mod) return;

    document.getElementById('mod-id').value         = mod.id;
    document.getElementById('mod-name').value        = mod.name;
    document.getElementById('mod-slug').value        = mod.slug;
    document.getElementById('mod-slug').readOnly     = true;
    document.getElementById('mod-description').value = mod.description || '';
    document.getElementById('mod-active').checked    = mod.active == 1;
    SB.form.clear('modules-error');
    _renderModuleCheckboxes(id);
    SB.panel.setTitle('modules-panel',
        '<i class="bi bi-pencil me-2 text-primary"></i>' + SB.t('edit_module'));
    SB.panel.show('modules-panel');
}

// ── Form submit ───────────────────────────────────────────────────────────
$(document).on('submit', '#modules-form', function (e) {
    e.preventDefault();
    SB.form.clear('modules-error');

    var name = document.getElementById('mod-name').value.trim();
    var slug = document.getElementById('mod-slug').value.trim();

    if (!name) { SB.form.error(SB.t('name') + ': ' + SB.t('required'), 'modules-error'); return; }
    if (!slug) { SB.form.error('Slug: ' + SB.t('required'), 'modules-error'); return; }

    var perms = [], profs = [];
    $('.mod-perm-check:checked').each(function () { perms.push($(this).val()); });
    $('.mod-prof-check:checked').each(function () { profs.push($(this).val()); });

    SB.api('../config/manage_modules.php', {
        action:         'save_module',
        id:             document.getElementById('mod-id').value,
        name:           name,
        slug:           slug,
        description:    document.getElementById('mod-description').value.trim(),
        active:         document.getElementById('mod-active').checked ? 1 : 0,
        permissions:    perms,
        grant_profiles: profs
    }).done(function (r) {
        if (r.success) {
            SB.panel.hide('modules-panel');
            SB.toast(r.message || SB.t('success')).then(loadModulesData);
        } else {
            SB.form.error(r.message || SB.t('error_saving'), 'modules-error');
        }
    }).fail(function () {
        SB.form.error(SB.t('connection_error'), 'modules-error');
    });
});

// ── Delete ────────────────────────────────────────────────────────────────
function modulesDelete(id, name) {
    SB.confirm({
        html: 'Se eliminará el módulo <strong>' + SB.esc(name) + '</strong> y todos sus permisos.',
        confirmButtonText:  SB.t('delete') || 'Eliminar',
        confirmButtonColor: '#dc3545'
    }).then(function (r) {
        if (!r.isConfirmed) return;
        SB.api('../config/manage_modules.php', { action: 'delete_module', id: id })
            .done(function (res) {
                if (res.success) SB.toast(res.message || SB.t('deleted')).then(loadModulesData);
                else SB.swal({ icon: 'error', title: res.message });
            })
            .fail(function () { SB.swal({ icon: 'error', title: SB.t('connection_error') }); });
    });
}

// ── Permissions matrix modal ──────────────────────────────────────────────
function modulesViewPermissions(moduleId) {
    var mod = _modData.modules.find(function (m) { return m.id === moduleId; });
    if (!mod) return;

    var $body = $('#modules-perms-body');
    $body.html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');
    $('#modules-perms-title').html(
        '<i class="bi bi-shield-lock me-2"></i>' + SB.esc(mod.name) + ' — Permisos por Perfil');
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modules-perms-modal')).show();

    var modulePerms = _modData.matrix.filter(function (mp) { return mp.module_id === moduleId; });

    var html = '<div class="table-responsive"><table class="table table-bordered table-sm align-middle">' +
        '<thead class="table-light"><tr><th>' + SB.t('permission') + '</th>';
    _modData.profiles.forEach(function (pr) {
        html += '<th class="text-center">' + SB.esc(pr.name) + '</th>';
    });
    html += '</tr></thead><tbody>';

    _modData.permissions.forEach(function (perm) {
        var mpEntry = modulePerms.find(function (mp) { return mp.permission_id === perm.id; });
        html += '<tr><td><strong>' + SB.esc(perm.name) + '</strong></td>';
        _modData.profiles.forEach(function (pr) {
            if (!mpEntry) { html += '<td class="text-center text-muted bg-light">—</td>'; return; }
            var grant = _modData.grants.find(function (g) {
                return g.module_slug === mod.slug &&
                       g.permission_slug === perm.slug &&
                       g.profile_id === pr.id;
            });
            var checked = grant && grant.can_access == 1;
            html += '<td class="text-center"><div class="form-check form-switch d-flex justify-content-center">' +
                '<input class="form-check-input" type="checkbox" role="switch"' +
                ' data-profile="' + pr.id + '" data-module="' + mod.slug + '" data-perm="' + perm.slug + '"' +
                (checked ? ' checked' : '') + ' onchange="modulesToggleGrant(this)">' +
                '</div></td>';
        });
        html += '</tr>';
    });
    html += '</tbody></table></div>';
    $body.html(html);
}

function modulesToggleGrant(checkbox) {
    var profileId  = $(checkbox).data('profile');
    var moduleSlug = $(checkbox).data('module');
    var permSlug   = $(checkbox).data('perm');
    var canAccess  = $(checkbox).is(':checked') ? 1 : 0;

    SB.api('../config/manage_modules.php', {
        action:          'toggle_grant',
        profile_id:      profileId,
        module_slug:     moduleSlug,
        permission_slug: permSlug,
        can_access:      canAccess
    }).done(function (r) {
        if (r.success) {
            _modData.grants = _modData.grants.filter(function (g) {
                return !(g.profile_id === profileId &&
                         g.module_slug === moduleSlug &&
                         g.permission_slug === permSlug);
            });
            _modData.grants.push({
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
