<?php

declare(strict_types=1);

/**
 * -------------------------------------------------------------------------
 * NexusCore
 * -------------------------------------------------------------------------
 * File        : SymposiumController.php
 * Location    : app/Controllers/
 * Description : Handles all Symposium Management HTTP requests.
 *
 * Responsibilities
 * -------------------------------------------------------------------------
 * • Display symposium list
 * • Display create symposium form
 * • Validate symposium input
 * • Create symposium
 * • Edit symposium
 * • Update symposium
 * • View symposium details
 * • Delete symposium
 * • Change symposium status
 *
 * NOTE
 * -------------------------------------------------------------------------
 * • Controller NEVER contains SQL.
 * • Business logic belongs to SymposiumService.
 * • Validation belongs to SymposiumValidator.
 * • Database operations belong to SymposiumModel.
 *
 * Author      : Sugumaran J
 * Project     : NexusCore
 * -------------------------------------------------------------------------
 */

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Services\DepartmentService;
use App\Services\SymposiumService;
use App\Validators\SymposiumValidator;

final class SymposiumController extends BaseController
{
    private SymposiumService $symposiumService;
    private SymposiumValidator $validator;
    private DepartmentService $departmentService;

    public function __construct()
    {
        AuthMiddleware::handle();

        $this->symposiumService = new SymposiumService();

        $this->validator = new SymposiumValidator();

        $this->departmentService = new DepartmentService();
    }

    public function index(): void
    {
        $search = trim((string) ($_GET['search'] ?? ''));
        $departmentId = trim((string) ($_GET['department_id'] ?? ''));
        $academicYear = trim((string) ($_GET['academic_year'] ?? ''));
        $symposiumType = trim((string) ($_GET['symposium_type'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));
        $quickFilter = trim((string) ($_GET['quick_filter'] ?? ''));

        $currentUser = $this->user();

        $symposiums = $this->symposiumService->searchSymposiums(
            $search,
            $departmentId,
            $academicYear,
            $symposiumType,
            $status,
            $quickFilter,
            $currentUser
        );

        foreach ($symposiums as &$symposium) {
            $symposium['can_edit'] = $this->symposiumService->canEditSymposium($currentUser, $symposium);
            $symposium['can_delete'] = $this->symposiumService->canDeleteSymposium($currentUser, $symposium);
            $symposium['can_change_status'] = $this->symposiumService->canChangeStatus($currentUser, $symposium);
        }
        unset($symposium);

        $academicYears = $this->symposiumService->getAcademicYears();

        $this->render(
            'symposiums.index',
            [
                'pageTitle' => 'Symposium Management',
                'symposiums' => $symposiums,
                'search' => $search,
                'department_id' => $departmentId,
                'academic_year' => $academicYear,
                'symposium_type' => $symposiumType,
                'status' => $status,
                'quick_filter' => $quickFilter,
                'departments' => $this->departmentService->getAllDepartments(),
                'academicYears' => $academicYears,
                'symposiumTypes' => $this->symposiumService->getAvailableTypes(),
                'symposiumStatuses' => $this->symposiumService->getAvailableStatuses(),
                'quickFilters' => [
                    'Registration Open',
                    'Registration Closed',
                    'Upcoming',
                    'Completed'
                ],
                'totalSymposiums' => $this->symposiumService->countAll($currentUser),
                'draftCount' => $this->symposiumService->countByStatus('Draft', $currentUser),
                'registrationOpenCount' => $this->symposiumService->countByStatus('Registration Open', $currentUser),
                'registrationClosedCount' => $this->symposiumService->countByStatus('Registration Closed', $currentUser),
                'completedCount' => $this->symposiumService->countByStatus('Completed', $currentUser),
                'cancelledCount' => $this->symposiumService->countByStatus('Cancelled', $currentUser),
                'canCreateSymposium' => $this->symposiumService->canCreateSymposium($currentUser, (int) ($currentUser['department_id'] ?? 0))
            ]
        );
    }

    public function create(): void
    {
        $currentUser = $this->user();

        if (!$this->symposiumService->canCreateSymposium($currentUser, (int) ($currentUser['department_id'] ?? 0))) {
            $this->error('You are not authorized to create symposiums.');
            $this->redirect('/symposiums');
        }

        $this->render(
            'symposiums.create',
            [
                'pageTitle' => 'Create Symposium',
                'departments' => $this->departmentService->getAllDepartments(),
                'academicYears' => $this->symposiumService->getAcademicYears(),
                'symposiumTypes' => $this->symposiumService->getAvailableTypes(),
                'symposiumStatuses' => $this->symposiumService->getAvailableStatuses(),
                'old' => [],
                'errors' => []
            ]
        );
    }

    public function store(): void
    {
        $currentUser = $this->user();

        $errors = array_merge(
            $this->validator->validate($_POST, true),
            $this->validator->validateFiles($_FILES)
        );

        if (!empty($errors)) {
            $this->render(
                'symposiums.create',
                [
                    'pageTitle' => 'Create Symposium',
                    'old' => $_POST,
                    'errors' => $errors,
                    'departments' => $this->departmentService->getAllDepartments(),
                    'academicYears' => $this->symposiumService->getAcademicYears(),
                    'symposiumTypes' => $this->symposiumService->getAvailableTypes(),
                    'symposiumStatuses' => $this->symposiumService->getAvailableStatuses()
                ]
            );

            return;
        }

        $result = $this->symposiumService->createSymposium(
            $_POST,
            $_FILES,
            $currentUser
        );

        if (!$result['success']) {
            $this->error($result['message']);

            $this->render(
                'symposiums.create',
                [
                    'pageTitle' => 'Create Symposium',
                    'old' => $_POST,
                    'errors' => [],
                    'departments' => $this->departmentService->getAllDepartments(),
                    'academicYears' => $this->symposiumService->getAcademicYears(),
                    'symposiumTypes' => $this->symposiumService->getAvailableTypes(),
                    'symposiumStatuses' => $this->symposiumService->getAvailableStatuses()
                ]
            );

            return;
        }

        $this->success($result['message']);

        $this->redirect('/symposiums');
    }

    public function edit(): void
    {
        $symposiumId = (int) ($_GET['id'] ?? 0);

        $symposium = $this->symposiumService->getSymposiumById($symposiumId);

        if (!$symposium) {
            $this->error('Symposium not found.');
            $this->redirect('/symposiums');
        }

        $currentUser = $this->user();

        if (!$this->symposiumService->canEditSymposium($currentUser, $symposium)) {
            $this->error('You are not authorized to edit this symposium.');
            $this->redirect('/symposiums');
        }

        $this->render(
            'symposiums.edit',
            [
                'pageTitle' => 'Edit Symposium',
                'symposium' => $symposium,
                'departments' => $this->departmentService->getAllDepartments(),
                'academicYears' => $this->symposiumService->getAcademicYears(),
                'symposiumTypes' => $this->symposiumService->getAvailableTypes(),
                'symposiumStatuses' => $this->symposiumService->getAvailableStatuses(),
                'old' => [],
                'errors' => []
            ]
        );
    }

    public function update(): void
    {
        $symposiumId = (int) ($_POST['symposium_id'] ?? 0);

        $symposium = $this->symposiumService->getSymposiumById($symposiumId);

        if (!$symposium) {
            $this->error('Symposium not found.');
            $this->redirect('/symposiums');
        }

        $currentUser = $this->user();

        if (!$this->symposiumService->canEditSymposium($currentUser, $symposium)) {
            $this->error('You are not authorized to update this symposium.');
            $this->redirect('/symposiums');
        }

        $errors = array_merge(
            $this->validator->validate($_POST, false),
            $this->validator->validateFiles($_FILES)
        );

        if (!empty($errors)) {
            $this->render(
                'symposiums.edit',
                [
                    'pageTitle' => 'Edit Symposium',
                    'symposium' => $symposium,
                    'old' => $_POST,
                    'errors' => $errors,
                    'departments' => $this->departmentService->getAllDepartments(),
                    'academicYears' => $this->symposiumService->getAcademicYears(),
                    'symposiumTypes' => $this->symposiumService->getAvailableTypes(),
                    'symposiumStatuses' => $this->symposiumService->getAvailableStatuses()
                ]
            );

            return;
        }

        $result = $this->symposiumService->updateSymposium(
            $symposiumId,
            $_POST,
            $_FILES,
            $currentUser
        );

        if (!$result['success']) {
            $this->error($result['message']);

            $this->render(
                'symposiums.edit',
                [
                    'pageTitle' => 'Edit Symposium',
                    'symposium' => $symposium,
                    'old' => $_POST,
                    'errors' => [],
                    'departments' => $this->departmentService->getAllDepartments(),
                    'academicYears' => $this->symposiumService->getAcademicYears(),
                    'symposiumTypes' => $this->symposiumService->getAvailableTypes(),
                    'symposiumStatuses' => $this->symposiumService->getAvailableStatuses()
                ]
            );

            return;
        }

        $this->success($result['message']);

        $this->redirect('/symposiums');
    }

    public function view(): void
    {
        $symposiumId = (int) ($_GET['id'] ?? 0);

        $symposium = $this->symposiumService->getSymposiumById($symposiumId);

        if (!$symposium) {
            $this->error('Symposium not found.');
            $this->redirect('/symposiums');
        }

        $currentUser = $this->user();

        if (!$this->symposiumService->canViewSymposium($currentUser, $symposium)) {
            $this->error('You are not authorized to view this symposium.');
            $this->redirect('/symposiums');
        }

        $this->render(
            'symposiums.view',
            [
                'pageTitle' => 'View Symposium',
                'symposium' => $symposium,
                'symposiumStatuses' => $this->symposiumService->getAvailableStatuses(),
                'canChangeStatus' => $this->symposiumService->canChangeStatus($currentUser, $symposium)
            ]
        );
    }

    public function delete(): void
    {
        $symposiumId = (int) ($_POST['symposium_id'] ?? 0);

        $currentUser = $this->user();

        $result = $this->symposiumService->deleteSymposium($symposiumId, $currentUser);

        if (!$result['success']) {
            $this->error($result['message']);
            $this->redirect('/symposiums');
        }

        $this->success($result['message']);
        $this->redirect('/symposiums');
    }

    public function changeStatus(): void
    {
        $symposiumId = (int) ($_POST['symposium_id'] ?? 0);
        $status = trim((string) ($_POST['status'] ?? ''));

        $currentUser = $this->user();

        $result = $this->symposiumService->changeStatus($symposiumId, $status, $currentUser);

        if (!$result['success']) {
            $this->error($result['message']);
            $this->redirect('/symposiums/view?id=' . $symposiumId);
        }

        $this->success($result['message']);
        $this->redirect('/symposiums/view?id=' . $symposiumId);
    }
}
