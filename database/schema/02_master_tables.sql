USE nexus_ems_db;

-- =====================================================
-- TABLE : departments
-- =====================================================

CREATE TABLE departments (
    department_id INT AUTO_INCREMENT PRIMARY KEY,

    department_code VARCHAR(10) NOT NULL,
    department_name VARCHAR(150) NOT NULL,
    short_name VARCHAR(30) NOT NULL,

    is_active BOOLEAN NOT NULL DEFAULT TRUE,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT uq_department_code UNIQUE (department_code),
    CONSTRAINT uq_department_name UNIQUE (department_name)
);

-- =====================================================
-- TABLE : venues
-- =====================================================

CREATE TABLE venues (
    venue_id INT AUTO_INCREMENT PRIMARY KEY,

    venue_code VARCHAR(20) NOT NULL,
    venue_name VARCHAR(120) NOT NULL,

    building_name VARCHAR(100) NOT NULL,
    floor VARCHAR(30),

    seating_capacity SMALLINT UNSIGNED NOT NULL,

    is_computer_lab BOOLEAN NOT NULL DEFAULT FALSE,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT uq_venue_code UNIQUE (venue_code)
);

-- =====================================================
-- TABLE : competition_types
-- =====================================================

CREATE TABLE competition_types (

    competition_type_id INT AUTO_INCREMENT PRIMARY KEY,

    type_code VARCHAR(20) NOT NULL,
    type_name VARCHAR(120) NOT NULL,

    category ENUM(
        'Technical',
        'Non-Technical'
    ) NOT NULL,

    is_team_event BOOLEAN NOT NULL DEFAULT FALSE,

    supports_upload BOOLEAN NOT NULL DEFAULT FALSE,

    supports_prelims BOOLEAN NOT NULL DEFAULT FALSE,

    supports_batch BOOLEAN NOT NULL DEFAULT FALSE,

    default_team_size TINYINT UNSIGNED NOT NULL DEFAULT 1,

    is_active BOOLEAN NOT NULL DEFAULT TRUE,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT uq_type_code UNIQUE(type_code),

    CONSTRAINT chk_team_size
        CHECK (default_team_size BETWEEN 1 AND 10)
);

-- =====================================================
-- TABLE : system_settings
-- =====================================================

CREATE TABLE system_settings (

    setting_id INT AUTO_INCREMENT PRIMARY KEY,

    setting_key VARCHAR(100) NOT NULL,

    setting_value TEXT,

    description VARCHAR(255),

    updated_by BIGINT NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT uq_setting_key UNIQUE(setting_key)
);