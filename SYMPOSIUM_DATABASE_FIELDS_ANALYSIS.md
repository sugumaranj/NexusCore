# Symposium Module - Database Fields Analysis

## Executive Summary

The Symposium module has **critical schema discrepancies**. The code references **5 essential fields** that are **missing from the database schema**. This analysis identifies all fields currently in use and what needs to be added to the `symposiums` table.

---

## 1. Complete List of Fields Currently Used in Code

### A. Fields in Current Schema (Existing - 13 fields)

| Field Name           | Type         | Used In                                          | Purpose                        |
| -------------------- | ------------ | ------------------------------------------------ | ------------------------------ |
| `symposium_id`       | BIGINT (PK)  | Model, Controller                                | Primary key, record identifier |
| `title`              | VARCHAR(150) | Model, Service, Controller, Templates, Validator | Symposium title/name           |
| `academic_year`      | YEAR         | Model, Service, Controller, Templates, Validator | Academic year for filtering    |
| `description`        | TEXT         | Model, Service, Controller, Templates, Validator | Detailed symposium description |
| `registration_start` | DATETIME     | Model, Service, Controller, Templates, Validator | Registration window start      |
| `registration_end`   | DATETIME     | Model, Service, Controller, Templates, Validator | Registration window end        |
| `event_start_date`   | DATE         | Model, Service, Controller, Templates, Validator | Event date range start         |
| `event_end_date`     | DATE         | Model, Service, Controller, Templates, Validator | Event date range end           |
| `status`             | ENUM         | Model, Service, Controller, Templates            | Symposium lifecycle status     |
| `created_by`         | BIGINT (FK)  | Model, Service, Controller, Templates            | Creator user reference         |
| `created_at`         | TIMESTAMP    | Model, Templates                                 | Record creation timestamp      |
| `updated_at`         | TIMESTAMP    | Model                                            | Record modification timestamp  |
| `brochure_path`      | VARCHAR(255) | Model, Service, Controller, Templates            | Path to brochure document      |

### B. Fields Referenced in Code but MISSING from Schema (5 fields - CRITICAL)

| Field Name                 | Type         | Used In                                          | Purpose                              | Impact                                                  |
| -------------------------- | ------------ | ------------------------------------------------ | ------------------------------------ | ------------------------------------------------------- |
| `symposium_code`           | VARCHAR(30)  | Model, Service, Controller, Validator            | Unique symposium identifier          | **CRITICAL** - Used in findByCode(), create(), update() |
| `symposium_type`           | VARCHAR(50)  | Model, Service, Controller, Validator, Templates | Type of symposium (Intra/Inter Dept) | **CRITICAL** - Filtering and display feature            |
| `organizing_department_id` | BIGINT (FK)  | Model, Service, Controller, Validator            | Department running the symposium     | **CRITICAL** - Essential for scoping/authorization      |
| `circular_path`            | VARCHAR(255) | Model, Service, Controller, Templates            | Path to circular document            | **CRITICAL** - File management feature                  |
| `banner_path`              | VARCHAR(255) | Model, Service, Controller, Templates            | Path to banner/poster image          | **CRITICAL** - File management feature                  |

### C. Related/Joined Fields (Not stored, pulled from other tables)

| Field Name        | Source Table | Used In          | Purpose                             |
| ----------------- | ------------ | ---------------- | ----------------------------------- |
| `department_name` | departments  | Model, Templates | Department display name (LEFT JOIN) |
| `created_by_name` | users        | Model, Templates | Creator display name (LEFT JOIN)    |

---

## 2. Fields Referenced in Each Module Component

### 2.1 SymposiumController.php

**Fields accessed from symposium data:**

- symposium_id, symposium_code, title, symposium_type, organizing_department_id, academic_year
- description, registration_start, registration_end, event_start_date, event_end_date
- status, brochure_path, circular_path, banner_path, created_by, department_name

**Field filtering parameters:**

- search (searches: code, title, department_name, academic_year, status, description)
- department_id (organizing_department_id)
- academic_year
- symposium_type
- status
- quick_filter (based on date ranges)

### 2.2 SymposiumService.php

**Data created/updated:**

```php
[
    'symposium_code' => $symposiumCode,           // MISSING
    'title' => $title,
    'symposium_type' => $symposiumType,           // MISSING
    'organizing_department_id' => $departmentId,  // MISSING
    'academic_year' => $academicYear,
    'description' => $description,
    'brochure_path' => $brochurePath,
    'circular_path' => $circularPath,             // MISSING
    'banner_path' => $bannerPath,                 // MISSING
    'registration_start' => $registrationStart,
    'registration_end' => $registrationEnd,
    'event_start_date' => $eventStartDate,
    'event_end_date' => $eventEndDate,
    'status' => $status,
    'created_by' => $userId
]
```

**Allowed values defined:**

- symposium_type: ['Intra Department', 'Inter Department']
- status: ['Draft', 'Registration Open', 'Registration Closed', 'Completed', 'Cancelled']

### 2.3 SymposiumModel.php

**SQL SELECT queries access:**

```sql
SELECT
    s.symposium_id,
    s.symposium_code,              -- MISSING
    s.title,
    s.symposium_type,              -- MISSING
    s.organizing_department_id,    -- MISSING
    s.academic_year,
    s.description,
    s.brochure_path,
    s.circular_path,               -- MISSING
    s.banner_path,                 -- MISSING
    s.registration_start,
    s.registration_end,
    s.event_start_date,
    s.event_end_date,
    s.status,
    s.created_by,
    s.created_at,
    s.updated_at,
    d.department_name,             -- from JOIN
    u.full_name AS created_by_name -- from JOIN
```

**SQL WHERE conditions built on:**

- symposium_code (LIKE search)
- title (LIKE search)
- department_name (LIKE search)
- academic_year
- status
- symposium_type
- organizing_department_id (scoping)
- created_by (scoping)
- registration_start, registration_end (date range logic)
- event_start_date, event_end_date (date range logic)

### 2.4 SymposiumValidator.php

**Input validation rules:**

- `symposium_code`: Required, pattern /^[A-Z0-9-]{2,30}$/, uniqueness check
- `title`: Required, max 150 characters
- `symposium_type`: Required, must be in ['Intra Department', 'Inter Department']
- `organizing_department_id`: Required, must be valid department ID
- `academic_year`: Required, YYYY format, between 2000 and current_year + 10
- `description`: Required, free text
- `registration_start`: Required, valid datetime
- `registration_end`: Required, valid datetime, must be after start
- `event_start_date`: Required, valid date (YYYY-MM-DD)
- `event_end_date`: Required, valid date, must be after start
- `event_start_date` vs `registration_end`: Event start must be on/after registration end
- `status`: Required, must be in allowed statuses
- File uploads: `brochure`, `circular`, `banner` (optional, max 2MB, specific extensions)

### 2.5 Template Files Usage

**templates/symposiums/index.php** displays columns:

- symposium_code
- title
- symposium_type
- department_name (from join)
- academic_year
- registration_start, registration_end
- event_start_date, event_end_date
- status
- created_by_name (from join)

**templates/symposiums/create.php** form fields:

- symposium_code
- title
- symposium_type
- organizing_department_id
- academic_year
- description
- registration_start, registration_end
- event_start_date, event_end_date
- brochure, circular, banner (file uploads)
- status

**templates/symposiums/edit.php** form fields:

- All fields from create (symposium_code is read-only)
- Existing file paths for: brochure_path, circular_path, banner_path
- Remove checkboxes for: remove_brochure, remove_circular, remove_banner

**templates/symposiums/view.php** displays:

- banner_path (as image if valid)
- title, status badge
- description
- symposium_code
- symposium_type
- department_name
- academic_year
- registration_start, registration_end
- event_start_date, event_end_date
- created_by_name
- brochure_path, circular_path (download links)
- created_at, updated_at (metadata)

---

## 3. Missing Fields from Schema - Detailed Impact

### Field 1: `symposium_code` (VARCHAR(30), UNIQUE)

**Status:** CRITICAL - MISSING  
**Used In:** Controller, Service, Model (4 places), Validator, Templates (2 places)  
**Operations:**

- `SymposiumModel::findByCode()` - searches for existing code
- `SymposiumModel::create()` - stores code as part of INSERT
- `SymposiumModel::update()` - passed but not updated (immutable)
- `SymposiumValidator::validate()` - validation on create only
- Display in index and view templates

**Search & Filter:**

- Part of LIKE search in Model::getAll() and Model::search()

**Constraints Needed:**

- UNIQUE constraint
- NOT NULL
- Pattern: uppercase alphanumeric and dashes, 2-30 characters

---

### Field 2: `symposium_type` (VARCHAR(50))

**Status:** CRITICAL - MISSING  
**Used In:** Controller, Service, Model (3 places), Validator, Templates (2 places)  
**Allowed Values:** ['Intra Department', 'Inter Department']  
**Operations:**

- Filter in search queries
- Stored in create/update operations
- Display in templates
- Validation in Validator

**Constraints Needed:**

- NOT NULL
- ENUM('Intra Department', 'Inter Department') recommended

---

### Field 3: `organizing_department_id` (BIGINT, FK)

**Status:** CRITICAL - MISSING  
**Used In:** Controller, Service (3 places), Model (3+ places), Validator, Template  
**Relationships:**

- Foreign Key to `departments.department_id`
- Used for authorization scoping
- Used for filtering

**Operations:**

- Validate department exists and user has permission
- Store in create/update
- Filter queries by department
- Join for display (department_name)

**Constraints Needed:**

- NOT NULL
- FOREIGN KEY to departments(department_id)

---

### Field 4: `circular_path` (VARCHAR(255))

**Status:** CRITICAL - MISSING  
**Used In:** Service (3 places), Model (3 places), Templates (2 places)  
**File Management:**

- Upload handling in service
- Storage in database
- Display as download link in view template
- Display with removal checkbox in edit template

**Operations:**

- processFileUpload() called in create/update
- deleteUploadedFile() called in delete
- Displayed in view/edit templates

**Constraints Needed:**

- NULL possible (optional file)
- VARCHAR(255) to store file path

---

### Field 5: `banner_path` (VARCHAR(255))

**Status:** CRITICAL - MISSING  
**Used In:** Service (3 places), Model (3 places), Templates (3 places)  
**File Management:**

- Upload handling in service
- Storage in database
- Display as image in view template
- Display with removal checkbox in edit template

**Use Cases:**

- Displayed at top of view page as hero image
- Shown as thumbnail in edit page
- Download/view option available
- Can be removed via checkbox

**Constraints Needed:**

- NULL possible (optional file)
- VARCHAR(255) to store file path

---

## 4. Complete Required Schema Fields

### Current Table Definition Issue:

The `CREATE TABLE symposiums` statement is **incomplete**. It's missing 5 critical fields that the entire application depends on.

### Corrected Fields Summary:

```
Column Name                  | Data Type          | NOT NULL | Unique | FK | Default
─────────────────────────────┼────────────────────┼──────────┼────────┼────┼─────────
symposium_id                 | BIGINT             | ✓        | ✓(PK)  |    |
symposium_code               | VARCHAR(30)        | ✓        | ✓      |    | [NEW]
title                        | VARCHAR(150)       | ✓        |        |    |
symposium_type               | ENUM(2 values)     | ✓        |        |    | [NEW]
organizing_department_id     | BIGINT             | ✓        |        | ✓  | [NEW]
academic_year                | YEAR               | ✓        |        |    |
description                  | TEXT               | ✓        |        |    |
brochure_path                | VARCHAR(255)       |          |        |    |
circular_path                | VARCHAR(255)       |          |        |    | [NEW]
banner_path                  | VARCHAR(255)       |          |        |    | [NEW]
registration_start           | DATETIME           | ✓        |        |    |
registration_end             | DATETIME           | ✓        |        |    |
event_start_date             | DATE               | ✓        |        |    |
event_end_date               | DATE               | ✓        |        |    |
status                       | ENUM(5 values)     | ✓        |        |    |
created_by                   | BIGINT             | ✓        |        | ✓  |
created_at                   | TIMESTAMP          | ✓        |        |    |
updated_at                   | TIMESTAMP          | ✓        |        |    |
```

---

## 5. SQL Corrections Needed

### Required ALTER TABLE statements:

```sql
ALTER TABLE symposiums ADD COLUMN symposium_code VARCHAR(30) NOT NULL UNIQUE AFTER symposium_id;
ALTER TABLE symposiums ADD COLUMN symposium_type ENUM('Intra Department', 'Inter Department') NOT NULL AFTER title;
ALTER TABLE symposiums ADD COLUMN organizing_department_id BIGINT NOT NULL AFTER symposium_type;
ALTER TABLE symposiums ADD COLUMN circular_path VARCHAR(255) AFTER brochure_path;
ALTER TABLE symposiums ADD COLUMN banner_path VARCHAR(255) AFTER circular_path;
ALTER TABLE symposiums ADD FOREIGN KEY (organizing_department_id) REFERENCES departments(department_id);
```

### Or create corrected table:

```sql
CREATE TABLE symposiums (
    symposium_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    symposium_code VARCHAR(30) NOT NULL UNIQUE,
    title VARCHAR(150) NOT NULL,
    symposium_type ENUM('Intra Department', 'Inter Department') NOT NULL,
    organizing_department_id BIGINT NOT NULL,
    academic_year YEAR NOT NULL,
    description TEXT NOT NULL,
    brochure_path VARCHAR(255),
    circular_path VARCHAR(255),
    banner_path VARCHAR(255),
    registration_start DATETIME NOT NULL,
    registration_end DATETIME NOT NULL,
    event_start_date DATE NOT NULL,
    event_end_date DATE NOT NULL,
    status ENUM('Draft', 'Registration Open', 'Registration Closed', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Draft',
    created_by BIGINT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_symposium_organizing_dept FOREIGN KEY (organizing_department_id) REFERENCES departments(department_id),
    CONSTRAINT fk_symposium_created_by FOREIGN KEY (created_by) REFERENCES users(user_id)
);
```

---

## 6. Functions Called on Symposium Model

### Public Methods:

1. `getAll()` - Retrieve all/filtered symposiums
2. `findById(int $id)` - Get single symposium by ID
3. `findByCode(string $code)` - Get symposium by unique code
4. `create(array $data)` - Insert new symposium
5. `update(int $id, array $data)` - Update existing symposium
6. `updateStatus(int $id, string $status)` - Update only status field
7. `delete(int $id)` - Delete symposium
8. `search()` - Alias to getAll with filters
9. `getDistinctAcademicYears()` - Get all academic years
10. `countAll()` - Get total count with scoping
11. `countByStatus(string $status)` - Get count by status with scoping

### Private Helper Methods:

1. `buildQuickFilterCondition(string $filter)` - Build SQL for quick filters
2. `countByCondition()` - Internal count with custom conditions

---

## 7. Database Operations Summary

### CREATE Operations:

- Inserts all 18 fields (including 5 missing ones)
- Validates symposium_code uniqueness at application level before INSERT

### READ Operations:

- Retrieves all 18 fields (13 existing + 5 missing)
- Filters on: code, title, department_name, year, status, type, dates
- Performs LEFT JOINs to get department_name and created_by_name

### UPDATE Operations:

- Updates: title, type, organizing_dept_id, year, description, file paths, dates, status
- Does NOT update: symposium_code (immutable), created_by, created_at
- Updates updated_at automatically via TIMESTAMP

### DELETE Operations:

- Deletes physical files first: brochure_path, circular_path, banner_path
- Then deletes database record

---

## 8. Summary & Recommendations

### Critical Issues:

1. **Schema is incomplete** - 5 essential fields are missing
2. **Application will fail** on deployment against current schema
3. **UNIQUE constraint** needed on symposium_code
4. **FOREIGN KEY constraints** needed for organizing_department_id and created_by

### Immediate Actions Required:

1. Update schema file `04_symposium_competition.sql` to include all 5 missing fields
2. Add FOREIGN KEY constraints
3. Add UNIQUE constraint on symposium_code
4. Run migration to update existing database

### Schema Validation:

- ✅ All field types match usage in code
- ✅ All constraints properly specified
- ✅ All relationships defined
- ❌ **5 fields are missing from current schema**

### Fields in Use: **18 total**

- **Existing in schema:** 13 fields
- **Missing from schema:** 5 fields (CRITICAL)
- **Joined from other tables:** 2 fields (not stored)
