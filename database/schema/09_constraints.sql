USE nexus_ems_db;

ALTER TABLE applications
ADD CONSTRAINT uq_student_competition
UNIQUE (student_id, competition_id);

ALTER TABLE competition_coordinators
ADD CONSTRAINT uq_competition_user
UNIQUE (competition_id, user_id);

ALTER TABLE competition_coordinators
ADD CONSTRAINT uq_competition_responsibility
UNIQUE (competition_id, responsibility);

ALTER TABLE team_members
ADD CONSTRAINT uq_team_student
UNIQUE (team_id, student_id);

ALTER TABLE competition_results
ADD CONSTRAINT uq_result_application
UNIQUE (application_id);

ALTER TABLE certificates
ADD CONSTRAINT uq_certificate_application
UNIQUE (application_id);