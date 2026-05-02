// tabs.js — Tabs component (Tailwind + vanilla JS, no external deps)
//
// Usage:
//   const tabs = new Tabs('container-id', {
//     tabs: [
//       { label: 'Activos',   key: 'active',   default: true },
//       { label: 'Inactivos', key: 'inactive' },
//     ],
//     onChange: (key) => table.setData(data.filter(r => r.active === (key === 'active'))),
//   });
//
// API:
//   tabs.setActive(key)          — switch tab programmatically (fires onChange)
//   tabs.getActive()             — returns current active key
//   tabs.setBadge(key, count)    — update the count badge on a tab (null hides it)

class Tabs {
  constructor(containerId, options = {}) {
    this.el = typeof containerId === 'string'
      ? document.getElementById(containerId)
      : containerId;

    if (!this.el) throw new Error(`Tabs: element not found — "${containerId}"`);

    this.tabs     = options.tabs     || [];
    this.onChange = options.onChange || function () {};

    const defaultTab = this.tabs.find(t => t.default) || this.tabs[0];
    this._active = defaultTab ? defaultTab.key : null;

    // refs to badge elements per key
    this._badges = {};
    // refs to button elements per key
    this._buttons = {};

    this._build();
  }

  // ── Public API ─────────────────────────────────────────────────────────

  setActive(key) {
    if (this._active === key) return;
    this._active = key;
    this._update();
    this.onChange(key);
  }

  getActive() {
    return this._active;
  }

  setBadge(key, count) {
    const badge = this._badges[key];
    if (!badge) return;
    if (count === null || count === undefined) {
      badge.className = 'hidden';
    } else {
      badge.textContent = count;
      badge.className = this._badgeClass(key === this._active);
    }
  }

  // ── Internal ───────────────────────────────────────────────────────────

  _build() {
    this.el.innerHTML = '';
    this.el.setAttribute('role', 'tablist');
    this.el.className = 'flex border-b border-gray-200';

    this.tabs.forEach(tab => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.setAttribute('role', 'tab');
      btn.setAttribute('aria-selected', tab.key === this._active ? 'true' : 'false');
      btn.setAttribute('aria-controls', 'tabpanel-' + tab.key);

      // Label
      const label = document.createElement('span');
      label.textContent = tab.label;

      // Badge
      const badge = document.createElement('span');
      badge.className = 'hidden';
      this._badges[tab.key] = badge;

      btn.appendChild(label);
      btn.appendChild(badge);

      btn.addEventListener('click', () => this.setActive(tab.key));

      this._buttons[tab.key] = btn;
      this.el.appendChild(btn);
    });

    this._update();
  }

  _update() {
    this.tabs.forEach(tab => {
      const btn   = this._buttons[tab.key];
      const badge = this._badges[tab.key];
      const isActive = tab.key === this._active;

      btn.className = this._btnClass(isActive);
      btn.setAttribute('aria-selected', isActive ? 'true' : 'false');

      // Re-apply badge class to reflect active color
      if (badge && !badge.classList.contains('hidden')) {
        badge.className = this._badgeClass(isActive);
      }
    });
  }

  _btnClass(isActive) {
    const base = [
      'inline-flex items-center gap-1.5',
      'px-4 py-2.5 -mb-px',
      'text-sm font-medium',
      'border-b-2',
      'transition-colors duration-150',
      'focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-inset',
      'whitespace-nowrap',
    ].join(' ');

    const state = isActive
      ? 'text-blue-600 border-blue-600'
      : 'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300';

    return base + ' ' + state;
  }

  _badgeClass(isActive) {
    const base = 'inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 text-xs font-bold rounded-full';
    return base + (isActive ? ' bg-blue-100 text-blue-600' : ' bg-gray-100 text-gray-500');
  }
}
