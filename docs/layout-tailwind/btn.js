// btn.js — Reusable button system (Tailwind + vanilla JS, no external deps)
//
// Two output modes per button:
//   Btn.create(opts)  → HTMLButtonElement  (for direct DOM insertion)
//   Btn.html(opts)    → HTML string        (for use inside DataTable render() or innerHTML)
//
// Presets (DOM):   Btn.edit(onClick, opts?)  Btn.delete(…)  Btn.new(…)  Btn.activate(…)  Btn.deactivate(…)
// Presets (HTML):  Btn.editHtml(onclickStr)  Btn.deleteHtml(…)  Btn.newHtml(…)  etc.
//
// opts shape:
//   text      string    — button label
//   variant   string    — 'primary' | 'secondary' | 'success' | 'danger' | 'warning' | 'ghost'
//   size      string    — 'sm' | 'default' | 'lg'
//   disabled  bool      — opacity-50 + cursor-not-allowed
//   loading   bool      — replaces icon with spinner, disables interaction
//   icon      string    — SVG HTML string prepended to text
//   type      string    — HTML button type (default: 'button')
//   title     string    — tooltip
//   onClick   function  — (create() only) click handler
//   onclick   string    — (html() only)  inline handler string e.g. "handleEdit(1)"

const Btn = (() => {

  // ── Design tokens ───────────────────────────────────────────────────────

  const BASE = [
    'inline-flex items-center justify-center gap-1.5',
    'font-medium rounded-lg',
    'transition-all duration-150',
    'focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2',
    'active:scale-95',
    'select-none whitespace-nowrap',
  ].join(' ');

  const VARIANTS = {
    primary:   'bg-blue-600   text-white     hover:bg-blue-700   focus-visible:ring-blue-500',
    secondary: 'bg-gray-500   text-white     hover:bg-gray-600   focus-visible:ring-gray-400',
    success:   'bg-green-600  text-white     hover:bg-green-700  focus-visible:ring-green-500',
    danger:    'bg-red-600    text-white     hover:bg-red-700    focus-visible:ring-red-500',
    warning:   'bg-yellow-500 text-gray-900  hover:bg-yellow-400 focus-visible:ring-yellow-400',
    ghost:     'bg-transparent border border-gray-300 text-gray-700 hover:bg-gray-100 focus-visible:ring-gray-300',
  };

  const SIZES = {
    sm:      'px-3 py-1 text-sm',
    default: 'px-4 py-2 text-sm',
    lg:      'px-6 py-3 text-base',
  };

  // ── Icons (Heroicons outline, 16 × 16) ──────────────────────────────────

  const IC = {
    edit: `<svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
    </svg>`,

    trash: `<svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
    </svg>`,

    plus: `<svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
    </svg>`,

    check: `<svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
    </svg>`,

    ban: `<svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
    </svg>`,

    spinner: `<svg class="h-4 w-4 shrink-0 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
    </svg>`,
  };

  // ── Internal helpers ─────────────────────────────────────────────────────

  function buildClass(variant, size, disabled, loading) {
    const v = VARIANTS[variant] || VARIANTS.primary;
    const s = SIZES[size]       || SIZES.default;
    const d = (disabled || loading) ? 'opacity-50 cursor-not-allowed pointer-events-none' : '';
    return [BASE, v, s, d].filter(Boolean).join(' ');
  }

  function buildContent(text, icon, loading) {
    const iconHtml = loading ? IC.spinner : (icon || '');
    const textHtml = text   ? `<span>${text}</span>` : '';
    return iconHtml + textHtml;
  }

  // ── Core: DOM element ─────────────────────────────────────────────────────

  function create(opts) {
    const o = Object.assign({
      text: '', variant: 'primary', size: 'default',
      disabled: false, loading: false, icon: '',
      onClick: null, type: 'button', title: '', id: '',
    }, opts);

    const btn = document.createElement('button');
    btn.type      = o.type;
    btn.className = buildClass(o.variant, o.size, o.disabled, o.loading);
    btn.disabled  = o.disabled || o.loading;
    btn.innerHTML = buildContent(o.text, o.icon, o.loading);
    if (o.title) btn.setAttribute('title', o.title);
    if (o.id)    btn.id = o.id;
    if (o.onClick && !o.disabled && !o.loading) {
      btn.addEventListener('click', o.onClick);
    }
    return btn;
  }

  // ── Core: HTML string ─────────────────────────────────────────────────────

  function html(opts) {
    const o = Object.assign({
      text: '', variant: 'primary', size: 'default',
      disabled: false, loading: false, icon: '',
      onclick: '', type: 'button', title: '',
    }, opts);

    const cls     = buildClass(o.variant, o.size, o.disabled, o.loading);
    const content = buildContent(o.text, o.icon, o.loading);
    const attrs   = [
      `type="${o.type}"`,
      `class="${cls}"`,
      (o.disabled || o.loading) ? 'disabled' : '',
      o.title   ? `title="${o.title}"` : '',
      o.onclick ? `onclick="${o.onclick}"` : '',
    ].filter(Boolean).join(' ');

    return `<button ${attrs}>${content}</button>`;
  }

  // ── Preset factory ────────────────────────────────────────────────────────

  function preset(defaults) {
    return {
      // DOM — handler is a JS function
      dom: (onClick, opts) =>
        create(Object.assign({}, defaults, { onClick }, opts)),
      // HTML string — handler is an inline string e.g. "openEdit(42)"
      str: (onclick, opts) =>
        html(Object.assign({}, defaults, { onclick }, opts)),
    };
  }

  const PRESETS = {
    edit:       preset({ text: 'Editar',      variant: 'primary',   size: 'sm', icon: IC.edit  }),
    delete:     preset({ text: 'Eliminar',    variant: 'danger',    size: 'sm', icon: IC.trash }),
    new:        preset({ text: 'Nuevo',       variant: 'primary',              icon: IC.plus  }),
    activate:   preset({ text: 'Activar',     variant: 'success',   size: 'sm', icon: IC.check }),
    deactivate: preset({ text: 'Desactivar',  variant: 'warning',   size: 'sm', icon: IC.ban   }),
  };

  // ── Public API ────────────────────────────────────────────────────────────

  return {
    create,
    html,

    // Icons exposed so callers can reuse them in custom buttons
    icons: IC,

    // DOM presets
    edit:       (onClick, opts) => PRESETS.edit.dom(onClick, opts),
    delete:     (onClick, opts) => PRESETS.delete.dom(onClick, opts),
    new:        (onClick, opts) => PRESETS.new.dom(onClick, opts),
    activate:   (onClick, opts) => PRESETS.activate.dom(onClick, opts),
    deactivate: (onClick, opts) => PRESETS.deactivate.dom(onClick, opts),

    // HTML string presets (use inside DataTable render() or any innerHTML)
    editHtml:       (onclick, opts) => PRESETS.edit.str(onclick, opts),
    deleteHtml:     (onclick, opts) => PRESETS.delete.str(onclick, opts),
    newHtml:        (onclick, opts) => PRESETS.new.str(onclick, opts),
    activateHtml:   (onclick, opts) => PRESETS.activate.str(onclick, opts),
    deactivateHtml: (onclick, opts) => PRESETS.deactivate.str(onclick, opts),
  };

})();
