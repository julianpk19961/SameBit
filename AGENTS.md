# SameBit - AI Agent Instructions

**SameBit** es una aplicación web médica/hospitalaria para gestión de pacientes, medicamentos y tratamientos. Construida en PHP 7+ con MySQL 8.0, jQuery, Bootstrap y Docker.

## Propósito General

Plataforma web para:
- Registro y gestión de pacientes (DNI, EPS, IPS, rangos)
- Gestión de medicamentos (crear, actualizar, activar/inactivar)
- Registro de diagnósticos y citas médicas
- Seguimiento de tratamientos (TOP - Perfil de Resultados de Tratamiento)
- Reportes y auditoría de actividades

## Stack Tecnológico

- **Backend**: PHP 7+, MySQL 8.0
- **Frontend**: Bootstrap 5, jQuery 3.5+, DataTables
- **Infraestructura**: Docker + Docker Compose
- **Librerías**: PHPExcel (reportes), PHPMailer (emails)

## Estructura del Proyecto

```
/config/        - Conexión, autenticación, lógica de negocio (*.php)
/pages/         - Vistas principales (dashboard, login, pacientes, medicamentos)
/Js/            - JavaScript del frontend (jQuery)
/css/           - Estilos (Bootstrap + custom)
/database/      - Schema SQL y seed data
/img/           - Recursos visuales
/PHPMailer/     - Librería para envío de emails
/PHPExcel/      - Librería para manejo de hojas de cálculo
```

## Características Principales

### 1. Autenticación
- Login seguro en `pages/login.php`
- Sesiones PHP con verificación
- Logout en `config/logout.php`

### 2. Gestión de Pacientes
- Búsqueda por DNI (`config/dniverification.php`)
- Registro de nuevos pacientes (`config/usepatient.php`)
- Datos: tipo documento, EPS, IPS, rango
- Archivo: `pages/dashboard.php` (interfaz principal)

### 3. Gestión de Medicamentos
- CRUD completo (`pages/medicines_l.php`)
- Estado activo/inactivo (`config/medicines.php`)
- Almacenamiento (`config/medicinestored.php`)
- Kardex (registro de movimientos - `config/newkardexmov.php`)

### 4. Reportes y Prioridades
- Filtrado por fecha, usuario, DNI
- Cálculo de prioridades (`config/getPriorities.php`, `config/getTodayPriorities.php`)
- Diagnósticos asociados (`config/calldiagnosis.php`)

### 5. Seguimiento de Tratamiento (TOP)
- Registro de drogas consumidas
- Etapas: Ingreso, Egreso, En tratamiento, Seguimiento
- Visualización de progreso con gráficos
- Archivo: `pages/asisttop.php`

## Convenciones de Código

### PHP
- Iniciar sesión en `config/setup.php` (siempre incluir)
- Namespacing: usar `config/` para lógica, `pages/` para vistas
- Salida JSON para AJAX: `json_encode($array)`
- Encoding UTF-8 en conexión MySQL

### JavaScript
- Usar jQuery (versión 3.5+)
- SweetAlert para diálogos (`Swal.fire()`)
- DataTables para tablas paginadas (`pagination()`)
- LocalStorage para datos de usuario

### Base de Datos
- Tabla `patients`: pacientes
- Tabla `medicines`: medicamentos
- Tabla `kardex`: movimientos de medicinas
- Tabla `priorities`: prioridades/citas
- IDs: UUID (`KP_UUID`), estado (`z_xOne`: 1=activo, 0=inactivo)

## Configuración y Ejecución

### Iniciar Proyecto
```bash
docker-compose up -d
```
- App en: `http://localhost:8081/`
- DB: MySQL en `localhost:3306`
- Usuario: `usrconect` / Contraseña: `toor`

### Variables de Entorno
- `DB_HOST`: localhost o nombre servicio docker (`db`)
- Base de datos: `bit_medical`

### Base de Datos
- Schema: `database/schema.sql`
- Seeds: `database/seed.sql`
- Inicializa automáticamente al levantar docker

## Archivos Clave

| Archivo | Propósito |
|---------|-----------|
| `index.php` | Punto de entrada, redirige a login o dashboard |
| `config/setup.php` | Configuración global, sesiones, rutas |
| `config/config.php` | Conexión MySQL |
| `pages/login.php` | Formulario de autenticación |
| `pages/dashboard.php` | Panel principal post-login |
| `Js/dashboard.js` | Lógica de reportes y filtrados |
| `config/dniverification.php` | Búsqueda de pacientes por DNI |
| `config/usepatient.php` | Crear/actualizar paciente |
| `pages/medicines_l.php` | Listado y gestión de medicamentos |
| `config/medicinestored.php` | Guardar medicina |
| `pages/asisttop.php` | Registro de tratamiento |

## Patrones Comunes

### AJAX POST
```php
// Recibir en config/
$data = $_POST;
// Procesar
// Responder JSON
echo json_encode($response);
```

### Validación Frontend
```javascript
if (emptyFields) {
    Swal.fire({ icon: 'error', title: 'Error', text: '...' });
    return false;
}
```

### DataTables
```javascript
pagination('#table-id', '15', columns, 'Título', [1, 'asc'], true);
```

## Mejoras Sugeridas para Agentes

1. **Seguridad**: Usar prepared statements en queries SQL (prevenir SQL injection)
2. **API REST**: Migrar `config/*.php` a endpoints estructurados
3. **Validación**: Implementar clases validator reutilizables
4. **Testing**: Agregar tests unitarios para funciones críticas
5. **Documentación**: API docs (phpDocumentor o Swagger)
6. **Performance**: Cacheo de consultas frecuentes (Redis)
7. **Code Quality**: PSR-12, linting, análisis estático

## Debug y Troubleshooting

- **Error de conexión DB**: Verificar `docker ps` y conexión en `config/config.php`
- **Sesiones expiradas**: Check `config/setup.php` `session_start()`
- **CORS issues**: Revisar headers AJAX en `Js/`
- **Queries lentas**: Verificar índices en `database/schema.sql`

## Recursos de Ayuda

- Bootstrap: https://getbootstrap.com/docs/5.0/
- jQuery: https://api.jquery.com/
- DataTables: https://datatables.net/
- PHPMailer: https://github.com/PHPMailer/PHPMailer
- MySQL: https://dev.mysql.com/doc/
