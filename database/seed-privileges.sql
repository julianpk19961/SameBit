-- =============================================
-- Seed: Modules, Permissions, Profiles & Privileges
-- =============================================

-- Insert Modules
INSERT INTO modules (id, name, slug, description, active) VALUES
('mod-001', 'Llamadas - SameBit', 'llamadas_samebit', 'Módulo de gestión de llamadas y prioridades', 1),
('mod-002', 'Medicina - SameComed', 'medicina_samecomed', 'Módulo de gestión de medicamentos y medicinas', 1);

-- Insert Permissions
INSERT INTO permissions (id, name, slug, description) VALUES
('perm-001', 'Ver', 'view', 'Permiso para ver/consultar datos'),
('perm-002', 'Crear', 'create', 'Permiso para crear nuevos registros'),
('perm-003', 'Editar', 'edit', 'Permiso para editar registros existentes'),
('perm-004', 'Generar Reporte X', 'generate_report_x', 'Permiso para generar reporte de tipo X'),
('perm-005', 'Generar Reporte Y', 'generate_report_y', 'Permiso para generar reporte de tipo Y');

-- Create Module-Permission Matrix (all combinations)
INSERT INTO module_permissions (id, module_id, permission_id) VALUES
-- Llamadas - SameBit
('modp-001', 'mod-001', 'perm-001'),
('modp-002', 'mod-001', 'perm-002'),
('modp-003', 'mod-001', 'perm-003'),
('modp-004', 'mod-001', 'perm-004'),
('modp-005', 'mod-001', 'perm-005'),
-- Medicina - SameComed
('modp-006', 'mod-002', 'perm-001'),
('modp-007', 'mod-002', 'perm-002'),
('modp-008', 'mod-002', 'perm-003'),
('modp-009', 'mod-002', 'perm-004'),
('modp-010', 'mod-002', 'perm-005');

-- Insert Profiles
INSERT INTO profiles (id, name, slug, description, active) VALUES
('prof-001', 'Administrador', 'admin', 'Acceso total a todos los módulos y permisos', 1),
('prof-002', 'Operador', 'operador', 'Acceso limitado - puede ver, crear y editar', 1),
('prof-003', 'Visualizador', 'visualizador', 'Acceso de solo lectura', 1);

-- =============================================
-- ADMIN PROFILE: All permissions = TRUE (1)
-- =============================================
INSERT INTO profile_permissions (id, profile_id, module_permission_id, can_access) VALUES
-- Llamadas - SameBit (Admin)
('pp-001', 'prof-001', 'modp-001', 1),
('pp-002', 'prof-001', 'modp-002', 1),
('pp-003', 'prof-001', 'modp-003', 1),
('pp-004', 'prof-001', 'modp-004', 1),
('pp-005', 'prof-001', 'modp-005', 1),
-- Medicina - SameComed (Admin)
('pp-006', 'prof-001', 'modp-006', 1),
('pp-007', 'prof-001', 'modp-007', 1),
('pp-008', 'prof-001', 'modp-008', 1),
('pp-009', 'prof-001', 'modp-009', 1),
('pp-010', 'prof-001', 'modp-010', 1);

-- =============================================
-- OPERADOR PROFILE: Limited permissions
-- =============================================
INSERT INTO profile_permissions (id, profile_id, module_permission_id, can_access) VALUES
-- Llamadas - SameBit (Operador)
('pp-011', 'prof-002', 'modp-001', 1),  -- view
('pp-012', 'prof-002', 'modp-002', 1),  -- create
('pp-013', 'prof-002', 'modp-003', 1),  -- edit
('pp-014', 'prof-002', 'modp-004', 0),  -- report_x
('pp-015', 'prof-002', 'modp-005', 0),  -- report_y
-- Medicina - SameComed (Operador)
('pp-016', 'prof-002', 'modp-006', 1),  -- view
('pp-017', 'prof-002', 'modp-007', 1),  -- create
('pp-018', 'prof-002', 'modp-008', 1),  -- edit
('pp-019', 'prof-002', 'modp-009', 0),  -- report_x
('pp-020', 'prof-002', 'modp-010', 0);  -- report_y

-- =============================================
-- VISUALIZADOR PROFILE: View only
-- =============================================
INSERT INTO profile_permissions (id, profile_id, module_permission_id, can_access) VALUES
-- Llamadas - SameBit (Visualizador)
('pp-021', 'prof-003', 'modp-001', 1),  -- view
('pp-022', 'prof-003', 'modp-002', 0),  -- create
('pp-023', 'prof-003', 'modp-003', 0),  -- edit
('pp-024', 'prof-003', 'modp-004', 0),  -- report_x
('pp-025', 'prof-003', 'modp-005', 0),  -- report_y
-- Medicina - SameComed (Visualizador)
('pp-026', 'prof-003', 'modp-006', 1),  -- view
('pp-027', 'prof-003', 'modp-007', 0),  -- create
('pp-028', 'prof-003', 'modp-008', 0),  -- edit
('pp-029', 'prof-003', 'modp-009', 0),  -- report_x
('pp-030', 'prof-003', 'modp-010', 0);  -- report_y

-- =============================================
-- DEFAULT ADMIN USER
-- =============================================
INSERT INTO users (id, username, password, first_name, last_name, profile_id, active) VALUES
('user-001', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrador', 'Sistema', 'prof-001', 1);
