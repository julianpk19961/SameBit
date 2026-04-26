# 📝 Changelog - Mejoras de Seguridad SameBit

## [1.0.1] - 2024-XX-XX

### Fixed
- **Sesiones y Headers**: Corregido error "headers already sent" y "ini_set session active"
  - Movida la configuración de sesiones ANTES de `session_start()`
  - Agregada verificación `headers_sent()` antes de enviar headers de seguridad
  - Eliminados `session_start()` redundantes en archivos que ya incluían `setup.php`
  - Soporte mejorado for PHP 7.3+ usando parámetros de `session_start()`

### Changed
- `config/security.php`: Reescrita función `init_security()` para configurar sesiones correctamente
- `config/setup.php`