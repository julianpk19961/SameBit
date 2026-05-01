-- =============================================
-- Seed: Modules, Permissions, Profiles & Privileges
-- =============================================

INSERT INTO modules (id, name, slug, description, active) VALUES
('mod-001', 'Llamadas - SameBit', 'llamadas_samebit', 'Gestion de llamadas y prioridades', 1),
('mod-002', 'Medicina - SameComed', 'medicina_samecomed', 'Gestion de medicamentos, medicinas y kardex', 1),
('mod-003', 'Pacientes', 'pacientes', 'Registro y gestion de pacientes', 1),
('mod-004', 'Asist TOP', 'asist_top', 'Seguimiento de tratamiento (Treatment Outcome Profile)', 1),
('mod-005', 'Reportes y Dashboard', 'reportes_dashboard', 'Panel de control y generacion de informes', 1),
('mod-006', 'Administracion Usuarios', 'admin_usuarios', 'Gestion de usuarios, perfiles y permisos', 1);

INSERT INTO permissions (id, name, slug, description) VALUES
('perm-001', 'Ingresar', 'ingresar', 'Acceso al modulo: consultar y visualizar datos'),
('perm-002', 'Editar', 'editar', 'Editar registros existentes'),
('perm-003', 'Guardar', 'guardar', 'Crear y almacenar nuevos registros'),
('perm-004', 'Informes', 'informes', 'Generar reportes y exportar datos');

INSERT INTO module_permissions (id, module_id, permission_id) VALUES
('modp-001', 'mod-001', 'perm-001'),
('modp-002', 'mod-001', 'perm-002'),
('modp-003', 'mod-001', 'perm-003'),
('modp-004', 'mod-001', 'perm-004'),
('modp-005', 'mod-002', 'perm-001'),
('modp-006', 'mod-002', 'perm-002'),
('modp-007', 'mod-002', 'perm-003'),
('modp-008', 'mod-002', 'perm-004'),
('modp-009', 'mod-003', 'perm-001'),
('modp-010', 'mod-003', 'perm-002'),
('modp-011', 'mod-003', 'perm-003'),
('modp-012', 'mod-003', 'perm-004'),
('modp-013', 'mod-004', 'perm-001'),
('modp-014', 'mod-004', 'perm-002'),
('modp-015', 'mod-004', 'perm-003'),
('modp-016', 'mod-004', 'perm-004'),
('modp-017', 'mod-005', 'perm-001'),
('modp-018', 'mod-005', 'perm-002'),
('modp-019', 'mod-005', 'perm-003'),
('modp-020', 'mod-005', 'perm-004'),
('modp-021', 'mod-006', 'perm-001'),
('modp-022', 'mod-006', 'perm-002'),
('modp-023', 'mod-006', 'perm-003'),
('modp-024', 'mod-006', 'perm-004');

INSERT INTO profiles (id, name, slug, description, active) VALUES
('prof-001', 'Administrador', 'admin', 'Acceso total a todos los modulos y permisos', 1),
('prof-002', 'Operador', 'operador', 'Acceso limitado - puede ingresar, editar y guardar', 1),
('prof-003', 'Visualizador', 'visualizador', 'Acceso de solo lectura', 1);

-- =============================================
-- ADMIN: Todo permitido
-- =============================================
INSERT INTO profile_permissions (id, profile_id, module_permission_id, can_access) VALUES
('pp-001', 'prof-001', 'modp-001', 1),
('pp-002', 'prof-001', 'modp-002', 1),
('pp-003', 'prof-001', 'modp-003', 1),
('pp-004', 'prof-001', 'modp-004', 1),
('pp-005', 'prof-001', 'modp-005', 1),
('pp-006', 'prof-001', 'modp-006', 1),
('pp-007', 'prof-001', 'modp-007', 1),
('pp-008', 'prof-001', 'modp-008', 1),
('pp-009', 'prof-001', 'modp-009', 1),
('pp-010', 'prof-001', 'modp-010', 1),
('pp-011', 'prof-001', 'modp-011', 1),
('pp-012', 'prof-001', 'modp-012', 1),
('pp-013', 'prof-001', 'modp-013', 1),
('pp-014', 'prof-001', 'modp-014', 1),
('pp-015', 'prof-001', 'modp-015', 1),
('pp-016', 'prof-001', 'modp-016', 1),
('pp-017', 'prof-001', 'modp-017', 1),
('pp-018', 'prof-001', 'modp-018', 1),
('pp-019', 'prof-001', 'modp-019', 1),
('pp-020', 'prof-001', 'modp-020', 1),
('pp-021', 'prof-001', 'modp-021', 1),
('pp-022', 'prof-001', 'modp-022', 1),
('pp-023', 'prof-001', 'modp-023', 1),
('pp-024', 'prof-001', 'modp-024', 1);

-- =============================================
-- OPERADOR: Ingresar + Editar + Guardar (sin informes)
-- =============================================
INSERT INTO profile_permissions (id, profile_id, module_permission_id, can_access) VALUES
('pp-025', 'prof-002', 'modp-001', 1),
('pp-026', 'prof-002', 'modp-002', 1),
('pp-027', 'prof-002', 'modp-003', 1),
('pp-028', 'prof-002', 'modp-004', 0),
('pp-029', 'prof-002', 'modp-005', 1),
('pp-030', 'prof-002', 'modp-006', 1),
('pp-031', 'prof-002', 'modp-007', 1),
('pp-032', 'prof-002', 'modp-008', 0),
('pp-033', 'prof-002', 'modp-009', 1),
('pp-034', 'prof-002', 'modp-010', 1),
('pp-035', 'prof-002', 'modp-011', 1),
('pp-036', 'prof-002', 'modp-012', 0),
('pp-037', 'prof-002', 'modp-013', 1),
('pp-038', 'prof-002', 'modp-014', 1),
('pp-039', 'prof-002', 'modp-015', 1),
('pp-040', 'prof-002', 'modp-016', 0),
('pp-041', 'prof-002', 'modp-017', 1),
('pp-042', 'prof-002', 'modp-018', 0),
('pp-043', 'prof-002', 'modp-019', 0),
('pp-044', 'prof-002', 'modp-020', 0),
('pp-045', 'prof-002', 'modp-021', 0),
('pp-046', 'prof-002', 'modp-022', 0),
('pp-047', 'prof-002', 'modp-023', 0),
('pp-048', 'prof-002', 'modp-024', 0);

-- =============================================
-- VISUALIZADOR: Solo Ingresar (lectura)
-- =============================================
INSERT INTO profile_permissions (id, profile_id, module_permission_id, can_access) VALUES
('pp-049', 'prof-003', 'modp-001', 1),
('pp-050', 'prof-003', 'modp-002', 0),
('pp-051', 'prof-003', 'modp-003', 0),
('pp-052', 'prof-003', 'modp-004', 0),
('pp-053', 'prof-003', 'modp-005', 1),
('pp-054', 'prof-003', 'modp-006', 0),
('pp-055', 'prof-003', 'modp-007', 0),
('pp-056', 'prof-003', 'modp-008', 0),
('pp-057', 'prof-003', 'modp-009', 1),
('pp-058', 'prof-003', 'modp-010', 0),
('pp-059', 'prof-003', 'modp-011', 0),
('pp-060', 'prof-003', 'modp-012', 0),
('pp-061', 'prof-003', 'modp-013', 1),
('pp-062', 'prof-003', 'modp-014', 0),
('pp-063', 'prof-003', 'modp-015', 0),
('pp-064', 'prof-003', 'modp-016', 0),
('pp-065', 'prof-003', 'modp-017', 1),
('pp-066', 'prof-003', 'modp-018', 0),
('pp-067', 'prof-003', 'modp-019', 0),
('pp-068', 'prof-003', 'modp-020', 0),
('pp-069', 'prof-003', 'modp-021', 0),
('pp-070', 'prof-003', 'modp-022', 0),
('pp-071', 'prof-003', 'modp-023', 0),
('pp-072', 'prof-003', 'modp-024', 0);

INSERT INTO users (id, username, password, first_name, last_name, profile_id, active) VALUES
('user-001', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrador', 'Sistema', 'prof-001', 1);
