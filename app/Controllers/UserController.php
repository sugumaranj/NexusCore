<?php

declare(strict_types=1);

/**
 * -------------------------------------------------------------------------
 * NexusCore
 * -------------------------------------------------------------------------
 * File        : UserController.php
 * Location    : app/Controllers/
 * Description : Handles all User Management HTTP requests.
 *
 * Responsibilities
 * -------------------------------------------------------------------------
 * • Display user list
 * • Display create user form
 * • Validate user input
 * • Create user
 * • Edit user
 * • Update user
 * • Delete user
 * • Reset password
 * • Change account status
 *
 * NOTE
 * -------------------------------------------------------------------------
 * • Controller NEVER contains SQL.
 * • Business logic belongs to UserService.
 * * Validation belongs to UserValidator.
 * • Database operations belong to UserModel.
 *
 * Author      : Sugumaran J
 * Project     : NexusCore
 * -------------------------------------------------------------------------
 */

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Services\UserService;
use App\Validators\UserValidator;
use App\Services\DepartmentService;

final class UserController extends BaseController
{
    /**
     * User Service.
     *
     * @var UserService
     */
    private UserService $userService;

    /**
     * User Validator.
     *
     * @var UserValidator
     */
    private UserValidator $validator;

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
    * Protect all User Management pages and initialize required services.
    * ---------------------------------------------------------------------
    */
    public function __construct()
    {
        AuthMiddleware::handle();

        $this->userService = new UserService();

        $this->validator = new UserValidator();

        $this->departmentService = new DepartmentService();
    }

    /**
     * ---------------------------------------------------------------------
     * Display User List.
     * ---------------------------------------------------------------------
     */
    public function index(): void
    {
        $users = $this->userService->getAllUsers();

        $this->render(
            'users.index',
            [
                'pageTitle' => 'User Management',
                'users'     => $users
            ]
        );
    }

    /**
    * ---------------------------------------------------------------------
    * Display Create User Form.
    * ---------------------------------------------------------------------
    */
    public function create(): void
    {
        $this->render(
            'users.create',
            [
                'pageTitle'   => 'Create User',

                'departments' => $this
                    ->departmentService
                    ->getAllDepartments()
            ]
        );
    }

    /**
     * ---------------------------------------------------------------------
     * Store User.
     * ---------------------------------------------------------------------
     */
    public function store(): void
    {
        $errors = $this->validator->validate($_POST);

        if (!empty($errors)) {

            $this->render(
                'users.create',
                [
                    'pageTitle'   => 'Create User',

                    'old'         => $_POST,

                    'departments' => $this
                        ->departmentService
                        ->getAllDepartments()
                ]
        );

            return;
        }

        $result = $this->userService->createUser($_POST);

        if (!$result['success']) {

            $this->error($result['message']);

            $this->render(
                'users.create',
                [
                    'pageTitle' => 'Create User',
                    'old'       => $_POST
                ]
            );

            return;
        }

        $this->success($result['message']);

        $this->redirect('/users');
    }

    /**
     * ---------------------------------------------------------------------
     * Display Edit User Form.
     * ---------------------------------------------------------------------
     */
    public function edit(): void
    {
        $userId = (int) ($_GET['id'] ?? 0);

        $user = $this->userService->getUserById($userId);

        if (!$user) {

            $this->error('User not found.');

            $this->redirect('/users');
        }

        $this->render(
            'users.edit',
            [
                'pageTitle'   => 'Edit User',

                'user'        => $user,

                'departments' => $this
                    ->departmentService
                    ->getAllDepartments()
            ]
        );
    }

    /**
     * ---------------------------------------------------------------------
     * Update User.
     * ---------------------------------------------------------------------
     */
    public function update(): void
    {
        $userId = (int) ($_POST['user_id'] ?? 0);

        $errors = $this->validator->validate($_POST);

        if (!empty($errors)) {

            $this->render(
                'users.edit',
                 [
                    'pageTitle'   => 'Edit User',

                    'errors'      => $errors,

                    'user'        => $_POST,

                    'departments' => $this
                        ->departmentService
                        ->getAllDepartments()
                ]
            );

            return;
        }

        $result = $this->userService->updateUser(
            $userId,
            $_POST
        );

        if (!$result['success']) {

            $this->error($result['message']);

            $this->redirect('/users');
        }

        $this->success($result['message']);

        $this->redirect('/users');
    }

    /**
     * ---------------------------------------------------------------------
     * View User Details.
     * ---------------------------------------------------------------------
     */
    public function view(): void
    {
        $userId = (int) ($_GET['id'] ?? 0);

        $user = $this->userService->getUserById($userId);

        if (!$user) {

            $this->error('User not found.');

            $this->redirect('/users');
        }

        $this->render(
            'users.view',
            [
                'pageTitle' => 'User Details',
                'user'      => $user
            ]
        );
    }

    /**
     * ---------------------------------------------------------------------
     * Delete User.
     * ---------------------------------------------------------------------
     */
    public function delete(): void
    {
        $userId = (int) ($_POST['user_id'] ?? 0);

        $result = $this->userService->deleteUser($userId);

        if (!$result['success']) {

            $this->error($result['message']);

            $this->redirect('/users');
        }

        $this->success($result['message']);

        $this->redirect('/users');
    }

    /**
     * ---------------------------------------------------------------------
     * Update User Account Status.
     * ---------------------------------------------------------------------
     */
    public function changeStatus(): void
    {
        $userId = (int) ($_POST['user_id'] ?? 0);

        $status = trim($_POST['account_status'] ?? '');

        $result = $this->userService->updateStatus(
            $userId,
            $status
        );

        if (!$result['success']) {

            $this->error($result['message']);

            $this->redirect('/users');
        }

        $this->success($result['message']);

        $this->redirect('/users');
    }

    /**
     * ---------------------------------------------------------------------
     * Reset User Password.
     * ---------------------------------------------------------------------
     */
    public function resetPassword(): void
    {
        $userId = (int) ($_POST['user_id'] ?? 0);

        $password = $_POST['password'] ?? '';

        $result = $this->userService->resetPassword(
            $userId,
            $password
        );

        if (!$result['success']) {

            $this->error($result['message']);

            $this->redirect('/users');
        }

        $this->success($result['message']);

        $this->redirect('/users');
    }
}