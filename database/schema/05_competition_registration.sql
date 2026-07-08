USE nexus_ems_db;

-- =====================================================
-- TABLE : competition_coordinators
-- =====================================================

CREATE TABLE competition_coordinators (

    coordinator_assignment_id BIGINT AUTO_INCREMENT PRIMARY KEY,

    competition_id BIGINT NOT NULL,

    user_id BIGINT NOT NULL,

    responsibility ENUM(
        'Coordinator',
        'Incharge'
    ) NOT NULL,

    assigned_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

);


-- =====================================================
-- TABLE : applications
-- =====================================================

CREATE TABLE applications (

    application_id BIGINT AUTO_INCREMENT PRIMARY KEY,

    application_no VARCHAR(30) NOT NULL,

    competition_id BIGINT NOT NULL,

    student_id BIGINT NOT NULL,

    application_type ENUM(
        'Individual',
        'Team'
    ) NOT NULL,

    application_status ENUM(
        'Pending',
        'Approved',
        'Rejected',
        'Cancelled'
    ) NOT NULL DEFAULT 'Pending',

    remarks VARCHAR(255),

    applied_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT uq_application_no UNIQUE(application_no)

);

-- =====================================================
-- TABLE : teams
-- =====================================================

CREATE TABLE teams (

    team_id BIGINT AUTO_INCREMENT PRIMARY KEY,

    application_id BIGINT NOT NULL,

    team_name VARCHAR(100) NOT NULL,

    leader_student_id BIGINT NOT NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

);

-- =====================================================
-- TABLE : team_members
-- =====================================================

CREATE TABLE team_members (

    team_member_id BIGINT AUTO_INCREMENT PRIMARY KEY,

    team_id BIGINT NOT NULL,

    student_id BIGINT NOT NULL,

    member_role ENUM(
        'Leader',
        'Member'
    ) NOT NULL DEFAULT 'Member',

    joined_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

);

