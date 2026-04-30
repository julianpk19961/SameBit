# Frontend Architecture: Scalable Component System

## Current State
✅ Login has dark/light theme support  
✅ Reusable component system created  
✅ CSS variables centralized  

## Recommended Next Steps

### Phase 1: Component Integration (Recommended for next sprint)

#### 1. Create Component Library Root
```
/Js/components/
├── Button.js          # Button component class
├── Card.js            # Card component class
├── Form.js            # Form wrapper class
├── Modal.js           # Modal component class
└── index.js           # Component exports

/css/components/
├── index.css          # Import all components
└── (existing files)
```

#### 2. Example: Button Component
```javascript
// /Js/components/Button.js
class Button {
  constructor(selector, options = {}) {
    this.element = document.querySelector(selector);
    this.options = {
      variant: 'primary',  // primary, secondary, danger, warning
      size: 'md',          // sm, md, lg
      loading: false,
      disabled: false,
      ...options
    };
    this.init();
  }

  init() {
    this.applyClasses();
    this.attachEvents();
  }

  applyClasses() {
    this.element.classList.add(
      'btn',
      `btn-${this.options.variant}`,
      `btn-${this.options.size}`
    );
    if (this.options.disabled) {
      this.element.disabled = true;
    }
  }

  attachEvents() {
    this.element.addEventListener('click', (e) => {
      if (this.options.onClick) {
        this.options.onClick(e);
      }
    });
  }

  setLoading(state) {
    this.options.loading = state;
    this.element.classList.toggle('loading', state);
  }
}

export default Button;
```

### Phase 2: Application-Wide Theme (Post Phase 1)

#### Apply Components to Dashboard
```
/pages/dashboard.php
├── Use button component for actions
├── Use card component for panels
├── Use form component for inputs
└── Support dark/light mode automatically
```

#### Update Main CSS
```css
/* /css/main.css */
@import url('./themes/variables.css');
@import url('./components/theme-toggle.css');
@import url('./components/index.css');

body {
  background: linear-gradient(135deg, var(--color-bg-from), var(--color-bg-to));
  color: var(--color-text);
  transition: background 0.3s ease;
}
```

### Phase 3: Create Shared Template System

#### Template Inheritance Pattern
```
/pages/layouts/
├── base.php           # Base HTML structure
├── authenticated.php  # Authenticated user layout
└── public.php         # Public pages layout

/pages/components/
├── header.php         # Navigation header
├── sidebar.php        # Left sidebar
├── footer.php         # Footer
├── modals/
│   ├── confirm.php    # Confirmation modal
│   └── alert.php      # Alert modal
└── forms/
    ├── patient.php    # Patient form component
    ├── medicine.php   # Medicine form component
    └── search.php     # Search form component
```

#### Example Base Template
```php
<!-- /pages/layouts/base.php -->
<!DOCTYPE html>
<html lang="<?= $language ?>">
<head>
  <?php include '../pages/components/head.php'; ?>
</head>
<body data-theme="light">
  <?php include '../pages/components/header.php'; ?>
  
  <main class="container">
    <?php include $content_file; ?>
  </main>

  <?php include '../pages/components/footer.php'; ?>
</body>
</html>
```

### Phase 4: Modular JavaScript Structure

#### Recommended Organization
```
/Js/
├── modules/
│   ├── api.js         # API calls (centralized)
│   ├── storage.js     # localStorage helper
│   ├── validation.js  # Form validation
│   ├── notifications.js # Alert/toast system
│   └── auth.js        # Authentication logic
├── components/
│   ├── Button.js
│   ├── Card.js
│   ├── Modal.js
│   └── Table.js
├── pages/
│   ├── login.js       # Login page logic
│   ├── dashboard.js   # Dashboard logic
│   ├── medicines.js   # Medicines page logic
│   └── calls.js       # Calls page logic
├── theme-switcher.js
├── security.js
└── index.js           # Entry point
```

#### Module Pattern Example
```javascript
// /Js/modules/api.js
class API {
  static async post(endpoint, data) {
    try {
      const response = await fetch(endpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': window.CSRF_TOKEN
        },
        body: JSON.stringify(data)
      });
      return await response.json();
    } catch (error) {
      console.error('API Error:', error);
      throw error;
    }
  }

  static async get(endpoint) {
    try {
      const response = await fetch(endpoint);
      return await response.json();
    } catch (error) {
      console.error('API Error:', error);
      throw error;
    }
  }
}

export default API;
```

### Phase 5: State Management (Advanced)

Consider implementing a simple state management pattern:
```javascript
// /Js/store.js
class Store {
  constructor(initialState = {}) {
    this.state = initialState;
    this.observers = [];
  }

  subscribe(observer) {
    this.observers.push(observer);
  }

  setState(newState) {
    this.state = { ...this.state, ...newState };
    this.observers.forEach(obs => obs(this.state));
  }

  getState() {
    return { ...this.state };
  }
}
```

## File Organization Summary

```
SameBit/
├── config/
│   ├── setup.php
│   ├── config.php
│   ├── connection.php
│   ├── modules/          # [NEW] Business logic
│   │   ├── PatientManager.php
│   │   ├── MedicineManager.php
│   │   ├── CallManager.php
│   │   └── ReportGenerator.php
│   └── ... (existing)
├── Js/
│   ├── components/       # [NEW] JS Component classes
│   │   ├── Button.js
│   │   ├── Card.js
│   │   ├── Modal.js
│   │   └── Table.js
│   ├── modules/          # [NEW] Shared utilities
│   │   ├── api.js
│   │   ├── validation.js
│   │   ├── notifications.js
│   │   └── store.js
│   ├── pages/            # [NEW] Page-specific logic
│   │   ├── login.js
│   │   ├── dashboard.js
│   │   ├── medicines.js
│   │   └── calls.js
│   ├── theme-switcher.js
│   ├── security.js
│   └── ... (existing)
├── css/
│   ├── themes/
│   │   └── variables.css
│   ├── components/
│   │   ├── buttons.css
│   │   ├── forms.css
│   │   ├── cards.css
│   │   └── theme-toggle.css
│   ├── pages/           # [NEW] Page-specific styles
│   │   ├── login.css    # Already exists
│   │   ├── dashboard.css
│   │   ├── medicines.css
│   │   └── calls.css
│   └── ... (existing)
├── pages/
│   ├── layouts/         # [NEW] Template layouts
│   │   ├── base.php
│   │   ├── authenticated.php
│   │   └── public.php
│   ├── components/      # [NEW] Reusable PHP components
│   │   ├── header.php
│   │   ├── sidebar.php
│   │   ├── footer.php
│   │   └── ... (modals, forms)
│   ├── login.php
│   ├── dashboard.php
│   ├── medicines_l.php
│   └── ... (existing)
├── database/
│   ├── schema.sql
│   ├── seeds.sql
│   └── README.md
└── docs/
    ├── THEME_SYSTEM.md
    ├── ARCHITECTURE.md  # [NEW] This document
    ├── API.md           # [NEW] API documentation
    └── COMPONENTS.md    # [NEW] Component guide
```

## Benefits of This Structure

✅ **Scalability** - Easy to add new components and pages  
✅ **Maintainability** - Clear separation of concerns  
✅ **Reusability** - Components can be used across pages  
✅ **Testing** - Easier to unit test isolated components  
✅ **Performance** - CSS/JS can be lazy-loaded per page  
✅ **Team Collaboration** - Clear file organization  
✅ **Documentation** - Self-documenting structure  

## Migration Path

1. **Week 1**: Create component library (Phase 1)
2. **Week 2**: Integrate components into login & dashboard (Phase 2)
3. **Week 3**: Create template system (Phase 3)
4. **Week 4**: Refactor existing pages to use templates (Phase 3)
5. **Week 5-6**: Modularize JavaScript (Phase 4)
6. **Future**: Add state management as needed (Phase 5)

## Quick Wins (Start Now!)

### 1. Create Components Index
```javascript
// /Js/components/index.js
export { default as Button } from './Button.js';
export { default as Card } from './Card.js';
export { default as Modal } from './Modal.js';
```

### 2. Start Using in Login
```javascript
// /pages/login.php
<script type="module">
  import { Button } from '../Js/components/index.js';
  
  const loginBtn = new Button('#login button[type="submit"]', {
    variant: 'primary',
    onClick: () => console.log('Logging in...')
  });
</script>
```

### 3. Create API Module
Replace all $.ajax calls with centralized API module (better error handling, DRY principle)

## Next Actions

1. Review this architecture with your team
2. Create components incrementally
3. Apply theme to other pages
4. Document API endpoints (Swagger/OpenAPI)
5. Add unit tests for components
6. Consider package manager (npm) for dependencies

## Resources

- CSS Patterns: https://cube.fyi/
- Component Architecture: https://www.11ty.dev/
- Design System: https://www.designsystems.com/
- State Management: https://redux.js.org/understanding/thinking-in-redux
