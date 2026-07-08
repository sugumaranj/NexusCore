USE nexus_ems_db;

INSERT INTO symposiums
(
    symposium_code,
    title,
    symposium_type,
    organizing_department_id,
    academic_year,
    description,
    brochure_path,
    circular_path,
    banner_path,
    registration_start,
    registration_end,
    event_start_date,
    event_end_date,
    status,
    created_by
)
VALUES
(
    'NEXUS2027',
    'NEXUS 2027',
    'Intra Department',
    2,
    2027,
    'Annual Intra Department Technical and Non-Technical Symposium',
    NULL,
    NULL,
    NULL,
    '2027-02-01 09:00:00',
    '2027-02-20 17:00:00',
    '2027-02-27',
    '2027-02-28',
    'Draft',
    1
);