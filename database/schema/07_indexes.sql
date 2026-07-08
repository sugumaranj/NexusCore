USE nexus_ems_db;

-- =====================================================
-- USERS
-- =====================================================

CREATE INDEX idx_users_department
ON users(department_id);

CREATE INDEX idx_users_role
ON users(role);

-- =====================================================
-- STUDENTS
-- =====================================================

CREATE INDEX idx_students_department
ON students(department_id);

CREATE INDEX idx_students_year
ON students(academic_year);

-- =====================================================
-- SYMPOSIUMS
-- =====================================================

CREATE INDEX idx_symposium_year
ON symposiums(academic_year);

CREATE INDEX idx_symposium_status
ON symposiums(status);

-- =====================================================
-- COMPETITIONS
-- =====================================================

CREATE INDEX idx_competition_symposium
ON competitions(symposium_id);

CREATE INDEX idx_competition_type
ON competitions(competition_type_id);

CREATE INDEX idx_competition_venue
ON competitions(venue_id);

CREATE INDEX idx_competition_date
ON competitions(event_date);

-- =====================================================
-- APPLICATIONS
-- =====================================================

CREATE INDEX idx_application_student
ON applications(student_id);

CREATE INDEX idx_application_competition
ON applications(competition_id);

CREATE INDEX idx_application_status
ON applications(application_status);

-- =====================================================
-- TEAMS
-- =====================================================

CREATE INDEX idx_team_application
ON teams(application_id);

CREATE INDEX idx_team_leader
ON teams(leader_student_id);

-- =====================================================
-- TEAM MEMBERS
-- =====================================================

CREATE INDEX idx_team_member_team
ON team_members(team_id);

CREATE INDEX idx_team_member_student
ON team_members(student_id);

-- =====================================================
-- RESULTS
-- =====================================================

CREATE INDEX idx_result_application
ON competition_results(application_id);

CREATE INDEX idx_result_rank
ON competition_results(rank_position);

-- =====================================================
-- SUBMISSIONS
-- =====================================================

CREATE INDEX idx_submission_application
ON competition_submissions(application_id);

-- =====================================================
-- CERTIFICATES
-- =====================================================

CREATE INDEX idx_certificate_application
ON certificates(application_id);

CREATE INDEX idx_certificate_number
ON certificates(certificate_number);

-- =====================================================
-- REPORTS
-- =====================================================

CREATE INDEX idx_report_generated_by
ON reports_archive(generated_by);

-- =====================================================
-- NOTIFICATIONS
-- =====================================================

CREATE INDEX idx_notification_recipient
ON notifications(recipient_type, recipient_id);

CREATE INDEX idx_notification_status
ON notifications(delivery_status);

-- =====================================================
-- AUDIT LOGS
-- =====================================================

CREATE INDEX idx_audit_user
ON audit_logs(user_id);

CREATE INDEX idx_audit_time
ON audit_logs(action_time);