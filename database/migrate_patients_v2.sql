-- Migration: extend patients table with demographic and contact columns
-- Run once against an existing database that used the original schema.

ALTER TABLE patients
    -- Change document_type from VARCHAR(50) to INT
    MODIFY COLUMN document_type INT NOT NULL DEFAULT 13,
    -- Add missing demographic columns
    ADD COLUMN gender     VARCHAR(10)  NULL AFTER last_name,
    ADD COLUMN birth_date DATE         NULL AFTER gender,
    ADD COLUMN phone      VARCHAR(30)  NULL AFTER birth_date,
    ADD COLUMN mobile     VARCHAR(30)  NULL AFTER phone,
    ADD COLUMN email      VARCHAR(150) NULL AFTER mobile,
    ADD COLUMN address    VARCHAR(255) NULL AFTER email,
    -- Change range_level from VARCHAR(50) to INT
    MODIFY COLUMN range_level INT NULL,
    -- Add active flag
    ADD COLUMN active TINYINT(1) NOT NULL DEFAULT 1 AFTER range_level;

-- Replace the old UNIQUE on document_number alone with a composite key
-- (allows same number across different document types)
ALTER TABLE patients
    DROP INDEX document_number,
    ADD UNIQUE KEY uq_patient_doc (document_type, document_number);
