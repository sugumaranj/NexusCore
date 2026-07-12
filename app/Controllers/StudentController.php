<?php

declare(strict_types=1);

/**
 * -------------------------------------------------------------------------
 * NexusCore
 * -------------------------------------------------------------------------
 * File        : StudentController.php
 * Location    : app/Controllers/
 * Description : Handles all Student Management HTTP requests.
 *
 * Responsibilities
 * -------------------------------------------------------------------------
 * • Display student list
 * • Display create student form
 * • Validate student input
 * • Create student
 * • Edit student
 * • Update student
 * • Delete student
 * • View student details
 *
 * NOTE
 * -------------------------------------------------------------------------
 * • Controller NEVER contains SQL.
 * • Business logic belongs to StudentService.
 * • Validation belongs to StudentValidator.
 * • Database operations belong to StudentModel.
 *
 * Author      : Sugumaran J
 * Project     : NexusCore
 * -------------------------------------------------------------------------
 */

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Services\DepartmentService;
use App\Services\StudentService;
use App\Validators\StudentValidator;

final class StudentController extends BaseController
{
    /**
     * Student Service.
     *
     * @var StudentService
     */
    private StudentService $studentService;

    /**
     * Student Validator.
     *
     * @var StudentValidator
     */
    private StudentValidator $validator;

    /**
     * Department Service.
     *
     * @var DepartmentService
     */
    private DepartmentService $departmentService;

    /**
     * ---------------------------------------------------------------------
     * Constructor.
     *
     * Protect all student pages and initialize required services.
     * ---------------------------------------------------------------------
     */
    public function __construct()
    {
        AuthMiddleware::handle();

        $this->studentService = new StudentService();

        $this->validator = new StudentValidator();

        $this->departmentService = new DepartmentService();
    }

    /**
     * ---------------------------------------------------------------------
     * Display Student List.
     * ---------------------------------------------------------------------
     */
    public function index(): void
    {
        $search = trim((string) ($_GET['search'] ?? ''));

        $departmentId = trim((string) ($_GET['department_id'] ?? ''));

        $academicYear = trim((string) ($_GET['academic_year'] ?? ''));

        $semester = trim((string) ($_GET['semester'] ?? ''));

        $status = trim((string) ($_GET['status'] ?? ''));

        $students = $this->studentService->searchStudents(
            $search,
            $departmentId,
            $academicYear,
            $semester,
            $status
        );

        $academicYears = [];

        foreach ($students as $student) {
            if (!empty($student['academic_year'])) {
                $academicYears[] = (string) $student['academic_year'];
            }
        }

        $academicYears = array_values(array_unique($academicYears));

        sort($academicYears);

        $semesters = [];

        foreach ($students as $student) {
            if (!empty($student['semester'])) {
                $semesters[] = (string) $student['semester'];
            }
        }

        $semesters = array_values(array_unique($semesters));

        sort($semesters);

        $totalStudents = $this->studentService->countAllStudents();
        $activeStudents = $this->studentService->countActiveStudents();
        $inactiveStudents = $this->studentService->countInactiveStudents();

        $this->render(
            'students.index',
            [
                'pageTitle' => 'Student Management',
                'students' => $students,
                'search' => $search,
                'department_id' => $departmentId,
                'academic_year' => $academicYear,
                'semester' => $semester,
                'status' => $status,
                'departments' => $this->departmentService->getAllDepartments(),
                'academicYears' => $academicYears,
                'semesters' => $semesters,
                'totalStudents' => $totalStudents,
                'activeStudents' => $activeStudents,
                'inactiveStudents' => $inactiveStudents
            ]
        );
    }

    /**
     * ---------------------------------------------------------------------
     * Display Create Student Form.
     * ---------------------------------------------------------------------
     */
    public function create(): void
    {
        $this->render(
            'students.create',
            [
                'pageTitle' => 'Create Student',
                'departments' => $this->departmentService->getAllDepartments()
            ]
        );
    }

    /**
     * ---------------------------------------------------------------------
     * Store Student.
     * ---------------------------------------------------------------------
     */
    public function store(): void
    {
        $errors = array_merge(
            $this->validator->validate($_POST),
            $this->validator->validateFiles($_FILES)
        );

        if (!empty($errors)) {
            $this->render(
                'students.create',
                [
                    'pageTitle' => 'Create Student',
                    'old' => $_POST,
                    'errors' => $errors,
                    'departments' => $this->departmentService->getAllDepartments()
                ]
            );

            return;
        }

        $result = $this->studentService->createStudent(
            $_POST,
            $_FILES
        );

        if (!$result['success']) {
            $this->error($result['message']);

            $this->render(
                'students.create',
                [
                    'pageTitle' => 'Create Student',
                    'old' => $_POST,
                    'errors' => [],
                    'departments' => $this->departmentService->getAllDepartments()
                ]
            );

            return;
        }

        $this->success($result['message']);

        $this->redirect('/students');
    }

    /**
     * ---------------------------------------------------------------------
     * Display Edit Student Form.
     * ---------------------------------------------------------------------
     */
    public function edit(): void
    {
        $studentId = (int) ($_GET['id'] ?? 0);

        $student = $this->studentService->getStudentById($studentId);

        if (!$student) {
            $this->error('Student not found.');
            $this->redirect('/students');
        }

        $this->render(
            'students.edit',
            [
                'pageTitle' => 'Edit Student',
                'student' => $student,
                'departments' => $this->departmentService->getAllDepartments()
            ]
        );
    }

    /**
     * ---------------------------------------------------------------------
     * Update Student.
     * ---------------------------------------------------------------------
     */
    public function update(): void
    {
        $studentId = (int) ($_POST['student_id'] ?? 0);

        $originalStudent = $this->studentService->getStudentById($studentId);

        if (!$originalStudent) {
            $this->error('Student not found.');
            $this->redirect('/students');
        }

        $mergedStudent = array_merge($originalStudent, $_POST);

        $mergedStudent['register_number'] = (string) ($originalStudent['register_number'] ?? '');

        $errors = array_merge(
            $this->validator->validate($mergedStudent),
            $this->validator->validateFiles($_FILES)
        );

        if (!empty($errors)) {
            $this->render(
                'students.edit',
                [
                    'pageTitle' => 'Edit Student',
                    'errors' => $errors,
                    'student' => $mergedStudent,
                    'departments' => $this->departmentService->getAllDepartments()
                ]
            );

            return;
        }

        $result = $this->studentService->updateStudent(
            $studentId,
            $mergedStudent,
            $_FILES
        );

        if (!$result['success']) {
            $this->error($result['message']);

            $this->render(
                'students.edit',
                [
                    'pageTitle' => 'Edit Student',
                    'errors' => [],
                    'student' => $mergedStudent,
                    'departments' => $this->departmentService->getAllDepartments()
                ]
            );

            return;
        }

        $this->success($result['message']);

        $this->redirect('/students');
    }

    /**
     * ---------------------------------------------------------------------
     * View Student Details.
     * ---------------------------------------------------------------------
     */
    public function view(): void
    {
        $studentId = (int) ($_GET['id'] ?? 0);

        $student = $this->studentService->getStudentById($studentId);

        if (!$student) {
            $this->error('Student not found.');
            $this->redirect('/students');
        }

        $this->render(
            'students.view',
            [
                'pageTitle' => 'Student Details',
                'student' => $student
            ]
        );
    }

    /**
     * ---------------------------------------------------------------------
     * Delete Student.
     * ---------------------------------------------------------------------
     */
    public function delete(): void
    {
        $studentId = (int) ($_POST['student_id'] ?? 0);

        $result = $this->studentService->deleteStudent($studentId);

        if (!$result['success']) {
            $this->error($result['message']);
            $this->redirect('/students');
        }

        $this->success($result['message']);

        $this->redirect('/students');
    }
}
