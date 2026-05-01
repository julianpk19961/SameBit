// admin_permissions.js — Permissions matrix toggles
// Depends on sb.js loaded globally via header.php

function togglePermission(cb) {
    var profileId  = $(cb).data('profile-id');
    var moduleSlug = $(cb).data('module-slug');
    var permSlug   = $(cb).data('permission-slug');
    var canAccess  = $(cb).is(':checked') ? 1 : 0;

    SB.api('../config/manage_profiles.php', {
        action:          'toggle_grant',
        profile_id:      profileId,
        module_slug:     moduleSlug,
        permission_slug: permSlug,
        can_access:      canAccess
    }).done(function (r) {
        if (!r.success) {
            SB.swal({ icon: 'error', title: 'Error', text: r.message || 'Error al actualizar' });
            $(cb).prop('checked', !canAccess);
        }
    }).fail(function () {
        $(cb).prop('checked', !canAccess);
    });
}
