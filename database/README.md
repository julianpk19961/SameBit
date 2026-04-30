# 📊 Datos de Prueba para SameBit

Este directorio contiene scripts para generar datos de prueba en la base de datos.

## 📋 Archivos

- **schema.sql** - Estructura de la base de datos (tablas)
- **seed.sql** - Datos iniciales (usuarios, entidades, etc.)
- **seed-test.sql** - Datos de prueba (5,000 medicamentos + 10,000 llamadas)
- **generate-test-data.php** - Script PHP para generar datos

## 🚀 Generador de Datos de Prueba

### Opción 1: Usando MySQL desde Terminal (Con Docker)

```bash
# Con Docker
docker exec -i samebit-db mysql -u usrconect -ptoor bit_medical < database/seed-test.sql

# Sin Docker (si MySQL está instalado localmente)
mysql -h localhost -u usrconect -ptoor bit_medical < database/seed-test.sql
```

### Opción 2: Usando el Script PHP

```bash
# Desde dentro del contenedor
docker exec -it samebit-app php database/generate-test-data.php

# O localmente si PHP está instalado
php database/generate-test-data.php
```

### Opción 3: Manualmente desde MySQL Workbench

1. Abre MySQL Workbench
2. Conecta a: `localhost:3306`
   - Usuario: `usrconect`
   - Contraseña: `toor`
   - Base de datos: `bit_medical`
3. Abre el archivo `database/seed-test.sql`
4. Click en "Execute" o presiona Ctrl+Shift+Enter

### Opción 4: Desde phpMyAdmin

1. Accede a `http://localhost:8080` (si phpMyAdmin está disponible)
2. Selecciona la base de datos `bit_medical`
3. Click en pestaña "SQL"
4. Pega el contenido de `database/seed-test.sql`
5. Click en "Execute"

---

## 📊 Datos Generados

El script `seed-test.sql` crea:

| Elemento | Cantidad |
|----------|----------|
| **Medicamentos** | 5,000 |
| **Llamadas (Prioridades)** | 10,000 |
| **Pacientes** | 1,000 |
| **Usuarios** | 9 (1 admin + 8 estándar) |
| **Entidades (EPS/IPS)** | 20 (10 EPS + 10 IPS) |
| **Diagnósticos** | 53 |
| **Categorías de Movimiento** | 7 |

### 🔑 Accesos de Prueba

**Admin:**
- Usuario: `admin`
- Contraseña: `admin`
- Rol: admin

**Médicos/Operadores:**
- doctor1, doctor2
- nurse1, nurse2
- pharmacist1
- operator1, operator2
- coordinator

**Contraseña común:** `pass123`

---

## ⚙️ Detalles Técnicos

### Medicamentos
- Nombres generados aleatoriamente (Acetaminofén, Ibuprofeno, etc.)
- Referencias: REF-000001 a REF-005000
- 90% activos, 10% inactivos

### Llamadas (Priorities)
- 10,000 registros distribuidos aleatoriamente
- Fechas: últimos 90 días
- Diagnósticos: seleccionados aleatoriamente
- Estados de aprobación: aleatorios
- Usuarios creadores: distribuidos entre operadores

### Pacientes
- 1,000 pacientes únicos
- Documentos: 100000000001 a 100000001000
- Asociados a EPS e IPS aleatorios
- Rangos: A, B, C, Sisben

---

## ⚠️ Advertencias

1. **Datos Aleatorios**: Los datos generados son completamente aleatorios y no representan casos reales
2. **Performance**: La inserción de 10,000 registros puede tomar 30-60 segundos
3. **Backup**: Se recomienda hacer backup antes de ejecutar
4. **Reset**: Si necesitas limpiar los datos:
   ```sql
   TRUNCATE TABLE priorities;
   TRUNCATE TABLE kardex;
   TRUNCATE TABLE medicines;
   TRUNCATE TABLE patients;
   DELETE FROM entities WHERE entity_type_id != '00000000-0000-0000-0000-000000000001' 
      AND entity_type_id != '00000000-0000-0000-0000-000000000002';
   DELETE FROM users WHERE username != 'admin';
   ```

---

## 📝 Notas

- Los datos están diseñados para testing y demostración
- Se pueden ejecutar múltiples veces (sin duplicados debido a UUIDs)
- Compatible con Docker Compose
- No afecta el schema base, solo agrega datos

---

## 🆘 Troubleshooting

**Error: "Too many connections"**
```bash
# Reinicia el contenedor
docker-compose restart db
```

**Error: "Access denied for user"**
- Verifica credenciales en config/config.php
- Usuario: `usrconect`, Contraseña: `toor`

**El script tarda mucho**
- Normal para 10,000 registros (30-60 segundos)
- Reduce la cantidad en seed-test.sql si es necesario

**Datos no aparecen**
- Recarga la página del navegador (F5)
- Revisa que no haya errores en SQL
- Consulta los logs: `docker-compose logs`

---

Última actualización: 2026-04-25
