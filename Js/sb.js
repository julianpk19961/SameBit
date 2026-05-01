/**
 * sb.js — SameBit Core Frontend Library
 *
 * Shared across all CRUD pages for consistent UX patterns.
 *
 *   SB.t(key)            — i18n from window.LANG
 *   SB.esc(str)          — XSS-safe HTML escaping
 *   SB.api(url, data)    — jQuery POST wrapper, returns Deferred
 *   SB.table(sel, opts)  — DataTable with standard config
 *   SB.toSlug(str)       — text → URL-safe slug
 *   SB.panel.*           — Offcanvas right-panel manager
 *   SB.form.*            — Error banner helpers
 *   SB.swal(opts)        — Swal.fire with offcanvas blur fix
 *   SB.toast(msg, icon)  — Quick notification (2s auto-close)
 *   SB.confirm(opts)     — Swal confirm dialog (returns Promise)
 */
(function (global, $) {
    'use strict';

    var L = global.LANG || {};

    var SB = {

        // ── Translation ──────────────────────────────────────────────────────
        t: function (key) {
            return L[key] || key;
        },

        // ── XSS-safe escaping ────────────────────────────────────────────────
        esc: function (str) {
            var d = document.createElement('div');
            d.appendChild(document.createTextNode(str != null ? String(str) : ''));
            return d.innerHTML;
        },

        // ── Unified AJAX POST ────────────────────────────────────────────────
        // Returns jQuery Deferred — use .done(fn) / .fail(fn)
        api: function (url, data) {
            return $.ajax({
                url:      url,
                method:   'POST',
                dataType: 'json',
                data:     data || {}
            });
        },

        // ── DataTable factory ────────────────────────────────────────────────
        // Merges standard options with page-specific overrides.
        table: function (selector, opts) {
            var langUrl  = L.datatables_lang_url || '';
            var defaults = {
                pageLength: 25,
                lengthMenu: [[10, 25, 50], [10, 25, 50]],
                order:      [[0, 'asc']],
                language:   langUrl ? { url: langUrl } : {},
                dom:        '<"d-flex justify-content-between align-items-center mb-2"lf>rtip',
                responsive: true,
                destroy:    true
            };
            return $(selector).DataTable($.extend(true, {}, defaults, opts || {}));
        },

        // ── Slug generator ───────────────────────────────────────────────────
        toSlug: function (str) {
            return String(str).toLowerCase()
                .normalize('NFD').replace(/[̀-ͯ]/g, '')
                .replace(/[^a-z0-9\s_]/g, '')
                .replace(/\s+/g, '_')
                .substring(0, 100);
        },

        // ── Offcanvas panel manager ──────────────────────────────────────────
        panel: {
            show: function (id) {
                var el = document.getElementById(id);
                if (el) bootstrap.Offcanvas.getOrCreateInstance(el).show();
            },
            hide: function (id) {
                var el = document.getElementById(id);
                if (el) {
                    var inst = bootstrap.Offcanvas.getInstance(el);
                    if (inst) inst.hide();
                }
            },
            // panelId must match the element id; title element must be id="{panelId}-title"
            setTitle: function (panelId, html) {
                var el = document.getElementById(panelId + '-title');
                if (el) el.innerHTML = html;
            },
            onShow: function (id, fn) {
                var el = document.getElementById(id);
                if (el) el.addEventListener('shown.bs.offcanvas', fn);
            },
            onHide: function (id, fn) {
                var el = document.getElementById(id);
                if (el) el.addEventListener('hide.bs.offcanvas', fn);
            }
        },

        // ── Form error banner ────────────────────────────────────────────────
        // The banner element must contain a <span class="sb-error-text"> child.
        // bannerId defaults to 'sb-error-banner'.
        form: {
            error: function (msg, bannerId) {
                var bid = bannerId || 'sb-error-banner';
                var $b  = $('#' + bid);
                $b.find('.sb-error-text').text(msg);
                $b.removeClass('d-none');
                var el = document.getElementById(bid);
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            },
            clear: function (bannerId) {
                var bid = bannerId || 'sb-error-banner';
                $('#' + bid).addClass('d-none').find('.sb-error-text').text('');
            }
        },

        // ── Internal: blur focused element before opening Swal ───────────────
        _blur: function () {
            var f = document.activeElement;
            if (f && f !== document.body) f.blur();
        },

        // ── Swal wrappers ────────────────────────────────────────────────────
        swal: function (opts) {
            SB._blur();
            return Swal.fire(opts);
        },

        toast: function (msg, icon) {
            SB._blur();
            return Swal.fire({
                icon:                icon || 'success',
                title:               msg,
                timer:               2000,
                showConfirmButton:   false
            });
        },

        confirm: function (opts) {
            SB._blur();
            return Swal.fire($.extend({
                icon:               'warning',
                showCancelButton:   true,
                cancelButtonText:   L.cancel  || 'Cancelar',
                confirmButtonText:  L.confirm || 'Confirmar'
            }, opts));
        }
    };

    global.SB = SB;

})(window, jQuery);
