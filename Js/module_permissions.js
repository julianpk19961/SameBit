var ModulePermissions = (function() {
    var cache = null;
    var profileSlug = null;

    function load(forceReload) {
        if (cache && !forceReload) return Promise.resolve(cache);

        return $.ajax({
            url: '../config/my_permissions.php',
            method: 'GET',
            dataType: 'json'
        }).then(function(data) {
            if (data.success) {
                cache = data.permissions;
                profileSlug = data.profile;
            }
            return cache;
        });
    }

    function can(moduleSlug, permissionSlug) {
        if (!cache) {
            console.warn('ModulePermissions: permisos no cargados. Llama ModulePermissions.init() primero.');
            return false;
        }
        if (profileSlug === 'admin') return true;
        return !!(cache[moduleSlug] && cache[moduleSlug][permissionSlug]);
    }

    function isAdmin() {
        return profileSlug === 'admin';
    }

    function applyUIMask() {
        if (!cache) return;

        $('[data-perm-module]').each(function() {
            var el = $(this);
            var mod = el.data('perm-module');
            var perm = el.data('perm-perm') || 'ingresar';

            if (!can(mod, perm)) {
                if (el.is('button, a')) {
                    el.addClass('d-none');
                } else if (el.is('input, select, textarea')) {
                    el.prop('disabled', true);
                } else {
                    el.addClass('d-none');
                }
            }
        });
    }

    function init() {
        return load().then(function() {
            applyUIMask();
            return cache;
        });
    }

    function refresh() {
        cache = null;
        return init();
    }

    return {
        init: init,
        can: can,
        isAdmin: isAdmin,
        applyUIMask: applyUIMask,
        refresh: refresh,
        load: load
    };
})();
