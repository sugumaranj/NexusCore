USE nexus_ems_db;

-- =====================================================
-- TABLE : symposiums
-- =====================================================

CREATE TABLE symposiums (

    symposium_id BIGINT AUTO_INCREMENT PRIMARY KEY,

    title VARCHAR(150) NOT NULL,

    academic_year YEAR NOT NULL,

    description TEXT,

    brochure_path VARCHAR(255),

    registration_start DATETIME NOT NULL,

    registration_end DATETIME NOT NULL,

    event_start_date DATE NOT NULL,

    event_end_date DATE NOT NULL,

    status ENUM(
        'Draft',
        'Registration Open',
        'Registration Closed',
        'Completed',
        'Cancelled'
    ) NOT NULL DEFAULT 'Draft',

    created_by BIGINT NOT NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP

);

-- =====================================================
-- TABLE : competitions
-- =====================================================

CREATE TABLE competitions (

    competition_id BIGINT AUTO_INCREMENT PRIMARY KEY,

    symposium_id BIGINT NOT NULL,

    competition_type_id INT NOT NULL,

    venue_id INT NOT NULL,

    competition_code VARCHAR(30) NOT NULL,

    title VARCHAR(150) NOT NULL,

    description TEXT,

    max_participants SMALLINT UNSIGNED NULL,

    max_team_size TINYINT UNSIGNED NOT NULL DEFAULT 1,

    event_date DATE NOT NULL,

    reporting_time TIME NOT NULL,

    start_time TIME NOT NULL,

    end_time TIME NOT NULL,

    registration_deadline DATETIME NOT NULL,

    rules TEXT,

    is_active BOOLEAN NOT NULL DEFAULT TRUE,

    created_by BIGINT NOT NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    last_modified_by BIGINT NULL,

    CONSTRAINT uq_competition_code UNIQUE (competition_code)

);