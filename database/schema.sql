-- =============================================
-- bit-medical - Database Schema
-- =============================================

CREATE DATABASE IF NOT EXISTS `bit_medical`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `bit_medical`;

SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS profile_permissions;
DROP TABLE IF EXISTS module_permissions;
DROP TABLE IF EXISTS priorities;
DROP TABLE IF EXISTS kardex;
DROP TABLE IF EXISTS medicines;
DROP TABLE IF EXISTS patients;
DROP TABLE IF EXISTS diagnoses;
DROP TABLE IF EXISTS movement_categories;
DROP TABLE IF EXISTS entities;
DROP TABLE IF EXISTS entity_types;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS profiles;
DROP TABLE IF EXISTS permissions;
DROP TABLE IF EXISTS modules;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE entity_types (
    id         VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name       VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE entities (
    id             VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name           VARCHAR(200) NOT NULL,
    nit            VARCHAR(50),
    entity_type_id VARCHAR(36) NOT NULL,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (entity_type_id) REFERENCES entity_types(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE diagnoses (
    id          VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    code        VARCHAR(20) NOT NULL UNIQUE,
    description TEXT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE movement_categories (
    id           VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name         VARCHAR(100) NOT NULL,
    abbreviation VARCHAR(20),
    type         TINYINT NOT NULL COMMENT '1=entry 2=balance_reset 3=exit',
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE medicines (
    id        VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name      VARCHAR(200) NOT NULL,
    reference VARCHAR(100),
    notes     TEXT,
    active    TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Permission and Profile Management System
-- =============================================

CREATE TABLE modules (
    id          VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name        VARCHAR(100) NOT NULL UNIQUE COMMENT 'e.g., Llamadas-SameBit',
    slug        VARCHAR(100) NOT NULL UNIQUE COMMENT 'e.g., llamadas_samebit',
    description TEXT,
    active      TINYINT(1) DEFAULT 1,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE permissions (
    id          VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name        VARCHAR(100) NOT NULL COMMENT 'e.g., Ver, Crear, Editar',
    slug        VARCHAR(100) NOT NULL UNIQUE COMMENT 'e.g., view, create, edit',
    description TEXT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE module_permissions (
    id            VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    module_id     VARCHAR(36) NOT NULL,
    permission_id VARCHAR(36) NOT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_module_permission (module_id, permission_id),
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE profiles (
    id          VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name        VARCHAR(100) NOT NULL UNIQUE COMMENT 'e.g., Admin, Operador, Visualizador',
    slug        VARCHAR(100) NOT NULL UNIQUE COMMENT 'e.g., admin, operador, visualizador',
    description TEXT,
    active      TINYINT(1) DEFAULT 1,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE profile_permissions (
    id                   VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    profile_id           VARCHAR(36) NOT NULL,
    module_permission_id VARCHAR(36) NOT NULL,
    can_access           TINYINT(1) DEFAULT 0 COMMENT '0=deny, 1=allow',
    created_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_profile_module_permission (profile_id, module_permission_id),
    FOREIGN KEY (profile_id) REFERENCES profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (module_permission_id) REFERENCES module_permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE users (
    id               VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    username         VARCHAR(100) NOT NULL UNIQUE,
    password         VARCHAR(32) NOT NULL COMMENT 'MD5 hash',
    first_name       VARCHAR(100) NOT NULL,
    middle_name      VARCHAR(100),
    last_name        VARCHAR(100) NOT NULL,
    second_last_name VARCHAR(100),
    profile_id       VARCHAR(36) NOT NULL DEFAULT (UUID()) COMMENT 'FK to profiles table',
    active           TINYINT(1) DEFAULT 1,
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (profile_id) REFERENCES profiles(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
