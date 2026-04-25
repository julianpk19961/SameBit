-- =============================================
-- bit-medical - Database Schema
-- =============================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS priorities;
DROP TABLE IF EXISTS kardex;
DROP TABLE IF EXISTS medicines;
DROP TABLE IF EXISTS patients;
DROP TABLE IF EXISTS diagnoses;
DROP TABLE IF EXISTS movement_categories;
DROP TABLE IF EXISTS entities;
DROP TABLE IF EXISTS entity_types;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE entity_types (
    id         VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name       VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE entities (
    id             VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name           VARCHAR(200) NOT NULL,
    nit            VARCHAR(50),
    entity_type_id VARCHAR(36) NOT NULL,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (entity_type_id) REFERENCES entity_types(id)
);

CREATE TABLE diagnoses (
    id          VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    code        VARCHAR(20) NOT NULL UNIQUE,
    description TEXT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE movement_categories (
    id           VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name         VARCHAR(100) NOT NULL,
    abbreviation VARCHAR(20),
    type         TINYINT NOT NULL COMMENT '1=entry 2=balance_reset 3=exit',
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE medicines (
    id        VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name      VARCHAR(200) NOT NULL,
    reference VARCHAR(100),
    notes     TEXT,
    active    TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE patients (
    id              VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    document_number VARCHAR(50) NOT NULL UNIQUE,
    document_type   VARCHAR(50) NOT NULL,
    first_name      VARCHAR(100) NOT NULL,
    last_name       VARCHAR(100) NOT NULL,
    eps_id          VARCHAR(36),
    ips_id          VARCHAR(36),
    range_level     VARCHAR(50),
    created_by      VARCHAR(200),
    updated_by      VARCHAR(200),
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (eps_id) REFERENCES entities(id) ON DELETE SET NULL,
    FOREIGN KEY (ips_id) REFERENCES entities(id) ON DELETE SET NULL
);

CREATE TABLE kardex (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    medicine_id      VARCHAR(36) NOT NULL,
    patient_id       VARCHAR(36),
    category_id      VARCHAR(36) NOT NULL,
    type             TINYINT,
    initial_quantity INT NOT NULL DEFAULT 0,
    quantity         INT NOT NULL DEFAULT 0,
    final_quantity   INT NOT NULL DEFAULT 0,
    bill             VARCHAR(100),
    movement_date    DATETIME NOT NULL,
    notes            TEXT,
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (medicine_id) REFERENCES medicines(id),
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES movement_categories(id)
);

CREATE TABLE users (
    id               VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    username         VARCHAR(100) NOT NULL UNIQUE,
    password         VARCHAR(32) NOT NULL COMMENT 'MD5 hash',
    first_name       VARCHAR(100) NOT NULL,
    middle_name      VARCHAR(100),
    last_name        VARCHAR(100) NOT NULL,
    second_last_name VARCHAR(100),
    privilege        ENUM('root', 'admin', 'standard') NOT NULL DEFAULT 'standard',
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE priorities (
    id                 VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    patient_id         VARCHAR(36),
    eps_id             VARCHAR(36),
    ips_id             VARCHAR(36),
    diagnosis_id       VARCHAR(36),
    range_level        VARCHAR(50),
    document_number    VARCHAR(50),
    first_name         VARCHAR(100),
    last_name          VARCHAR(100),
    contact_type       VARCHAR(100),
    approved           TINYINT(1) DEFAULT 0,
    sent_by            VARCHAR(200),
    eps_status         VARCHAR(100),
    calls_count        INT DEFAULT 0,
    reception_notes    TEXT,
    outgoing_notes     TEXT,
    annex_nine         VARCHAR(5),
    annex_ten          VARCHAR(5),
    sent_to            VARCHAR(200),
    checkin_date       DATE,
    checkin_time       TIME,
    response_date      DATE,
    response_time      TIME,
    response_day_diff  INT,
    response_hour_diff VARCHAR(10),
    appointment_date   DATE,
    appointment_time   TIME,
    attention_day_diff  INT,
    attention_hour_diff VARCHAR(10),
    created_by         VARCHAR(200),
    updated_by         VARCHAR(200),
    created_at         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at         TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id)   REFERENCES patients(id)  ON DELETE SET NULL,
    FOREIGN KEY (eps_id)       REFERENCES entities(id)  ON DELETE SET NULL,
    FOREIGN KEY (ips_id)       REFERENCES entities(id)  ON DELETE SET NULL,
    FOREIGN KEY (diagnosis_id) REFERENCES diagnoses(id) ON DELETE SET NULL
);
