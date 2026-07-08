USE nexus_ems_db;

-- =====================================================
-- SCHEMA IMPROVEMENTS
-- NEXUS EMS DATABASE
-- =====================================================

-- =====================================================
-- 1. SYMPOSIUMS
-- =====================================================

ALTER TABLE symposiums

ADD COLUMN symposium_code VARCHAR(30) NOT NULL
AFTER symposium_id,

ADD COLUMN symposium_type ENUM(
'Intra Department',
'Inter Department'
) NOT NULL DEFAULT 'Intra Department'
AFTER title,

ADD COLUMN organizing_department_id INT NULL
AFTER symposium_type,

ADD COLUMN circular_path VARCHAR(255) NULL
AFTER brochure_path,

ADD COLUMN banner_path VARCHAR(255) NULL
AFTER circular_path;

-- Foreign Key

ALTER TABLE symposiums

ADD CONSTRAINT fk_symposium_department

FOREIGN KEY (organizing_department_id)

REFERENCES departments(department_id)

ON DELETE SET NULL

ON UPDATE CASCADE;


-- =====================================================
-- 2. COMPETITIONS
-- =====================================================

ALTER TABLE competitions

ADD COLUMN duration_minutes INT NOT NULL DEFAULT 60
AFTER start_time,

ADD COLUMN submission_deadline DATETIME NULL
AFTER registration_deadline,

ADD COLUMN maximum_score DECIMAL(5,2)
NOT NULL DEFAULT 100.00
AFTER submission_deadline,

ADD COLUMN display_order INT
NOT NULL DEFAULT 1
AFTER maximum_score,

ADD COLUMN is_locked TINYINT(1)
NOT NULL DEFAULT 0
AFTER display_order,

ADD COLUMN is_deleted TINYINT(1)
NOT NULL DEFAULT 0
AFTER is_locked,

ADD COLUMN deleted_at DATETIME NULL
AFTER is_deleted,

ADD COLUMN deleted_by BIGINT NULL
AFTER deleted_at;

ALTER TABLE competitions

ADD CONSTRAINT fk_competition_deleted_by

FOREIGN KEY (deleted_by)

REFERENCES users(user_id)

ON DELETE SET NULL

ON UPDATE CASCADE;


-- =====================================================
-- 3. APPLICATIONS
-- =====================================================

ALTER TABLE applications

ADD COLUMN approval_status ENUM(
'Pending',
'Approved',
'Rejected'
)
NOT NULL DEFAULT 'Pending'
AFTER application_status,

ADD COLUMN approved_by BIGINT NULL
AFTER approval_status,

ADD COLUMN approved_at DATETIME NULL
AFTER approved_by;


ALTER TABLE applications

ADD CONSTRAINT fk_application_approved_by

FOREIGN KEY (approved_by)

REFERENCES users(user_id)

ON DELETE SET NULL

ON UPDATE CASCADE;


-- =====================================================
-- 4. RESULTS
-- =====================================================

ALTER TABLE competition_results

ADD COLUMN published TINYINT(1)
NOT NULL DEFAULT 0
AFTER remarks,

ADD COLUMN published_at DATETIME NULL
AFTER published;


-- =====================================================
-- 5. CERTIFICATES
-- =====================================================

ALTER TABLE certificates

ADD COLUMN verification_url VARCHAR(255) NULL
AFTER qr_code_path;


-- =====================================================
-- 6. NOTIFICATIONS
-- =====================================================

ALTER TABLE notifications

ADD COLUMN sent_at DATETIME NULL
AFTER delivery_status,

ADD COLUMN read_at DATETIME NULL
AFTER sent_at;


-- =====================================================
-- 7. AUDIT LOGS
-- =====================================================

ALTER TABLE audit_logs

ADD COLUMN module_name VARCHAR(100) NULL
AFTER action;