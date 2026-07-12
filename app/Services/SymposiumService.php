<?php

declare(strict_types=1);

/**
 * -------------------------------------------------------------------------
 * NexusCore
 * -------------------------------------------------------------------------
 * File        : SymposiumService.php
 * Location    : app/Services/
 * Description : Business logic for Symposium Management.
 *
 * Responsibilities
 * -------------------------------------------------------------------------
 * • Validate department assignment rules
 * • Create, update and delete symposiums
 * • Upload, replace and remove symposium files
 * • Enforce role-based access rules
 * • Scope symposium search and statistics
 *
 * NOTE
 * -------------------------------------------------------------------------
 * This class NEVER contains SQL.
 * SQL belongs only inside SymposiumModel.
 *
 * Author      : Sugumaran J
 * Project     : NexusCore
 * -------------------------------------------------------------------------
 */

namespace App\Services;

use App\Models\SymposiumModel;

final class SymposiumService
{
    /**
     * Symposium Model.
     *
     * @var SymposiumModel
     */
    private SymposiumModel $symposiumModel;

    /**
     * Department Service.
     *
     * @var DepartmentService
     */
    private DepartmentService $departmentService;

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
     * Constructor.
     */
    public function __construct()
    {
        $this->symposiumModel = new SymposiumModel();
        $this->departmentService = new DepartmentService();
    }

    /**
     * Get available symposium types.
     *
     * @return array<int, string>
     */
    public function getAvailableTypes(): array
    {
        return $this->allowedTypes;
    }

    /**
     * Get available symposium statuses.
     *
     * @return array<int, string>
     */
    public function getAvailableStatuses(): array
    {
        return $this->allowedStatuses;
    }

    /**
     * Retrieve academic years from stored symposiums.
     *
     * @return array<int, string>
     */
    public function getAcademicYears(): array
    {
        $years = $this->symposiumModel->getDistinctAcademicYears();

        if (empty($years)) {
            $currentYear = (int) date('Y');

            return [
                (string) $currentYear,
                (string) ($currentYear + 1)
            ];
        }

        return $years;
    }

    /**
     * Search symposiums with filters and user scope.
     *
     * @param string      $search
     * @param string      $departmentId
     * @param string      $academicYear
     * @param string      $symposiumType
     * @param string      $status
     * @param string      $quickFilter
     * @param array       $user
     *
     * @return array
     */
    public function searchSymposiums(
        string $search = '',
        string $departmentId = '',
        string $academicYear = '',
        string $symposiumType = '',
        string $status = '',
        string $quickFilter = '',
        array $user = []
    ): array {
        [$scopeDepartmentId, $scopeCreatedBy] = $this->getScopeForUser($user);

        return $this->symposiumModel->search(
            $search !== '' ? $search : null,
            $departmentId !== '' ? $departmentId : null,
            $academicYear !== '' ? $academicYear : null,
            $symposiumType !== '' ? $symposiumType : null,
            $status !== '' ? $status : null,
            $quickFilter !== '' ? $quickFilter : null,
            $scopeDepartmentId,
            $scopeCreatedBy,
            null
        );
    }

    /**
     * Get symposium by ID.
     *
     * @param int $symposiumId
     *
     * @return array|false
     */
    public function getSymposiumById(int $symposiumId): array|false
    {
        return $this->symposiumModel->findById($symposiumId);
    }

    /**
     * Create symposium.
     *
     * @param array $data
     * @param array $files
     * @param array $user
     *
     * @return array{success: bool, message: string}
     */
    public function createSymposium(array $data, array $files, array $user): array
    {
        $symposiumCode = strtoupper(trim((string) ($data['symposium_code'] ?? '')));
        $departmentId = (int) ($data['organizing_department_id'] ?? 0);

        if (!$this->canCreateSymposium($user, $departmentId)) {
            return [
                'success' => false,
                'message' => 'You are not authorized to create symposiums for the selected department.'
            ];
        }

        if ($this->symposiumModel->findByCode($symposiumCode)) {
            return [
                'success' => false,
                'message' => 'Symposium code already exists.'
            ];
        }

        if (!$this->departmentService->getDepartmentById($departmentId)) {
            return [
                'success' => false,
                'message' => 'Organizing department does not exist.'
            ];
        }

        $brochureUpload = $this->processFileUpload(
            $files,
            'brochure',
            'uploads/symposiums/brochures',
            ['pdf'],
            null,
            false
        );

        if ($brochureUpload['error'] !== null) {
            return [
                'success' => false,
                'message' => $brochureUpload['error']
            ];
        }

        $circularUpload = $this->processFileUpload(
            $files,
            'circular',
            'uploads/symposiums/circulars',
            ['pdf'],
            null,
            false
        );

        if ($circularUpload['error'] !== null) {
            return [
                'success' => false,
                'message' => $circularUpload['error']
            ];
        }

        $bannerUpload = $this->processFileUpload(
            $files,
            'banner',
            'uploads/symposiums/banners',
            ['jpg', 'jpeg', 'png', 'webp'],
            null,
            false
        );

        if ($bannerUpload['error'] !== null) {
            return [
                'success' => false,
                'message' => $bannerUpload['error']
            ];
        }

        $created = $this->symposiumModel->create([
            'symposium_code' => $symposiumCode,
            'title' => trim((string) ($data['title'] ?? '')),
            'symposium_type' => trim((string) ($data['symposium_type'] ?? '')),
            'organizing_department_id' => $departmentId,
            'academic_year' => trim((string) ($data['academic_year'] ?? '')),
            'description' => trim((string) ($data['description'] ?? '')),
            'brochure_path' => $brochureUpload['path'],
            'circular_path' => $circularUpload['path'],
            'banner_path' => $bannerUpload['path'],
            'registration_start' => $this->normalizeDateTime(trim((string) ($data['registration_start'] ?? ''))),
            'registration_end' => $this->normalizeDateTime(trim((string) ($data['registration_end'] ?? ''))),
            'event_start_date' => trim((string) ($data['event_start_date'] ?? '')),
            'event_end_date' => trim((string) ($data['event_end_date'] ?? '')),
            'status' => trim((string) ($data['status'] ?? 'Draft')),
            'created_by' => (int) ($user['user_id'] ?? 0)
        ]);

        if (!$created) {
            return [
                'success' => false,
                'message' => 'Unable to create symposium.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Symposium created successfully.'
        ];
    }

    /**
     * Update symposium.
     *
     * @param int   $symposiumId
     * @param array $data
     * @param array $files
     * @param array $user
     *
     * @return array{success: bool, message: string}
     */
    public function updateSymposium(int $symposiumId, array $data, array $files, array $user): array
    {
        $symposium = $this->getSymposiumById($symposiumId);

        if (!$symposium) {
            return [
                'success' => false,
                'message' => 'Symposium not found.'
            ];
        }

        if (!$this->canEditSymposium($user, $symposium)) {
            return [
                'success' => false,
                'message' => 'You are not authorized to update this symposium.'
            ];
        }

        $departmentId = (int) ($data['organizing_department_id'] ?? 0);

        if (!$this->departmentService->getDepartmentById($departmentId)) {
            return [
                'success' => false,
                'message' => 'Organizing department does not exist.'
            ];
        }

        if (!$this->canCreateSymposium($user, $departmentId)) {
            return [
                'success' => false,
                'message' => 'You are not authorized to assign this department to the symposium.'
            ];
        }

        $brochureUpload = $this->processFileUpload(
            $files,
            'brochure',
            'uploads/symposiums/brochures',
            ['pdf'],
            $symposium['brochure_path'] ?? null,
            isset($data['remove_brochure']) && $data['remove_brochure'] === '1'
        );

        if ($brochureUpload['error'] !== null) {
            return [
                'success' => false,
                'message' => $brochureUpload['error']
            ];
        }

        $circularUpload = $this->processFileUpload(
            $files,
            'circular',
            'uploads/symposiums/circulars',
            ['pdf'],
            $symposium['circular_path'] ?? null,
            isset($data['remove_circular']) && $data['remove_circular'] === '1'
        );

        if ($circularUpload['error'] !== null) {
            return [
                'success' => false,
                'message' => $circularUpload['error']
            ];
        }

        $bannerUpload = $this->processFileUpload(
            $files,
            'banner',
            'uploads/symposiums/banners',
            ['jpg', 'jpeg', 'png', 'webp'],
            $symposium['banner_path'] ?? null,
            isset($data['remove_banner']) && $data['remove_banner'] === '1'
        );

        if ($bannerUpload['error'] !== null) {
            return [
                'success' => false,
                'message' => $bannerUpload['error']
            ];
        }

        $updated = $this->symposiumModel->update(
            $symposiumId,
            [
                'title' => trim((string) ($data['title'] ?? '')),
                'symposium_type' => trim((string) ($data['symposium_type'] ?? '')),
                'organizing_department_id' => $departmentId,
                'academic_year' => trim((string) ($data['academic_year'] ?? '')),
                'description' => trim((string) ($data['description'] ?? '')),
                'brochure_path' => $brochureUpload['path'],
                'circular_path' => $circularUpload['path'],
                'banner_path' => $bannerUpload['path'],
                'registration_start' => $this->normalizeDateTime(trim((string) ($data['registration_start'] ?? ''))),
                'registration_end' => $this->normalizeDateTime(trim((string) ($data['registration_end'] ?? ''))),
                'event_start_date' => trim((string) ($data['event_start_date'] ?? '')),
                'event_end_date' => trim((string) ($data['event_end_date'] ?? '')),
                'status' => trim((string) ($data['status'] ?? 'Draft'))
            ]
        );

        if (!$updated) {
            return [
                'success' => false,
                'message' => 'Unable to update symposium.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Symposium updated successfully.'
        ];
    }

    /**
     * Delete symposium.
     *
     * @param int   $symposiumId
     * @param array $user
     *
     * @return array{success: bool, message: string}
     */
    public function deleteSymposium(int $symposiumId, array $user): array
    {
        $symposium = $this->getSymposiumById($symposiumId);

        if (!$symposium) {
            return [
                'success' => false,
                'message' => 'Symposium not found.'
            ];
        }

        if (!$this->canDeleteSymposium($user, $symposium)) {
            return [
                'success' => false,
                'message' => 'You are not authorized to delete this symposium.'
            ];
        }

        // Check if there are linked competitions
        $linkedCompetitions = $this->symposiumModel->countCompetitions($symposiumId);

        if ($linkedCompetitions > 0) {
            return [
                'success' => false,
                'message' => 'Cannot delete symposium. There are ' . $linkedCompetitions . ' competition(s) linked to this symposium. Please delete the competitions first.'
            ];
        }

        $this->deleteUploadedFile($symposium['brochure_path'] ?? null);
        $this->deleteUploadedFile($symposium['circular_path'] ?? null);
        $this->deleteUploadedFile($symposium['banner_path'] ?? null);

        $deleted = $this->symposiumModel->delete($symposiumId);

        if (!$deleted) {
            return [
                'success' => false,
                'message' => 'Unable to delete symposium.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Symposium deleted successfully.'
        ];
    }

    /**
     * Change symposium status.
     *
     * @param int    $symposiumId
     * @param string $status
     * @param array  $user
     *
     * @return array{success: bool, message: string}
     */
    public function changeStatus(int $symposiumId, string $status, array $user): array
    {
        $symposium = $this->getSymposiumById($symposiumId);

        if (!$symposium) {
            return [
                'success' => false,
                'message' => 'Symposium not found.'
            ];
        }

        if (!$this->canChangeStatus($user, $symposium)) {
            return [
                'success' => false,
                'message' => 'You are not authorized to change the status of this symposium.'
            ];
        }

        if (!in_array($status, $this->allowedStatuses, true)) {
            return [
                'success' => false,
                'message' => 'Please select a valid symposium status.'
            ];
        }

        $now = time();
        $registrationEnd = strtotime((string) ($symposium['registration_end'] ?? ''));
        $eventEndDate = strtotime((string) ($symposium['event_end_date'] ?? '') . ' 23:59:59');

        if ($status === 'Registration Open' && $registrationEnd !== false && $registrationEnd <= $now) {
            return [
                'success' => false,
                'message' => 'Registration cannot be set to open after the registration end date.'
            ];
        }

        if ($status === 'Completed' && $eventEndDate !== false && $eventEndDate >= $now) {
            return [
                'success' => false,
                'message' => 'Completed status can only be set after the event end date.'
            ];
        }

        $updated = $this->symposiumModel->updateStatus($symposiumId, $status);

        if (!$updated) {
            return [
                'success' => false,
                'message' => 'Unable to update symposium status.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Symposium status updated successfully.'
        ];
    }

    /**
     * Count all symposiums.
     *
     * @param array $user
     *
     * @return int
     */
    public function countAll(array $user): int
    {
        [$scopeDepartmentId, $scopeCreatedBy] = $this->getScopeForUser($user);

        return $this->symposiumModel->countAll($scopeDepartmentId, $scopeCreatedBy, null);
    }

    /**
     * Count symposiums by status.
     *
     * @param string $status
     * @param array  $user
     *
     * @return int
     */
    public function countByStatus(string $status, array $user): int
    {
        [$scopeDepartmentId, $scopeCreatedBy] = $this->getScopeForUser($user);

        return $this->symposiumModel->countByStatus($status, $scopeDepartmentId, $scopeCreatedBy, null);
    }

    /**
     * Determine whether the user may create a symposium.
     *
     * @param array $user
     * @param int   $departmentId
     *
     * @return bool
     */
    public function canCreateSymposium(array $user, int $departmentId): bool
    {
        $role = $user['role'] ?? '';

        if ($role === 'Admin') {
            return true;
        }

        if ($role === 'HOD') {
            return (int) ($user['department_id'] ?? 0) === $departmentId;
        }

        return false;
    }

    /**
     * Determine whether the user may view a symposium.
     *
     * @param array $user
     * @param array $symposium
     *
     * @return bool
     */
    public function canViewSymposium(array $user, array $symposium): bool
    {
        $role = $user['role'] ?? '';

        if (in_array($role, ['Admin', 'Principal'], true)) {
            return true;
        }

        if ($role === 'HOD') {
            return (int) ($user['department_id'] ?? 0) === (int) ($symposium['organizing_department_id'] ?? 0);
        }

        if ($role === 'Staff') {
            return (int) ($user['user_id'] ?? 0) === (int) ($symposium['created_by'] ?? 0);
        }

        return false;
    }

    /**
     * Determine whether the user may edit a symposium.
     *
     * @param array $user
     * @param array $symposium
     *
     * @return bool
     */
    public function canEditSymposium(array $user, array $symposium): bool
    {
        $role = $user['role'] ?? '';

        if ($role === 'Admin') {
            return true;
        }

        if ($role === 'HOD') {
            return (int) ($user['department_id'] ?? 0) === (int) ($symposium['organizing_department_id'] ?? 0);
        }

        if ($role === 'Staff') {
            return (int) ($user['user_id'] ?? 0) === (int) ($symposium['created_by'] ?? 0);
        }

        return false;
    }

    /**
     * Determine whether the user may delete a symposium.
     *
     * @param array $user
     * @param array $symposium
     *
     * @return bool
     */
    public function canDeleteSymposium(array $user, array $symposium): bool
    {
        return $this->canEditSymposium($user, $symposium);
    }

    /**
     * Determine whether the user may change symposium status.
     *
     * @param array $user
     * @param array $symposium
     *
     * @return bool
     */
    public function canChangeStatus(array $user, array $symposium): bool
    {
        $role = $user['role'] ?? '';

        if (in_array($role, ['Admin', 'Principal'], true)) {
            return true;
        }

        if ($role === 'HOD') {
            return (int) ($user['department_id'] ?? 0) === (int) ($symposium['organizing_department_id'] ?? 0);
        }

        if ($role === 'Staff') {
            return (int) ($user['user_id'] ?? 0) === (int) ($symposium['created_by'] ?? 0);
        }

        return false;
    }

    /**
     * Build scope parameters based on user role.
     *
     * @param array $user
     *
     * @return array{int|null,int|null}
     */
    private function getScopeForUser(array $user): array
    {
        $role = $user['role'] ?? '';

        if (in_array($role, ['Admin', 'Principal'], true)) {
            return [null, null];
        }

        if ($role === 'HOD') {
            return [(int) ($user['department_id'] ?? 0), null];
        }

        if ($role === 'Staff') {
            return [null, (int) ($user['user_id'] ?? 0)];
        }

        return [null, null];
    }

    /**
     * Convert a datetime-local input or date-time string into MySQL datetime.
     *
     * @param string $value
     *
     * @return string
     */
    private function normalizeDateTime(string $value): string
    {
        return str_replace('T', ' ', trim($value));
    }

    /**
     * Process a symposium file upload.
     *
     * @param array       $files
     * @param string      $fieldName
     * @param string      $folder
     * @param array       $allowedExtensions
     * @param string|null $existingPath
     * @param bool        $removeExisting
     *
     * @return array{path: string|null, error: string|null}
     */
    private function processFileUpload(
        array $files,
        string $fieldName,
        string $folder,
        array $allowedExtensions,
        ?string $existingPath = null,
        bool $removeExisting = false
    ): array {
        if ($removeExisting && $existingPath !== null && $existingPath !== '') {
            $this->deleteUploadedFile($existingPath);
            $existingPath = null;
        }

        if (!isset($files[$fieldName]) || $files[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
            return [
                'path' => $existingPath,
                'error' => null
            ];
        }

        $uploadedPath = $this->uploadFile(
            $files[$fieldName],
            $folder,
            $allowedExtensions
        );

        if ($uploadedPath === null) {
            return [
                'path' => $existingPath,
                'error' => ucfirst(str_replace('_', ' ', $fieldName)) . ' upload failed. Please select a valid file.'
            ];
        }

        if ($existingPath !== null && $existingPath !== '') {
            $this->deleteUploadedFile($existingPath);
        }

        return [
            'path' => $uploadedPath,
            'error' => null
        ];
    }

    /**
     * Upload a file to public storage.
     *
     * @param array  $file
     * @param string $folder
     * @param array  $allowedExtensions
     *
     * @return string|null
     */
    private function uploadFile(array $file, string $folder, array $allowedExtensions): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            return null;
        }

        $uploadDir = dirname(__DIR__, 2) . '/public/' . trim($folder, '/') . '/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = uniqid('symposium_', true) . '.' . $extension;
        $destination = $uploadDir . $fileName;

        if (!is_uploaded_file($file['tmp_name'])) {
            return null;
        }

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return null;
        }

        return trim($folder, '/') . '/' . $fileName;
    }

    /**
     * Delete an uploaded symposium file.
     *
     * @param string|null $relativePath
     *
     * @return void
     */
    private function deleteUploadedFile(?string $relativePath): void
    {
        if ($relativePath === null || trim($relativePath) === '') {
            return;
        }

        if (!str_starts_with($relativePath, 'uploads/symposiums/')) {
            return;
        }

        $filePath = dirname(__DIR__, 2) . '/public/' . $relativePath;

        if (is_file($filePath)) {
            unlink($filePath);
        }
    }
}
