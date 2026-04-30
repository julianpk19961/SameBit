# Theme System Documentation

## Overview
SameBit now includes a comprehensive theme system that supports light/dark modes with a scalable, component-based architecture.

## File Structure

```
/css/
├── themes/
│   └── variables.css       # Theme color variables & CSS custom properties
├── components/
│   ├── theme-toggle.css    # Theme toggle button styles
│   ├── buttons.css         # Reusable button components
│   ├── forms.css           # Reusable form components
│   └── cards.css           # Reusable card components
├── Login.css               # Login page (uses theme system)
├── main.css                # Main application styles
└── ... (other styles)

/Js/
├── theme-switcher.js       # Theme switching logic
├── Login.js                # Login form handler
└── ... (other scripts)
```

## Usage

### Theme Variables (CSS)
All colors are defined as CSS custom properties in `variables.css`:

```css
/* Example: Using theme variables */
.my-button {
  background: var(--color-btn);
  color: var(--color-text);
  border: 1px solid var(--color-border);
}
```

**Available Variables:**
- `--color-bg-from`, `--color-bg-to` - Background gradient colors
- `--color-text`, `--color-text-muted` - Text colors
- `--color-btn`, `--color-btn-hover` - Button colors
- `--color-input-bg`, `--color-input-hover` - Input field colors
- `--color-label` - Label backgrounds
- `--color-border` - Border colors
- `--color-error`, `--color-warning`, `--color-success`, `--color-info` - Status colors
- `--radius`, `--radius-lg` - Border radius values

### Theme Switching (JavaScript)

#### Manual Usage
```javascript
// Get the current theme
const current = window.themeSwitcher.getTheme(); // 'light' or 'dark'

// Set a specific theme
window.themeSwitcher.setTheme('dark');

// Toggle between themes
window.themeSwitcher.toggle();
```

#### Listen for Theme Changes
```javascript
window.addEventListener('themechange', (e) => {
  console.log('Theme changed to:', e.detail); // 'light' or 'dark'
  // Update your custom components here
});
```

#### HTML Button
The theme toggle button is automatically initialized:
```html
<button class="theme-toggle light-mode" aria-label="Switch to dark mode">
  <svg class="sun-icon">...</svg>
  <svg class="moon-icon">...</svg>
</button>
```

**Keyboard Shortcut:** `Ctrl+Shift+T` (or `Cmd+Shift+T` on Mac)

### Persistence
User's theme preference is automatically saved to `localStorage` under the key `samebit-theme`.

## Theme Detection Order

1. **User Preference** - Saved in localStorage (highest priority)
2. **System Preference** - Uses `prefers-color-scheme: dark` media query
3. **Default** - Falls back to 'light' theme

## Component Examples

### Button Component
```html
<button class="btn btn-primary">Primary Button</button>
<button class="btn btn-secondary">Secondary Button</button>
<button class="btn btn-danger">Delete</button>
<button class="btn btn-lg btn-block">Full Width Button</button>
```

### Form with Icon
```html
<div class="form__field">
  <label for="email">
    <i class="bi bi-envelope"></i>
  </label>
  <input id="email" type="email" class="form__input" placeholder="Email">
</div>
```

### Card Component
```html
<div class="card glass">
  <div class="card__header">
    <h3 class="card__title">Card Title</h3>
  </div>
  <div class="card__body">
    Card content here
  </div>
  <div class="card__footer">
    <button class="btn btn-primary">Action</button>
  </div>
</div>
```

## Adding New Themes

To add a new theme (e.g., 'auto'):

1. **Update `variables.css`:**
```css
[data-theme="auto"] {
  /* Your custom variables */
}
```

2. **Update `theme-switcher.js`:**
```javascript
const switcher = new ThemeSwitcher({
  themes: ['light', 'dark', 'auto']
});
```

## Responsive Design

All components include responsive breakpoints:
- Mobile (< 640px)
- Tablet (640px - 1024px)
- Desktop (> 1024px)

## Browser Support

- Chrome/Edge 88+
- Firefox 85+
- Safari 14+
- Mobile browsers (iOS Safari, Chrome Mobile)

**CSS Custom Properties Support:** All modern browsers

## Migration Guide

### Updating Existing Styles

**Before:**
```css
.my-element {
  background: #1a1f3c;
  color: #eee;
  border: 1px solid #445566;
}
```

**After:**
```css
.my-element {
  background: var(--color-bg-from);
  color: var(--color-text);
  border: 1px solid var(--color-border);
}
```

## Performance Considerations

- CSS variables are hardware-accelerated
- Theme switching uses CSS transitions (no layout thrashing)
- localStorage key is minimal (12 bytes)
- JavaScript is only ~4KB

## Accessibility

- All theme toggle buttons have `aria-label` attributes
- Respects system `prefers-color-scheme` setting
- Keyboard navigation supported (Ctrl/Cmd + Shift + T)
- Sufficient color contrast in both themes
- No reliance on color alone for information

## Future Enhancements

Planned improvements:
- [ ] Color picker for custom themes
- [ ] Theme scheduling (auto-switch at specific times)
- [ ] Per-component theme overrides
- [ ] Animation preferences (respects `prefers-reduced-motion`)
- [ ] High contrast mode support
- [ ] Material Design 3 color tokens

## Support

For issues or feature requests related to the theme system, please check:
- `css/themes/variables.css` - Theme definitions
- `Js/theme-switcher.js` - Theme switching logic
- `css/components/*.css` - Component implementations
