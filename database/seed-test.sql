-- =============================================
-- SameBit - Test Data Generation Script
-- 5,000 Medicamentos + 10,000 Llamadas
-- =============================================

-- Desactivar chequeos de claves foráneas temporalmente
SET FOREIGN_KEY_CHECKS = 0;

-- =============================================
-- 1. CREAR ENTIDADES ADICIONALES (EPS, IPS)
-- =============================================

-- EPS (Aseguradoras)
INSERT INTO entities (name, nit, entity_type_id) VALUES
('EPS Sura', 'EPS001', '00000000-0000-0000-0000-000000000001'),
('EPS Compensar', 'EPS002', '00000000-0000-0000-0000-000000000001'),
('EPS Salud Total', 'EPS003', '00000000-0000-0000-0000-000000000001'),
('EPS Coomeva', 'EPS004', '00000000-0000-0000-0000-000000000001'),
('EPS Famisanar', 'EPS005', '00000000-0000-0000-0000-000000000001'),
('EPS Sanitas', 'EPS006', '00000000-0000-0000-0000-000000000001'),
('EPS AXA Colpatria', 'EPS007', '00000000-0000-0000-0000-000000000001'),
('EPS Médica SOS', 'EPS008', '00000000-0000-0000-0000-000000000001'),
('EPS Alianza', 'EPS009', '00000000-0000-0000-0000-000000000001'),
('EPS Mapfre', 'EPS010', '00000000-0000-0000-0000-000000000001');

-- IPS (Instituciones Prestadoras)
INSERT INTO entities (name, nit, entity_type_id) VALUES
('Hospital San José', 'IPS001', '00000000-0000-0000-0000-000000000002'),
('Clínica Reina Sofía', 'IPS002', '00000000-0000-0000-0000-000000000002'),
('Centro Médico Avanzado', 'IPS003', '00000000-0000-0000-0000-000000000002'),
('Hospital Universitario', 'IPS004', '00000000-0000-0000-0000-000000000002'),
('Clínica La Paz', 'IPS005', '00000000-0000-0000-0000-000000000002'),
('Centro de Salud Integral', 'IPS006', '00000000-0000-0000-0000-000000000002'),
('Hospital Metropolitano', 'IPS007', '00000000-0000-0000-0000-000000000002'),
('Clínica Especializada', 'IPS008', '00000000-0000-0000-0000-000000000002'),
('Centro Médico del Norte', 'IPS009', '00000000-0000-0000-0000-000000000002'),
('Hospital Distrital', 'IPS010', '00000000-0000-0000-0000-000000000002');

-- =============================================
-- 2. CREAR DIAGNÓSTICOS
-- =============================================

INSERT INTO diagnoses (code, description) VALUES
('A00', 'Cólera'),
('A01', 'Fiebre tifoidea'),
('A02', 'Otras salmonelosis'),
('A03', 'Disentería bacilar'),
('A04', 'Infección por E. coli'),
('B00', 'Infección por herpes simplex'),
('B01', 'Varicela'),
('B02', 'Herpes zóster'),
('C00', 'Carcinoma de labio'),
('C01', 'Carcinoma de lengua'),
('C80', 'Neoplasia maligna sin especificar'),
('D10', 'Lipoma'),
('D30', 'Neoplasias benignas de vejiga'),
('E00', 'Trastornos por deficiencia de yodo'),
('E10', 'Diabetes mellitus tipo 1'),
('E11', 'Diabetes mellitus tipo 2'),
('E66', 'Obesidad'),
('F01', 'Demencia vascular'),
('F10', 'Trastornos mentales por alcohol'),
('F20', 'Esquizofrenia'),
('F31', 'Trastorno afectivo bipolar'),
('F32', 'Episodio depresivo'),
('F41', 'Trastornos de ansiedad'),
('G10', 'Enfermedad de Huntington'),
('G20', 'Enfermedad de Parkinson'),
('G30', 'Enfermedad de Alzheimer'),
('H26', 'Cataratas'),
('I10', 'Hipertensión esencial'),
('I21', 'Infarto agudo de miocardio'),
('I25', 'Cardiopatía isquémica crónica'),
('I50', 'Insuficiencia cardíaca'),
('J00', 'Rinitis aguda'),
('J20', 'Bronquitis aguda'),
('J45', 'Asma'),
('K21', 'Reflujo gastroesofágico'),
('K29', 'Gastritis'),
('K35', 'Apendicitis aguda'),
('K80', 'Cálculos biliares'),
('L20', 'Dermatitis atópica'),
('M19', 'Artrosis sin especificar'),
('M79', 'Otros trastornos de los tejidos blandos'),
('N18', 'Enfermedad renal crónica'),
('N39', 'Otros trastornos del sistema urinario'),
('O00', 'Embarazo ectópico'),
('P00', 'Feto y recién nacido afectados por factores maternos'),
('R06', 'Anomalía de la respiración'),
('R51', 'Cefalea'),
('Z12', 'Examen de cribado para malignidad'),
('Z20', 'Contacto con enfermedad transmisible');

-- =============================================
-- 3. CREAR CATEGORÍAS DE MOVIMIENTO
-- =============================================

INSERT INTO movement_categories (name, abbreviation, type) VALUES
('Entrada de medicamentos', 'ENT', 1),
('Salida de medicamentos', 'SAL', 3),
('Ajuste de inventario', 'AJU', 2),
('Devolución', 'DEV', 1),
('Consumo', 'CON', 3),
('Pérdida', 'PER', 3),
('Transferencia', 'TRA', 2);

-- =============================================
-- 4. CREAR USUARIOS ADICIONALES
-- =============================================

INSERT INTO users (username, password, first_name, last_name, profile_id) VALUES
('doctor1', MD5('pass123'), 'Juan', 'Pérez', 'prof-002'),
('doctor2', MD5('pass123'), 'María', 'González', 'prof-002'),
('nurse1', MD5('pass123'), 'Carlos', 'López', 'prof-002'),
('nurse2', MD5('pass123'), 'Ana', 'Martínez', 'prof-002'),
('farmacist1', MD5('pass123'), 'Pedro', 'Rodríguez', 'prof-002'),
('operator1', MD5('pass123'), 'Luis', 'García', 'prof-002'),
('operator2', MD5('pass123'), 'Patricia', 'Sánchez', 'prof-002'),
('coordinator', MD5('pass123'), 'Fernando', 'Díaz', 'prof-001');

-- =============================================
-- 5. CREAR PACIENTES (1000 pacientes básicos)
-- =============================================

-- Usar procedimiento almacenado para insertar pacientes
DELIMITER //

DROP PROCEDURE IF EXISTS insert_test_patients//

CREATE PROCEDURE insert_test_patients()
BEGIN
    DECLARE i INT DEFAULT 1;
    DECLARE eps_id VARCHAR(36);
    DECLARE ips_id VARCHAR(36);
    DECLARE doc_number VARCHAR(50);
    
    WHILE i <= 1000 DO
        SET doc_number = CONCAT('100000', LPAD(i, 6, '0'));
        SET eps_id = (SELECT id FROM entities WHERE entity_type_id = '00000000-0000-0000-0000-000000000001' ORDER BY RAND() LIMIT 1);
        SET ips_id = (SELECT id FROM entities WHERE entity_type_id = '00000000-0000-0000-0000-000000000002' ORDER BY RAND() LIMIT 1);
        
        INSERT INTO patients (document_number, document_type, first_name, last_name, eps_id, ips_id, range_level, created_by)
        VALUES (
            doc_number,
            ELT(FLOOR(RAND() * 8) + 1, '11', '12', '13', '21', '22', '31', '41', '42'),
            ELT(FLOOR(RAND() * 5) + 1, 'Juan', 'María', 'Carlos', 'Ana', 'Pedro'),
            ELT(FLOOR(RAND() * 5) + 1, 'García', 'López', 'Martínez', 'Rodríguez', 'Sánchez'),
            eps_id,
            ips_id,
            ELT(FLOOR(RAND() * 4) + 1, 'A', 'B', 'C', 'Sisben'),
            'admin'
        );
        
        SET i = i + 1;
    END WHILE;
END//

DELIMITER ;

CALL insert_test_patients();

-- =============================================
-- 6. INSERTAR 5,000 MEDICAMENTOS
-- =============================================

DELIMITER //

DROP PROCEDURE IF EXISTS insert_test_medicines//

CREATE PROCEDURE insert_test_medicines()
BEGIN
    DECLARE i INT DEFAULT 1;
    DECLARE med_name VARCHAR(200);
    DECLARE med_ref VARCHAR(100);
    
    WHILE i <= 5000 DO
        SET med_name = CONCAT(
            ELT(FLOOR(RAND() * 10) + 1, 'Acetaminofén', 'Ibuprofeno', 'Amoxicilina', 'Dipirona', 'Metformina', 'Lisinopril', 'Omeprazol', 'Loratadina', 'Sertraline', 'Atorvastatina'),
            ' ',
            FLOOR(RAND() * 900 + 100),
            'mg'
        );
        SET med_ref = CONCAT('REF-', LPAD(i, 6, '0'));
        
        INSERT INTO medicines (name, reference, notes, active)
        VALUES (
            med_name,
            med_ref,
            CONCAT('Medicamento de prueba #', i),
            IF(RAND() > 0.1, 1, 0)
        );
        
        SET i = i + 1;
    END WHILE;
END//

DELIMITER ;

CALL insert_test_medicines();

-- =============================================
-- 7. INSERTAR 10,000 LLAMADAS (PRIORITIES)
-- =============================================

DELIMITER //

DROP PROCEDURE IF EXISTS insert_test_priorities//

CREATE PROCEDURE insert_test_priorities()
BEGIN
    DECLARE i INT DEFAULT 1;
    DECLARE patient_id VARCHAR(36);
    DECLARE eps_id VARCHAR(36);
    DECLARE ips_id VARCHAR(36);
    DECLARE diagnosis_id VARCHAR(36);
    DECLARE created_by_user VARCHAR(100);
    DECLARE checkin_date DATE;
    DECLARE response_date DATE;
    DECLARE appointment_date DATE;
    
    WHILE i <= 10000 DO
        -- Obtener datos aleatorios
        SET patient_id = (SELECT id FROM patients ORDER BY RAND() LIMIT 1);
        SET eps_id = (SELECT eps_id FROM patients WHERE id = patient_id);
        SET ips_id = (SELECT ips_id FROM patients WHERE id = patient_id);
        SET diagnosis_id = (SELECT id FROM diagnoses ORDER BY RAND() LIMIT 1);
        SET created_by_user = ELT(FLOOR(RAND() * 5) + 1, 'admin', 'doctor1', 'nurse1', 'operator1', 'coordinator');
        
        -- Generar fechas
        SET checkin_date = DATE_SUB(CURDATE(), INTERVAL FLOOR(RAND() * 90) DAY);
        SET response_date = DATE_ADD(checkin_date, INTERVAL FLOOR(RAND() * 7) DAY);
        SET appointment_date = DATE_ADD(response_date, INTERVAL FLOOR(RAND() * 30) DAY);
        
        INSERT INTO priorities (
            patient_id,
            eps_id,
            ips_id,
            diagnosis_id,
            range_level,
            contact_type,
            approved,
            sent_by,
            eps_status,
            calls_count,
            reception_notes,
            annex_nine,
            annex_ten,
            sent_to,
            checkin_date,
            checkin_time,
            response_date,
            response_time,
            appointment_date,
            appointment_time,
            created_by,
            updated_by
        ) VALUES (
            patient_id,
            eps_id,
            ips_id,
            diagnosis_id,
            ELT(FLOOR(RAND() * 4) + 1, 'A', 'B', 'C', 'Sisben'),
            ELT(FLOOR(RAND() * 2) + 1, 'Llamada', 'Correo'),
            FLOOR(RAND() * 2),
            created_by_user,
            ELT(FLOOR(RAND() * 3) + 1, 'Activo', 'Inactivo', 'Suspendido'),
            FLOOR(RAND() * 10 + 1),
            CONCAT('Nota de llamada de prueba #', i),
            IF(RAND() > 0.5, '1', '0'),
            IF(RAND() > 0.5, '1', '0'),
            ELT(FLOOR(RAND() * 3) + 1, 'Especialista A', 'Especialista B', 'Centro de Salud'),
            checkin_date,
            SEC_TO_TIME(FLOOR(RAND() * 86400)),
            response_date,
            SEC_TO_TIME(FLOOR(RAND() * 86400)),
            appointment_date,
            SEC_TO_TIME(FLOOR(RAND() * 86400)),
            created_by_user,
            created_by_user
        );
        
        SET i = i + 1;
        
        -- Mostrar progreso cada 1000
        IF i % 1000 = 0 THEN
            SELECT CONCAT('Insertadas ', i, ' llamadas') AS progreso;
        END IF;
    END WHILE;
END//

DELIMITER ;

CALL insert_test_priorities();

-- =============================================
-- 8. LIMPIAR PROCEDIMIENTOS
-- =============================================

DROP PROCEDURE IF EXISTS insert_test_patients;
DROP PROCEDURE IF EXISTS insert_test_medicines;
DROP PROCEDURE IF EXISTS insert_test_priorities;

-- Reactivar chequeos de claves foráneas
SET FOREIGN_KEY_CHECKS = 1;

-- =============================================
-- RESUMEN DE DATOS INSERTADOS
-- =============================================

SELECT 
    (SELECT COUNT(*) FROM medicines) AS 'Total Medicamentos',
    (SELECT COUNT(*) FROM priorities) AS 'Total Llamadas',
    (SELECT COUNT(*) FROM patients) AS 'Total Pacientes',
    (SELECT COUNT(*) FROM users) AS 'Total Usuarios',
    (SELECT COUNT(*) FROM entities) AS 'Total Entidades (EPS/IPS)',
    (SELECT COUNT(*) FROM diagnoses) AS 'Total Diagnósticos';
