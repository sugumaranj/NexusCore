USE nexus_ems_db;

-- =====================================================
-- TABLE : users
-- =====================================================

CREATE TABLE users (

    user_id BIGINT AUTO_INCREMENT PRIMARY KEY,

    employee_id VARCHAR(20) NOT NULL,

    department_id INT NULL,

    full_name VARCHAR(120) NOT NULL,

    email VARCHAR(120) NOT NULL,

    phone VARCHAR(15),

    password_hash VARCHAR(255) NOT NULL,

    role ENUM(
        'Admin',
        'Principal',
        'HOD',
        'Staff'
    ) NOT NULL,

    profile_photo VARCHAR(255),

    signature_path VARCHAR(255),

    account_status ENUM(
        'Active',
        'Inactive',
        'Blocked'
    ) NOT NULL DEFAULT 'Active',

    last_login DATETIME NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT uq_employee_id UNIQUE(employee_id),

    CONSTRAINT uq_user_email UNIQUE(email),

    CONSTRAINT uq_user_phone UNIQUE(phone)

);

-- =====================================================
-- TABLE : students
-- =====================================================

CREATE TABLE students (

    student_id BIGINT AUTO_INCREMENT PRIMARY KEY,

    register_number VARCHAR(25) NOT NULL,

    department_id INT NOT NULL,

    full_name VARCHAR(120) NOT NULL,

    email VARCHAR(120),

    phone VARCHAR(15),

    gender ENUM('Male','Female') NOT NULL,

    password_hash VARCHAR(255) NOT NULL,

    academic_year YEAR NOT NULL,

    section CHAR(1),

    account_status ENUM(
        'Active',
        'Inactive'
    ) NOT NULL DEFAULT 'Active',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT uq_register_number UNIQUE(register_number),

    CONSTRAINT uq_student_email UNIQUE(email),

    CONSTRAINT uq_student_phone UNIQUE(phone)

);