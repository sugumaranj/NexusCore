USE nexus_ems_db;

-- =====================================================
-- USERS
-- =====================================================

ALTER TABLE users
ADD CONSTRAINT fk_users_department
FOREIGN KEY (department_id)
REFERENCES departments(department_id)
ON DELETE SET NULL
ON UPDATE CASCADE;

-- =====================================================
-- STUDENTS
-- =====================================================

ALTER TABLE students
ADD CONSTRAINT fk_students_department
FOREIGN KEY (department_id)
REFERENCES departments(department_id)
ON DELETE RESTRICT
ON UPDATE CASCADE;

-- =====================================================
-- SYMPOSIUMS
-- =====================================================

ALTER TABLE symposiums
ADD CONSTRAINT fk_symposium_created_by
FOREIGN KEY (created_by)
REFERENCES users(user_id)
ON DELETE RESTRICT
ON UPDATE CASCADE;

-- =====================================================
-- COMPETITIONS
-- =====================================================

ALTER TABLE competitions
ADD CONSTRAINT fk_competition_symposium
FOREIGN KEY (symposium_id)
REFERENCES symposiums(symposium_id)
ON DELETE CASCADE
ON UPDATE CASCADE,

ADD CONSTRAINT fk_competition_type
FOREIGN KEY (competition_type_id)
REFERENCES competition_types(competition_type_id)
ON DELETE RESTRICT
ON UPDATE CASCADE,

ADD CONSTRAINT fk_competition_venue
FOREIGN KEY (venue_id)
REFERENCES venues(venue_id)
ON DELETE RESTRICT
ON UPDATE CASCADE,

ADD CONSTRAINT fk_competition_created_by
FOREIGN KEY (created_by)
REFERENCES users(user_id)
ON DELETE RESTRICT
ON UPDATE CASCADE,

ADD CONSTRAINT fk_competition_last_modified_by
FOREIGN KEY (last_modified_by)
REFERENCES users(user_id)
ON DELETE SET NULL
ON UPDATE CASCADE;

-- =====================================================
-- COMPETITION COORDINATORS
-- =====================================================

ALTER TABLE competition_coordinators
ADD CONSTRAINT fk_cc_competition
FOREIGN KEY (competition_id)
REFERENCES competitions(competition_id)
ON DELETE CASCADE
ON UPDATE CASCADE,

ADD CONSTRAINT fk_cc_user
FOREIGN KEY (user_id)
REFERENCES users(user_id)
ON DELETE RESTRICT
ON UPDATE CASCADE;

-- =====================================================
-- APPLICATIONS
-- =====================================================

ALTER TABLE applications
ADD CONSTRAINT fk_application_competition
FOREIGN KEY (competition_id)
REFERENCES competitions(competition_id)
ON DELETE CASCADE
ON UPDATE CASCADE,

ADD CONSTRAINT fk_application_student
FOREIGN KEY (student_id)
REFERENCES students(student_id)
ON DELETE RESTRICT
ON UPDATE CASCADE;

-- =====================================================
-- TEAMS
-- =====================================================

ALTER TABLE teams
ADD CONSTRAINT fk_team_application
FOREIGN KEY (application_id)
REFERENCES applications(application_id)
ON DELETE CASCADE
ON UPDATE CASCADE,

ADD CONSTRAINT fk_team_leader
FOREIGN KEY (leader_student_id)
REFERENCES students(student_id)
ON DELETE RESTRICT
ON UPDATE CASCADE;

-- =====================================================
-- TEAM MEMBERS
-- =====================================================

ALTER TABLE team_members
ADD CONSTRAINT fk_team_member_team
FOREIGN KEY (team_id)
REFERENCES teams(team_id)
ON DELETE CASCADE
ON UPDATE CASCADE,

ADD CONSTRAINT fk_team_member_student
FOREIGN KEY (student_id)
REFERENCES students(student_id)
ON DELETE RESTRICT
ON UPDATE CASCADE;

-- =====================================================
-- RESULTS
-- =====================================================

ALTER TABLE competition_results
ADD CONSTRAINT fk_result_application
FOREIGN KEY (application_id)
REFERENCES applications(application_id)
ON DELETE CASCADE
ON UPDATE CASCADE,

ADD CONSTRAINT fk_result_evaluator
FOREIGN KEY (evaluated_by)
REFERENCES users(user_id)
ON DELETE RESTRICT
ON UPDATE CASCADE;

-- =====================================================
-- SUBMISSIONS
-- =====================================================

ALTER TABLE competition_submissions
ADD CONSTRAINT fk_submission_application
FOREIGN KEY (application_id)
REFERENCES applications(application_id)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- =====================================================
-- CERTIFICATES
-- =====================================================

ALTER TABLE certificates
ADD CONSTRAINT fk_certificate_application
FOREIGN KEY (application_id)
REFERENCES applications(application_id)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- =====================================================
-- REPORTS
-- =====================================================

ALTER TABLE reports_archive
ADD CONSTRAINT fk_report_generated_by
FOREIGN KEY (generated_by)
REFERENCES users(user_id)
ON DELETE RESTRICT
ON UPDATE CASCADE;

-- =====================================================
-- SYSTEM SETTINGS
-- =====================================================

ALTER TABLE system_settings
ADD CONSTRAINT fk_system_updated_by
FOREIGN KEY (updated_by)
REFERENCES users(user_id)
ON DELETE SET NULL
ON UPDATE CASCADE;

-- =====================================================
-- AUDIT LOGS
-- =====================================================

ALTER TABLE audit_logs
ADD CONSTRAINT fk_audit_user
FOREIGN KEY (user_id)
REFERENCES users(user_id)
ON DELETE RESTRICT
ON UPDATE CASCADE;