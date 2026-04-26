# 📋 Guía de Migración de Seguridad - SameBit

Esta guía describe el proceso para migrar el sistema de autenticación de MD5 a Bcrypt y aplicar las mejoras de seguridad críticas.

---

## 🔐 **FASE 1.1: Migración de Contraseñas**

### **Resumen de Cambios**

| Archivo | Cambio | Propósito |
|---------|--------|-----------|
| `config/security.php` | Nuevo | Funciones de seguridad centralizadas |
| `config/conection.php` | Modificado | Login con prepared statements y password_verify |
| `config/setup.php` | Modificado | Incluir security.php y inicializar seguridad |
| `config/update_password.php` | Nuevo | Actualización segura de contraseñas |
| `database/migrate_passwords.php` | Nuevo | Script de migración MD5 → Bcrypt |

---

## 🚀 **Instrucciones de Migración**

### **Paso 1: Preparación**

1. **Backup de la base de datos**
   ```bash
   mysqldump -u usrconect -p bit_medical > backup_before_security_migration.sql
   ```

2. **Verificar PHP versión**
   ```bash
   php -v
   # Debe ser PHP 7.0+ para password_hash()
   ```

3. **Crear directorio de logs**
   ```bash
   mkdir logs
   chmod 755 logs
   ```

### **Paso 2: Ejecutar Migración**

1. **Acceder al script de migración**
   ```
   http://localhost:8081/database/migrate_passwords.php
   ```
   
   O desde línea de comandos:
   ```bash
   php database/migrate_passwords.php
   ```

2. **Verificar resultado**
   - El script mostrará un resumen de usuarios migrados
   - Verificar que no haya errores
   - Todos los usuarios deben estar migrados o ya migrados

3. **Eliminar script de migración** (importante por seguridad)
   ```bash
   rm database/migrate_passwords.php
   ```

### **Paso 3: Verificación**

1. **Probar login con usuario existente**
   - Las contraseñas antiguas (MD5) seguirán funcionando durante la migración
   - El sistema detecta automáticamente si es MD5 o Bcrypt

2. **Verificar sesión**
   - Después de login, verificar que `$_SESSION['id']` y `$_SESSION['usuario']` estén definidos
   - Verificar que se haya regenerado el ID de sesión

3. **Probar logout**
   - Verificar que destruye la sesión correctamente

### **Paso 4: Actualizar Contraseñas (Opcional pero recomendado)**

Para mayor seguridad, forzar a todos los usuarios a actualizar sus contraseñas:

1. **Crear lista de usuarios**
   ```sql
   SELECT id, username, email FROM users;
   ```

2. **Notificar a usuarios** sobre la actualización de seguridad

3. **Proporcionar interfaz** para actualizar contraseña (usar `config/update_password.php`)

---

## 🔧 **Uso de Funciones de Seguridad**

### **En Archivos PHP**

```php
// Incluir al inicio (ya está en setup.php)
require_once 'config/setup.php';

// Verificar autenticación
require_auth();

// Verificar admin
require_admin();

// Generar token CSRF para formularios
echo csrf_field();

// Validar token CSRF recibido
if (validate_csrf_token($_POST['csrf_token'])) {
    // Procesar formulario
    regenerate_csrf_token(); // Importante: regenerar después de usar
}

// Sanitizar datos
$nombre_limpio = sanitize_string($_POST['nombre']);
$edad_limpia = sanitize_int($_POST['edad']);

// Hash de contraseña (para nuevos usuarios)
$hash = hash_password($password);

// Verificar contraseña
if (verify_password($password, $stored_hash)) {
    // Login exitoso
}

// Log de seguridad
security_log('USER_LOGIN', 'Usuario inició sesión', $_SESSION['usuario']);
```

### **En Formularios HTML**

```html
<form method="POST" action="procesar.php">
    <?php echo csrf_field(); ?>
    
    <input type="text" name="usuario" required>
    <input type="password" name="password" required>
    
    <button type="submit">Enviar</button>
</form>
```

---

## 🛡️ **Medidas de Seguridad Implementadas**

### **1. Contraseñas**
- ✅ Hash con Bcrypt (costo 12)
- ✅ Soporte para MD5 durante migración
- ✅ Validación de complejidad (8+ caracteres, mayúscula, número)

### **2. Sesiones**
- ✅ Cookies HttpOnly (previene robo por XSS)
- ✅ Cookies Secure (solo HTTPS)
- ✅ SameSite Strict (previene CSRF)
- ✅ Regeneración de ID después de login
- ✅ Timeout de inactividad (30 minutos)

### **3. SQL Injection**
- ✅ Prepared statements en login
- ✅ Validación de tipos de datos

### **4. CSRF**
- ✅ Tokens CSRF en formularios
- ✅ Validación en cada petición POST
- ✅ Regeneración después de usar

### **5. XSS**
- ✅ Sanitización de datos con htmlspecialchars
- ✅ Headers de seguridad (X-XSS-Protection)

### **6. Headers de Seguridad**
- ✅ X-Content-Type-Options: nosniff
- ✅ X-Frame-Options: DENY
- ✅ X-XSS-Protection: 1; mode=block
- ✅ Content-Security-Policy básico

### **7. Logs**
- ✅ Registro de actividades de seguridad
- ✅ Logs en archivo JSON

---

## 📊 **Requisitos de Contraseñas**

Las nuevas contraseñas deben cumplir:
- Mínimo 8 caracteres
- Al menos una letra mayúscula (A-Z)
- Al menos un número (0-9)
- Máximo 128 caracteres

---

## 🔄 **Próximos Pasos (Fases Futuras)**

### **Fase 1.2: Prepared Statements**
- Migrar todos los queries SQL a prepared statements
- Archivos críticos: `dniverification.php`, `usepatient.php`, `medicines.php`, etc.

### **Fase 1.3: Protección CSRF**
- Agregar tokens CSRF a todos los formularios
- Validar en todos los endpoints POST

### **Fase 2: Seguridad de Sesiones**
- Mejorar gestión de sesiones
- Implementar logout seguro

### **Fase 3: Validación**
- Clase Validator para validación de datos
- Sanitización de todas las entradas

---

## 🆘 **Solución de Problemas**

### **Error: "No se pudo conectar a la base de datos"**
- Verificar que Docker esté corriendo
- Verificar credenciales en `config/config.php`

### **Error: "Token CSRF inválido"**
- El token expira después de usarse
- Asegurarse de llamar a `regenerate_csrf_token()` después de validar
- Verificar que la sesión esté iniciada

### **Error: "Sesión expirada"**
- El timeout es de 30 minutos de inactividad
- El usuario debe volver a loguearse

### **Login no funciona después de migración**
- Verificar que el script de migración se ejecutó correctamente
- Verificar que las contraseñas se migraron a Bcrypt
- Revisar logs en `logs/security.log`

---

## 📝 **Checklist de Migración**

- [ ] Backup de base de datos completado
- [ ] Directorio `logs/` creado con permisos correctos
- [ ] Archivos de seguridad subidos al servidor
- [ ] Script de migración ejecutado exitosamente
- [ ] Login probado con usuario existente
- [ ] Sesión verificada (regeneración de ID)
- [ ] Logout probado
- [ ] Script de migración eliminado del servidor
- [ ] Logs revisados (sin errores)
- [ ] Usuarios notificados (si aplica)

---

## 📞 **Soporte**

Para problemas o preguntas sobre esta migración:
1. Revisar logs en `logs/security.log` y `logs/php_errors.log`
2. Verificar configuración en `config/config.php`
3. Consultar documentación en `AGENTS.md`

---

**Fecha de última actualización:** 2024
**Versión:** 1.0