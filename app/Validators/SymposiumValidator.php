<?php

declare(strict_types=1);

/**
 * -------------------------------------------------------------------------
 * NexusCore
 * -------------------------------------------------------------------------
 * File        : SymposiumValidator.php
 * Location    : app/Validators/
 * Description : Validates symposium input before it reaches the
 *               service and model layers.
 *
 * Responsibilities
 * -------------------------------------------------------------------------
 * • Validate symposium code
 * • Validate title and type
 * • Validate department and academic year
 * • Validate description
 * • Validate registration and event dates
 * • Validate status
 * • Validate uploaded files
 *
 * NOTE
 * -------------------------------------------------------------------------
 * This class performs only input validation.
 * It does NOT communicate with the database.
 * Business rules belong in SymposiumService.
 *
 * Author      : Sugumaran J
 * Project     : NexusCore
 * -------------------------------------------------------------------------
 */

namespace App\Validators;

final class SymposiumValidator
{
    /**
     * Allowed symposium statuses.
     *
     * @var array<int, string>
     */
    private array $allowedStatuses = [
        'Draft',
        'Registration Open',
        'Registration Closed',
        'Completed',
        'Cancelled'
    ];

    /**
     * Allowed symposium types.
     *
     * @var array<int, string>
     */
    private array $allowedTypes = [
        'Intra Department',
        'Inter Department'
    ];

    /**
     * ---------------------------------------------------------------------
     * Validate symposium data.
     *
     * @param array $data
     * @param bool  $isCreate
     *
     * @return array<string, string>
     * ---------------------------------------------------------------------
     */
    public function validate(array $data, bool $isCreate = true): array
    {
        $errors = [];

        if ($isCreate) {
            $symposiumCode = strtoupper(trim((string) ($data['symposium_code'] ?? '')));

            if ($symposiumCode === '') {
                $errors['symposium_code'] = 'Symposium code is required.';
            }
            elseif (!preg_match('/^[A-Z0-9-]{2,30}$/', $symposiumCode)) {
                $errors['symposium_code'] = 'Symposium code must contain only letters, numbers and dashes (2-30 characters).';
            }
        }

        $title = trim((string) ($data['title'] ?? ''));

        if ($title === '') {
            $errors['title'] = 'Title is required.';
        }
        elseif (mb_strlen($title) > 150) {
            $errors['title'] = 'Title cannot exceed 150 characters.';
        }

        $symposiumType = trim((string) ($data['symposium_type'] ?? ''));

        if (!in_array($symposiumType, $this->allowedTypes, true)) {
            $errors['symposium_type'] = 'Please select a valid symposium type.';
        }

        $departmentId = trim((string) ($data['organizing_department_id'] ?? ''));

        if ($departmentId === '') {
            $errors['organizing_department_id'] = 'Please select an organizing department.';
        }
        elseif (!ctype_digit($departmentId) || (int) $departmentId <= 0) {
            $errors['organizing_department_id'] = 'Please select a valid department.';
        }

        $academicYear = trim((string) ($data['academic_year'] ?? ''));

        if ($academicYear === '') {
            $errors['academic_year'] = 'Academic year is required.';
        }
        elseif (!preg_match('/^\d{4}$/', $academicYear)) {
            $errors['academic_year'] = 'Academic year must be a valid 4-digit year.';
        }
        else {
            $year = (int) $academicYear;
            $currentYear = (int) date('Y');

            if ($year < 2000 || $year > ($currentYear + 10)) {
                $errors['academic_year'] = 'Academic year must be between 2000 and ' . ($currentYear + 10) . '.';
            }
        }

        $description = trim((string) ($data['description'] ?? ''));

        if ($description === '') {
            $errors['description'] = 'Description is required.';
        }

        $registrationStart = trim((string) ($data['registration_start'] ?? ''));
        $registrationEnd = trim((string) ($data['registration_end'] ?? ''));
        $eventStartDate = trim((string) ($data['event_start_date'] ?? ''));
        $eventEndDate = trim((string) ($data['event_end_date'] ?? ''));

        if ($registrationStart === '') {
            $errors['registration_start'] = 'Registration start date is required.';
        }
        elseif (!$this->isValidDateTime($registrationStart)) {
            $errors['registration_start'] = 'Registration start must be a valid date and time.';
        }

        if ($registrationEnd === '') {
            $errors['registration_end'] = 'Registration end date is required.';
        }
        elseif (!$this->isValidDateTime($registrationEnd)) {
            $errors['registration_end'] = 'Registration end must be a valid date and time.';
        }

        if ($eventStartDate === '') {
            $errors['event_start_date'] = 'Event start date is required.';
        }
        elseif (!$this->isValidDate($eventStartDate)) {
            $errors['event_start_date'] = 'Event start date must be in YYYY-MM-DD format.';
        }

        if ($eventEndDate === '') {
            $errors['event_end_date'] = 'Event end date is required.';
        }
        elseif (!$this->isValidDate($eventEndDate)) {
            $errors['event_end_date'] = 'Event end date must be in YYYY-MM-DD format.';
        }

        if (
            empty($errors['registration_start'])
            && empty($errors['registration_end'])
            && $this->isValidDateTime($registrationStart)
            && $this->isValidDateTime($registrationEnd)
        ) {
            if (strtotime($registrationStart) >= strtotime($registrationEnd)) {
                $errors['registration_end'] = 'Registration end must be after registration start.';
            }
        }

        if (
            empty($errors['registration_end'])
            && empty($errors['event_start_date'])
            && $this->isValidDateTime($registrationEnd)
            && $this->isValidDate($eventStartDate)
        ) {
            $registrationEndDate = date('Y-m-d', strtotime($registrationEnd));

            if ($registrationEndDate > $eventStartDate) {
                $errors['event_start_date'] = 'Event start must be on or after registration end date.';
            }
        }

        if (
            empty($errors['event_start_date'])
            && empty($errors['event_end_date'])
            && $this->isValidDate($eventStartDate)
            && $this->isValidDate($eventEndDate)
        ) {
            if ($eventStartDate > $eventEndDate) {
                $errors['event_end_date'] = 'Event end date must be on or after event start date.';
            }
        }

        $status = trim((string) ($data['status'] ?? ''));

        if (!in_array($status, $this->allowedStatuses, true)) {
            $errors['status'] = 'Please select a valid symposium status.';
        }

        return $errors;
    }

    /**
     * ---------------------------------------------------------------------
     * Validate uploaded symposium files.
     *
     * @param array $files
     *
     * @return array<string, string>
     * ---------------------------------------------------------------------
     */
    public function validateFiles(array $files): array
    {
        $errors = [];
        $maxFileSize = 2 * 1024 * 1024; // 2 MB

        // Define file type restrictions
        $fileFields = [
            'brochure' => ['label' => 'Brochure', 'extensions' => ['pdf']],
            'circular' => ['label' => 'Circular', 'extensions' => ['pdf']],
            'banner' => ['label' => 'Banner', 'extensions' => ['jpg', 'jpeg', 'png', 'webp']]
        ];

        foreach ($fileFields as $fieldName => $config) {
            if (!isset($files[$fieldName]) || $files[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $fileError = $this->validateUploadedFile(
                $files[$fieldName],
                $config['extensions'],
                $maxFileSize,
                $config['label']
            );

            if ($fileError !== null) {
                $errors[$fieldName] = $fileError;
            }
        }

        return $errors;
    }

    /**
     * ---------------------------------------------------------------------
     * Validate status change request.
     *
     * @param string $status
     *
     * @return string|null
     * ---------------------------------------------------------------------
     */
    public function validateStatus(string $status): ?string
    {
        if (!in_array($status, $this->allowedStatuses, true)) {
            return 'Please select a valid symposium status.';
        }

        return null;
    }

    /**
     * Validate a single uploaded file.
     *
     * @param array  $file
     * @param array  $allowedExtensions
     * @param int    $maxFileSize
     * @param string $label
     *
     * @return string|null
     */
    private function validateUploadedFile(
        array $file,
        array $allowedExtensions,
        int $maxFileSize,
        string $label
    ): ?string {
        if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
            return $label . ' exceeds the maximum allowed file size of 2 MB.';
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return $label . ' upload failed. Please try again.';
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            $extensionsFormatted = implode(', ', array_map('strtoupper', $allowedExtensions));
            return $label . ' must be a ' . $extensionsFormatted . ' file.';
        }

        if ($file['size'] > $maxFileSize) {
            return $label . ' exceeds the maximum allowed file size of 2 MB.';
        }

        // Validate image files
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            if (@getimagesize($file['tmp_name']) === false) {
                return $label . ' must be a valid image file.';
            }
        }

        // Validate PDF files
        if ($extension === 'pdf') {
            $mimeType = mime_content_type($file['tmp_name']);
            if ($mimeType !== false && $mimeType !== 'application/pdf') {
                return $label . ' must be a valid PDF file.';
            }
        }

        return null;
    }

    /**
     * Validate datetime string.
     *
     * @param string $value
     *
     * @return bool
     */
    private function isValidDateTime(string $value): bool
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $value)
            && !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value)
            && !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $value)
        ) {
            return false;
        }

        return strtotime($value) !== false;
    }

    /**
     * ---------------------------------------------------------------------
     * Validate date string.
     *
     * @param string $value
     *
     * @return bool
     * ---------------------------------------------------------------------
     */
    private function isValidDate(string $value): bool
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return false;
        }

        return strtotime($value) !== false;
    }
}
