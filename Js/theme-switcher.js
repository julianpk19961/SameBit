/**
 * Theme Switcher - Manages light/dark mode across the application
 * Persists user preference in localStorage
 */

class ThemeSwitcher {
  constructor(options = {}) {
    this.storageKey = options.storageKey || 'app-theme';
    this.defaultTheme = options.defaultTheme || 'light';
    this.themes = options.themes || ['light', 'dark'];
    this.init();
  }

  init() {
    this.loadTheme();
    this.setupButton();
    this.listenSystemPreference();
  }

  /**
   * Load theme from localStorage or system preference
   */
  loadTheme() {
    const savedTheme = localStorage.getItem(this.storageKey);
    
    if (savedTheme && this.themes.includes(savedTheme)) {
      this.setTheme(savedTheme);
    } else {
      // Use system preference
      const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
      this.setTheme(prefersDark ? 'dark' : 'light');
    }
  }

  /**
   * Set the active theme
   * @param {string} theme - Theme name to activate
   */
  setTheme(theme) {
    if (!this.themes.includes(theme)) {
      console.warn(`Theme "${theme}" not available`);
      return;
    }

    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem(this.storageKey, theme);
    this.updateButtonState(theme);
    this.dispatchEvent('themechange', theme);
  }

  /**
   * Toggle between available themes
   */
  toggle() {
    const currentTheme = document.documentElement.getAttribute('data-theme') || this.defaultTheme;
    const currentIndex = this.themes.indexOf(currentTheme);
    const nextIndex = (currentIndex + 1) % this.themes.length;
    this.setTheme(this.themes[nextIndex]);
  }

  /**
   * Get current active theme
   */
  getTheme() {
    return document.documentElement.getAttribute('data-theme') || this.defaultTheme;
  }

  /**
   * Setup theme toggle button
   */
  setupButton() {
    const button = document.querySelector('.theme-toggle');
    if (!button) return;

    button.addEventListener('click', () => this.toggle());
    button.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        this.toggle();
      }
    });

    // Keyboard shortcut: Ctrl+Shift+T or Cmd+Shift+T
    document.addEventListener('keydown', (e) => {
      if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'T') {
        e.preventDefault();
        this.toggle();
      }
    });
  }

  /**
   * Update button state/icon
   */
  updateButtonState(theme) {
    const button = document.querySelector('.theme-toggle');
    if (!button) return;

    button.classList.remove('light-mode', 'dark-mode');
    button.classList.add(`${theme}-mode`);
    button.setAttribute('aria-label', `Switch to ${theme === 'light' ? 'dark' : 'light'} mode`);
  }

  /**
   * Listen for system preference changes
   */
  listenSystemPreference() {
    const darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');
    
    darkModeQuery.addEventListener('change', (e) => {
      // Only apply if user hasn't manually set a preference
      if (!localStorage.getItem(this.storageKey)) {
        this.setTheme(e.matches ? 'dark' : 'light');
      }
    });
  }

  /**
   * Dispatch custom event
   */
  dispatchEvent(eventName, detail) {
    window.dispatchEvent(
      new CustomEvent(eventName, { detail })
    );
  }
}

/**
 * Initialize theme switcher on DOM ready
 */
document.addEventListener('DOMContentLoaded', () => {
  window.themeSwitcher = new ThemeSwitcher({
    storageKey: 'samebit-theme',
    defaultTheme: 'light',
    themes: ['light', 'dark']
  });

  // Listen for theme changes and update other parts of the app
  window.addEventListener('themechange', (e) => {
    console.log('Theme changed to:', e.detail);
    // You can add custom logic here for other components
  });
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
  module.exports = ThemeSwitcher;
}
