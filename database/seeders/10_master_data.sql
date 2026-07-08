USE nexus_ems_db;

-- =====================================================
-- MASTER DATA : DEPARTMENTS
-- =====================================================

INSERT INTO departments
(department_code, department_name, short_name)
VALUES
('CS', 'Bachelor of Science in Computer Science', 'B.Sc. CS'),
('CA', 'Bachelor of Computer Applications', 'BCA');



-- =====================================================
-- MASTER DATA : VENUES
-- =====================================================

INSERT INTO venues
(venue_code, venue_name, building_name, floor, seating_capacity, is_computer_lab)
VALUES

('LAB1','Computer Laboratory 1','Computer Science Block','Ground Floor',60,1),

('LAB2','Computer Laboratory 2','Computer Science Block','First Floor',60,1),

('LAB3','Computer Laboratory 3','Computer Science Block','Second Floor',60,1),

('SEMHALL','Seminar Hall','Main Block','Ground Floor',250,0),

('AUDITORIUM','College Auditorium','Main Block','Ground Floor',600,0),

('A101','Classroom A101','Academic Block','First Floor',80,0),

('A102','Classroom A102','Academic Block','First Floor',80,0),

('OPENSTAGE','Open Air Stage','College Campus','Ground',500,0);



-- =====================================================
-- MASTER DATA : COMPETITION TYPES
-- =====================================================

INSERT INTO competition_types
(type_code,
type_name,
category,
is_team_event,
supports_upload,
supports_prelims,
supports_batch,
default_team_size)

VALUES

('COD',
'Coding',
'Technical',
0,
0,
0,
1,
1),

('DBG',
'Debugging',
'Technical',
0,
0,
1,
1,
1),

('QUIZ',
'Technical Quiz',
'Technical',
1,
0,
1,
1,
2),

('PAPER',
'Paper Presentation',
'Technical',
1,
1,
0,
0,
2),

('POSTER',
'Poster Presentation',
'Technical',
1,
1,
0,
0,
2),

('WEB',
'Web Design',
'Technical',
1,
0,
0,
0,
2),

('UIUX',
'UI / UX Design',
'Technical',
1,
1,
0,
0,
2),

('PHOTO',
'Photography',
'Non-Technical',
0,
1,
0,
0,
1),

('FILM',
'Short Film',
'Non-Technical',
1,
1,
0,
0,
5),

('DRAW',
'Drawing',
'Non-Technical',
0,
0,
0,
0,
1),

('EWASTE',
'E-Waste Innovation',
'Technical',
1,
0,
0,
0,
3),

('COOK',
'Traditional Cooking',
'Non-Technical',
1,
0,
0,
0,
4),

('MEME',
'Meme Creation',
'Non-Technical',
1,
1,
0,
0,
2),

('TREASURE',
'Treasure Hunt',
'Non-Technical',
1,
0,
0,
0,
5),

('CONNECTION',
'Connections',
'Non-Technical',
1,
0,
0,
0,
4);



-- =====================================================
-- MASTER DATA : SYSTEM SETTINGS
-- =====================================================

INSERT INTO system_settings
(setting_key,
setting_value,
description)

VALUES

('COLLEGE_NAME',
'Government Arts and Science College, Veerapandi, Theni',
'College Name'),

('COLLEGE_SHORT_NAME',
'GASC',
'College Short Name'),

('SYSTEM_NAME',
'NexusCore Symposium Platform',
'System Name'),

('CURRENT_ACADEMIC_YEAR',
'2026',
'Current Academic Year'),

('CERTIFICATE_PREFIX',
'NEXUS',
'Certificate Number Prefix'),

('APPLICATION_PREFIX',
'APP',
'Application Number Prefix'),

('DEFAULT_TIMEZONE',
'Asia/Kolkata',
'System Time Zone'),

('REGISTRATION_STATUS',
'OPEN',
'Global Registration Status'),

('MAX_LOGIN_ATTEMPTS',
'5',
'Maximum Login Attempts'),

('SESSION_TIMEOUT',
'30',
'Session Timeout in Minutes'),

('PASSWORD_MIN_LENGTH',
'8',
'Minimum Password Length'),

('ALLOW_MULTIPLE_LOGIN',
'NO',
'Allow Multiple Sessions'),

('ENABLE_AUDIT_LOG',
'YES',
'Enable Audit Logging'),

('ENABLE_NOTIFICATIONS',
'YES',
'Enable Notification Service'),

('ENABLE_CERTIFICATE_VERIFICATION',
'YES',
'Enable QR Certificate Verification');