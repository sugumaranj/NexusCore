USE nexus_ems_db;

-- =====================================================
-- MIGRATION: Add missing symposium fields
-- =====================================================
-- This migration adds 5 critical fields that are
-- referenced throughout the Symposium module code
-- but were missing from the original schema.
-- =====================================================

-- Add symposium_code (unique identifier for each symposium)
ALTER TABLE symposiums 
ADD COLUMN symposium_code VARCHAR(30) NOT NULL UNIQUE 
AFTER symposium_id;

-- Add symposium_type (Intra Department or Inter Department)
ALTER TABLE symposiums 
ADD COLUMN symposium_type VARCHAR(50) NOT NULL 
DEFAULT 'Intra Department'
AFTER title;

-- Add organizing_department_id (which department runs this symposium)
ALTER TABLE symposiums 
ADD COLUMN organizing_department_id BIGINT NOT NULL 
AFTER academic_year;

-- Add circular_path (path to circular/notification document)
ALTER TABLE symposiums 
ADD COLUMN circular_path VARCHAR(255) NULL 
AFTER brochure_path;

-- Add banner_path (path to banner/poster image)
ALTER TABLE symposiums 
ADD COLUMN banner_path VARCHAR(255) NULL 
AFTER circular_path;

-- Add foreign key constraint for organizing_department_id
ALTER TABLE symposiums 
ADD CONSTRAINT fk_symposium_organizing_department 
FOREIGN KEY (organizing_department_id) 
REFERENCES departments(department_id) 
ON DELETE RESTRICT 
ON UPDATE CASCADE;

-- Add unique index on symposium_code
CREATE UNIQUE INDEX idx_symposium_code ON symposiums(symposium_code);

-- Add index for department_id for faster filtering
CREATE INDEX idx_symposium_department ON symposiums(organizing_department_id);

-- Add index for academic_year for faster filtering
CREATE INDEX idx_symposium_academic_year ON symposiums(academic_year);

-- Add index for status for faster filtering
CREATE INDEX idx_symposium_status ON symposiums(status);

-- Add index for created_by for faster filtering
CREATE INDEX idx_symposium_created_by ON symposiums(created_by);

-- =====================================================
-- END MIGRATION
-- =====================================================
