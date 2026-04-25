# SameBit - Medical Management System

**SameBit** es una plataforma web completa de gestión médica y hospitalaria diseñada para optimizar el manejo de pacientes, medicamentos y tratamientos en instituciones de salud.

## 🎯 Características Principales

### 👥 Gestión de Pacientes
- **Registro integral**: Captura completa de datos del paciente (nombre, DNI, EPS, IPS)
- **Búsqueda rápida**: Localización instantánea por documento de identificación
- **Clasificación**: Rangos de atención y prioridades (A, B, C, Sisben)
- **Historial**: Seguimiento completo del paciente

### 💊 Gestión de Medicamentos
- **Catálogo dinámico**: Crear, editar y eliminar medicamentos
- **Control de inventario**: Estado activo/inactivo
- **Kardex**: Registro de movimientos y auditoría
- **Observaciones**: Notas asociadas a medicamentos

### 📋 Registro de Tratamientos
- **TOP (Treatment Outcome Profile)**: Perfil de resultados de tratamiento
- **Etapas de tratamiento**: Ingreso, Egreso, En tratamiento, Seguimiento
- **Monitoreo de drogas**: Registro de consumo y cumplimiento
- **Visualización gráfica**: Análisis de progreso con gráficos

### 📊 Reportes y Auditoría
- **Reportes avanzados**: Filtrado por fecha, usuario, paciente
- **Prioridades del día**: Vista de citas y diagnósticos pendientes
- **Exportación**: Generación de reportes en Excel y PDF
- **Trazabilidad**: Registro de todas las actividades

### 🔐 Seguridad
- **Autenticación**: Login seguro basado en sesiones
- **Control de acceso**: Gestión de sesiones por usuario
- **Logout**: Cierre de sesión controlado

## 🚀 Stack Tecnológico

| Componente | Tecnología |
|-----------|-----------|
| **Backend** | PHP 7+ |
| **Base de Datos** | MySQL 8.0 |
| **Frontend** | Bootstrap 5, jQuery 3.5+ |
| **Tablas** | DataTables.js |
| **Reportes** | PHPExcel |
| **Email** | PHPMailer |
| **Infraestructura** | Docker + Docker Compose |

## 📦 Requisitos

- Docker & Docker Compose
- O alternativamente:
  - PHP 7.0+
  - MySQL 8.0
  - Servidor Web (Apache/Nginx)

## ⚡ Instalación Rápida

### Con Docker (Recomendado)

```bash
# Clonar o descargar el proyecto
cd SameBit

# Iniciar contenedores
docker-compose up -d

# Acceder a la aplicación
# App: http://localhost:8081
# MySQL: localhost:3306
```

La base de datos se inicializa automáticamente con schema y seed data.

### Sin Docker

```bash
# 1. Crear base de datos
mysql -u root -p < database/schema.sql

# 2. Cargar datos iniciales (opcional)
mysql -u root -p bit_medical < database/seed.sql

# 3. Configurar config/config.php con credenciales
# 4. Servir con Apache/Nginx en /var/www/html
```

## 🔑 Credenciales por Defecto

**Base de Datos:**
- Host: `db` (Docker) o `localhost`
- Usuario: `usrconect`
- Contraseña: `toor`
- Base de datos: `bit_medical`
- Puerto: `3306`

**Aplicación:**
- Credenciales de usuario: Configuradas en seed.sql
- Verificar tabla `users` en base de datos

## 📁 Estructura de Carpetas

```
SameBit/
├── config/                 # Lógica de negocio y API endpoints
│   ├── config.php         # Conexión a BD
│   ├── setup.php          # Configuración global
│   ├── dniverification.php # Buscar pacientes
│   ├── usepatient.php     # Crear/actualizar paciente
│   ├── medicines.php      # Listar medicamentos
│   ├── medicinestored.php # Guardar medicamento
│   ├── getPriorities.php  # Reportes de prioridades
│   └── ...
├── pages/                  # Vistas HTML/PHP
│   ├── login.php          # Formulario de login
│   ├── dashboard.php      # Panel principal
│   ├── medicines_l.php    # Gestión de medicamentos
│   ├── asisttop.php       # Registro de tratamiento
│   └── generales/         # Headers y footers
├── Js/                     # JavaScript frontend
│   ├── Login.Js
│   ├── dashboard.js
│   ├── medicines.js
│   └── asisttop.Js
├── css/                    # Estilos CSS
│   ├── Login.css
│   ├── main.css
│   └── bootstrap-css/     # Bootstrap framework
├── database/              # Base de datos
│   ├── schema.sql        # Estructura de tablas
│   └── seed.sql          # Datos iniciales
├── img/                   # Imágenes y logos
├── PHPMailer/            # Librería de email
├── PHPExcel/             # Librería de reportes Excel
├── docker-compose.yml    # Configuración Docker
├── Dockerfile            # Imagen Docker
├── index.php             # Punto de entrada
└── README.md             # Este archivo
```

## 🔄 Flujo de Uso Principal

1. **Login** (`pages/login.php`)
   - Usuario y contraseña
   - Validación en backend

2. **Dashboard** (`pages/dashboard.php`)
   - Bienvenida personalizada
   - Opciones: Nuevo registro, Reportes

3. **Registro de Paciente**
   - Buscar paciente por DNI (si existe)
   - Llenar datos: Nombres, apellidos, EPS, IPS, rango
   - Guardar

4. **Gestión de Medicamentos** (`pages/medicines_l.php`)
   - Listar medicamentos activos
   - Crear nuevo medicamento
   - Ver kardex (movimientos)
   - Activar/Inactivar

5. **Reportes** (`config/getPriorities.php`)
   - Filtrar por fecha, usuario, paciente
   - Visualizar citas y diagnósticos
   - Exportar a Excel/PDF

6. **Tratamiento (TOP)** (`pages/asisttop.php`)
   - Registrar consumo de drogas
   - Seguimiento por etapas
   - Ver gráficos de progreso

## 🛠️ Desarrollo

### Estructura de Endpoints

Los endpoints AJAX se encuentran en `config/`:

```javascript
// Ejemplo desde frontend
$.post('../config/dniverification.php', {
    dni: '12345678'
}, function(response) {
    console.log(JSON.parse(response));
});
```

### Patrón de Respuesta JSON

```php
// Desde backend
$response = [
    'status' => 'success',
    'data' => [...],
    'message' => 'Operación realizada'
];
echo json_encode($response);
```

### Validación

**Frontend:**
```javascript
if (!dni || !nombre) {
    Swal.fire('Error', 'Campos vacios', 'error');
    return false;
}
```

**Backend:**
```php
if (empty($_POST['dni'])) {
    die('Error: DNI requerido');
}
```

## 📊 Base de Datos

### Tablas Principales

| Tabla | Descripción |
|-------|------------|
| `users` | Usuarios del sistema |
| `patients` | Información de pacientes |
| `medicines` | Catálogo de medicamentos |
| `kardex` | Movimientos de medicinas |
| `priorities` | Citas y diagnósticos |
| `eps` | Aseguradoras EPS |
| `ips` | Instituciones prestadoras |

Ver `database/schema.sql` para detalles completos.

## 🐛 Troubleshooting

### La app no conecta a BD
```bash
# Verificar que Docker está corriendo
docker ps

# Ver logs
docker-compose logs db

# Revisar config/config.php
```

### Sesión expirada
```php
// config/setup.php debe incluirse en todas las páginas
include './config/setup.php';
// O incluir desde config/
include '../config/setup.php';
```

### Tablas vacías después de docker-compose up
```bash
# Reiniciar e importar seed
docker-compose down -v
docker-compose up -d
```

### Errores de charset UTF-8
Verificar que MySQL usa utf8mb4:
```sql
ALTER DATABASE bit_medical CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## 📈 Performance y Escalabilidad

### Optimizaciones Implementadas
- ✅ Charset UTF-8 en conexión MySQL
- ✅ Prepared statements (parcial)
- ✅ DataTables con server-side processing

### Mejoras Sugeridas
- [ ] Implementar cacheo (Redis)
- [ ] Queries con índices de BD
- [ ] Lazy loading en tablas grandes
- [ ] Minificación de JS/CSS
- [ ] Gzip compression
- [ ] CDN para assets

## 📝 Contribución

1. Crear rama feature: `git checkout -b feature/nueva-funcion`
2. Hacer cambios y commits: `git commit -m "desc"`
3. Push a rama: `git push origin feature/nueva-funcion`
4. Abrir Pull Request

## 📄 Licencia

Este proyecto es propiedad de SAMEIN S.A.S.

## 👥 Autores y Contacto

- **Mantenedor**: SameBit Team
- **Organización**: SAMEIN S.A.S.

## 🔗 Enlaces Útiles

- [Bootstrap 5 Docs](https://getbootstrap.com/docs/5.0/)
- [jQuery Docs](https://jquery.com/)
- [DataTables](https://datatables.net/)
- [PHPMailer](https://github.com/PHPMailer/PHPMailer)
- [PHPExcel](https://github.com/PHPOffice/PHPExcel)
- [MySQL 8.0](https://dev.mysql.com/doc/refman/8.0/en/)
- [Docker Docs](https://docs.docker.com/)

---

**Versión**: 1.0.0  
**Última actualización**: 2026-04-25  
**Estado**: En producción
