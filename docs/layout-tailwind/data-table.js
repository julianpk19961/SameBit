// data-table.js — Reusable Tailwind table component (vanilla JS, no external deps)
//
// Usage:
//   const table = new DataTable('container-id', { columns, emptyText, loadingText });
//   table.setData(rows)    — renders data
//   table.setLoading()     — shows spinner
//   table.setError(msg)    — shows error message
//
// Column shape:
//   { label: 'Name', field: 'name' }
//   { label: 'Status', field: 'status', render: (val, row, i) => `<span>…</span>` }
//   { label: '', field: null, render: (val, row, i) => `<button>…</button>` }
//     └─ columns with no label are treated as action columns (full-width in cards)

class DataTable {
  constructor(containerId, options = {}) {
    this.el = typeof containerId === 'string'
      ? document.getElementById(containerId)
      : containerId;

    if (!this.el) throw new Error(`DataTable: element not found — "${containerId}"`);

    this.columns     = options.columns     || [];
    this.emptyText   = options.emptyText   || 'No hay registros';
    this.loadingText = options.loadingText || 'Cargando…';

    this._state    = 'loading';
    this._rows     = [];
    this._errorMsg = '';

    this._build();
    this._update();
  }

  // ── Public API ────────────────────────────────────────────────────────────

  setData(rows) {
    this._rows  = Array.isArray(rows) ? rows : [];
    this._state = this._rows.length ? 'data' : 'empty';
    this._update();
  }

  setLoading() {
    this._state = 'loading';
    this._update();
  }

  setError(message) {
    this._errorMsg = message || 'Error al cargar los datos.';
    this._state    = 'error';
    this._update();
  }

  // ── DOM construction (runs once at init) ──────────────────────────────────

  _build() {
    this.el.innerHTML = '';

    // Table (visible on md+)
    this._tableWrap = this._el('div');
    const table = this._el('table', 'min-w-full text-sm');

    const thead  = this._el('thead', 'bg-gray-800 text-white');
    const headTr = this._el('tr');
    this.columns.forEach(col => {
      const th = this._el('th',
        'px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap');
      th.textContent = col.label;
      headTr.appendChild(th);
    });
    thead.appendChild(headTr);

    this._tbody = this._el('tbody', 'divide-y divide-gray-200');
    table.appendChild(thead);
    table.appendChild(this._tbody);
    this._tableWrap.appendChild(table);
    this.el.appendChild(this._tableWrap);

    // Cards (visible below md)
    this._cardsWrap = this._el('div');
    this.el.appendChild(this._cardsWrap);

    // Overlay: loading / empty / error
    this._overlay = this._el('div');
    this.el.appendChild(this._overlay);
  }

  // ── Re-render whenever state changes ─────────────────────────────────────

  _update() {
    const isData = this._state === 'data';

    this._tableWrap.className = isData
      ? 'hidden md:block overflow-x-auto rounded-xl border border-gray-200 shadow-sm'
      : 'hidden';

    this._cardsWrap.className = isData
      ? 'md:hidden space-y-3'
      : 'hidden';

    this._overlay.className  = isData ? 'hidden' : '';
    this._overlay.innerHTML  = '';
    this._tbody.innerHTML    = '';
    this._cardsWrap.innerHTML = '';

    if (!isData) {
      const tpl = { loading: this._tplLoading, empty: this._tplEmpty, error: this._tplError };
      this._overlay.innerHTML = (tpl[this._state] || function () { return ''; }).call(this);
      return;
    }

    const dataCols   = this.columns.filter(col => col.label);
    const actionCols = this.columns.filter(col => !col.label && col.render);

    this._rows.forEach((row, i) => {
      // ── Table row ──────────────────────────────────────────────────────
      const tr = this._el('tr',
        'bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors duration-100');

      this.columns.forEach(col => {
        const td  = this._el('td', 'px-4 py-3 text-gray-700');
        const val = col.field != null ? row[col.field] : undefined;
        td.innerHTML = col.render ? col.render(val, row, i) : this._esc(val ?? '');
        tr.appendChild(td);
      });
      this._tbody.appendChild(tr);

      // ── Card ───────────────────────────────────────────────────────────
      const card = this._el('div',
        'bg-white rounded-xl border border-gray-200 shadow-sm p-4');

      // Data fields
      if (dataCols.length) {
        const fields = this._el('div', 'space-y-2');
        dataCols.forEach(col => {
          const val     = col.field != null ? row[col.field] : undefined;
          const content = col.render ? col.render(val, row, i) : this._esc(val ?? '');
          const line    = this._el('div', 'flex items-start gap-3 text-sm');

          const lbl = this._el('span',
            'font-semibold text-gray-400 shrink-0 w-28 pt-0.5 text-xs uppercase tracking-wide');
          lbl.textContent = col.label;

          const value = this._el('span', 'text-gray-800 flex-1 min-w-0 break-words');
          value.innerHTML = content;

          line.appendChild(lbl);
          line.appendChild(value);
          fields.appendChild(line);
        });
        card.appendChild(fields);
      }

      // Action row (full width, separated by border)
      if (actionCols.length) {
        const actRow = this._el('div',
          dataCols.length
            ? 'flex items-center gap-2 flex-wrap pt-3 mt-3 border-t border-gray-100'
            : 'flex items-center gap-2 flex-wrap');
        actionCols.forEach(col => {
          const val = col.field != null ? row[col.field] : undefined;
          const wrap = this._el('span');
          wrap.innerHTML = col.render(val, row, i);
          actRow.appendChild(wrap);
        });
        card.appendChild(actRow);
      }

      this._cardsWrap.appendChild(card);
    });
  }

  // ── State templates ───────────────────────────────────────────────────────

  _tplLoading() {
    return `
      <div class="flex flex-col items-center justify-center py-16 gap-3 text-gray-400">
        <svg class="animate-spin h-7 w-7 text-gray-300" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10"
                  stroke="currentColor" stroke-width="4"/>
          <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
        </svg>
        <span class="text-sm">${this._esc(this.loadingText)}</span>
      </div>`;
  }

  _tplEmpty() {
    return `
      <div class="flex flex-col items-center justify-center py-16 gap-3 text-gray-400">
        <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5
               M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/>
        </svg>
        <span class="text-sm font-medium">${this._esc(this.emptyText)}</span>
      </div>`;
  }

  _tplError() {
    return `
      <div class="flex flex-col items-center justify-center py-16 gap-3">
        <svg class="h-10 w-10 text-red-300" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73
               0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898
               0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
        </svg>
        <span class="text-sm font-medium text-red-500">${this._esc(this._errorMsg)}</span>
      </div>`;
  }

  // ── Helpers ───────────────────────────────────────────────────────────────

  _el(tag, classes = '') {
    const el = document.createElement(tag);
    if (classes) el.className = classes;
    return el;
  }

  _esc(value) {
    const div = document.createElement('div');
    div.textContent = String(value);
    return div.innerHTML;
  }
}
