USE nexus_ems_db;

-- =====================================================
-- TABLE : competition_results
-- =====================================================

CREATE TABLE competition_results (

    result_id BIGINT AUTO_INCREMENT PRIMARY KEY,

    application_id BIGINT NOT NULL,

    total_score DECIMAL(6,2) NOT NULL,

    rank_position INT NULL,

    result_status ENUM(
        'Qualified',
        'Winner',
        'Runner',
        'Participant',
        'Disqualified'
    ) NOT NULL DEFAULT 'Participant',

    evaluated_by BIGINT NOT NULL,

    evaluation_time DATETIME NOT NULL,

    remarks TEXT,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

);

-- =====================================================
-- TABLE : competition_submissions
-- =====================================================

CREATE TABLE competition_submissions (

    submission_id BIGINT AUTO_INCREMENT PRIMARY KEY,

    application_id BIGINT NOT NULL,

    original_file_name VARCHAR(255) NOT NULL,

    stored_file_name VARCHAR(255) NOT NULL,

    file_type VARCHAR(50),

    file_size BIGINT,

    uploaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

);

-- =====================================================
-- TABLE : certificates
-- =====================================================

CREATE TABLE certificates (

    certificate_id BIGINT AUTO_INCREMENT PRIMARY KEY,

    application_id BIGINT NOT NULL,

    certificate_number VARCHAR(50) NOT NULL,

    certificate_type ENUM(
        'Participation',
        'Winner',
        'Runner'
    ) NOT NULL,

    verification_hash CHAR(64) NOT NULL,

    qr_code_path VARCHAR(255),

    generated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT uq_certificate_number
        UNIQUE(certificate_number),

    CONSTRAINT uq_certificate_hash
        UNIQUE(verification_hash)

);

-- =====================================================
-- TABLE : reports_archive
-- =====================================================

CREATE TABLE reports_archive (

    report_id BIGINT AUTO_INCREMENT PRIMARY KEY,

    report_name VARCHAR(150) NOT NULL,

    report_type VARCHAR(100) NOT NULL,

    generated_by BIGINT NOT NULL,

    generated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    file_path VARCHAR(255) NOT NULL

);


-- =====================================================
-- TABLE : notifications
-- =====================================================

CREATE TABLE notifications (

    notification_id BIGINT AUTO_INCREMENT PRIMARY KEY,

    recipient_type ENUM(
        'Student',
        'User'
    ) NOT NULL,

    recipient_id BIGINT NOT NULL,

    notification_title VARCHAR(150) NOT NULL,

    notification_message TEXT NOT NULL,

    delivery_channel ENUM(
        'System',
        'Email',
        'WhatsApp'
    ) NOT NULL DEFAULT 'System',

    delivery_status ENUM(
        'Pending',
        'Sent',
        'Failed'
    ) NOT NULL DEFAULT 'Pending',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

);

-- =====================================================
-- TABLE : audit_logs
-- =====================================================

CREATE TABLE audit_logs (

    audit_log_id BIGINT AUTO_INCREMENT PRIMARY KEY,

    user_id BIGINT NOT NULL,

    action VARCHAR(100) NOT NULL,

    table_name VARCHAR(100),

    record_id BIGINT,

    ip_address VARCHAR(45),

    user_agent TEXT,

    action_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

);