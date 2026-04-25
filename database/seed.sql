-- =============================================
-- bit-medical - Seed Data
-- =============================================

-- Entity types (fixed IDs so callEps/callips can query by name)
INSERT INTO entity_types (id, name) VALUES
    ('00000000-0000-0000-0000-000000000001', 'EPS'),
    ('00000000-0000-0000-0000-000000000002', 'IPS');

-- Admin user  (username: admin / password: admin)
INSERT INTO users (id, username, password, first_name, last_name, privilege) VALUES
    (UUID(), 'admin', MD5('admin'), 'Admin', 'System', 'admin');
