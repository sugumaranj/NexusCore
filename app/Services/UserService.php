<?php

declare(strict_types=1);

/**
 * -------------------------------------------------------------------------
 * NexusCore
 * -------------------------------------------------------------------------
 * File        : UserService.php
 * Location    : app/Services/
 * Description : Business logic for User Management.
 *
 * Responsibilities
 * -------------------------------------------------------------------------
 * • Validate business rules
 * • Prevent duplicate Employee IDs
 * • Prevent duplicate Email addresses
 * • Hash passwords
 * • Call UserModel
 *
 * NOTE
 * -------------------------------------------------------------------------
 * This class NEVER contains SQL.
 * SQL belongs only inside UserModel.
 *
 * Author      : Sugumaran J
 * Project     : NexusCore
 * -------------------------------------------------------------------------
 */

namespace App\Services;

use App\Models\UserModel;

final class UserService
{
    /**
     * User Model.
     *
     * @var UserModel
     */
    private UserModel $userModel;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * ---------------------------------------------------------------------
     * Retrieve all users.
     *
     * @return array
     * ---------------------------------------------------------------------
     */
    public function getAllUsers(): array
    {
        return $this->userModel->getAll();
    }

    /**
     * ---------------------------------------------------------------------
     * Retrieve user by ID.
     *
     * @param int $userId
     *
     * @return array|false
     * ---------------------------------------------------------------------
     */
    public function getUserById(int $userId): array|false
    {
        return $this->userModel->findById($userId);
    }

    /**
     * ---------------------------------------------------------------------
     * Create User.
     *
     * @param array $data
     *
     * @return array
     * ---------------------------------------------------------------------
     */
    public function createUser(array $data): array
    {
        /*
        |--------------------------------------------------------------------------
        | Normalize Input
        |--------------------------------------------------------------------------
        */

        $data['employee_id'] = strtoupper(
            trim($data['employee_id'])
        );

        $data['full_name'] = trim(
            $data['full_name']
        );

        $data['email'] = strtolower(
            trim($data['email'])
        );

        $data['phone'] = trim(
            $data['phone'] ?? ''
        );

        /*
        |--------------------------------------------------------------------------
        | Duplicate Employee ID
        |--------------------------------------------------------------------------
        */

        if (
            $this->userModel->findByEmployeeId(
                $data['employee_id']
            )
        ) {

            return [

                'success' => false,

                'message' => 'Employee ID already exists.'

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Duplicate Email
        |--------------------------------------------------------------------------
        */

        if (
            $this->userModel->findByEmail(
                $data['email']
            )
        ) {

            return [

                'success' => false,

                'message' => 'Email address already exists.'

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Hash Password
        |--------------------------------------------------------------------------
        */

        $data['password_hash'] = password_hash(
            $data['password'],
            PASSWORD_DEFAULT
        );

        unset(
            $data['password'],
            $data['confirm_password']
        );

        /*
        |--------------------------------------------------------------------------
        | Save User
        |--------------------------------------------------------------------------
        */

        $created = $this->userModel->create($data);

        if (!$created) {

            return [

                'success' => false,

                'message' => 'Unable to create user.'

            ];
        }

        return [

            'success' => true,

            'message' => 'User created successfully.'

        ];
    }

    /**
     * ---------------------------------------------------------------------
     * Update User.
     *
     * @param int   $userId
     * @param array $data
     *
     * @return array
     * ---------------------------------------------------------------------
     */
    public function updateUser(
        int $userId,
        array $data
    ): array {

        $updated = $this->userModel->update(
            $userId,
            $data
        );

        if (!$updated) {

            return [

                'success' => false,

                'message' => 'Unable to update user.'

            ];
        }

        return [

            'success' => true,

            'message' => 'User updated successfully.'

        ];
    }

    /**
     * ---------------------------------------------------------------------
     * Delete User.
     *
     * @param int $userId
     *
     * @return array
     * ---------------------------------------------------------------------
     */
    public function deleteUser(int $userId): array
    {
        $deleted = $this->userModel->delete(
            $userId
        );

        if (!$deleted) {

            return [

                'success' => false,

                'message' => 'Unable to delete user.'

            ];
        }

        return [

            'success' => true,

            'message' => 'User deleted successfully.'

        ];
    }

    /**
     * ---------------------------------------------------------------------
     * Change Account Status.
     *
     * @param int    $userId
     * @param string $status
     *
     * @return array
     * ---------------------------------------------------------------------
     */
    public function updateStatus(
        int $userId,
        string $status
    ): array {

        $updated = $this->userModel->updateStatus(
            $userId,
            $status
        );

        if (!$updated) {

            return [

                'success' => false,

                'message' => 'Unable to update account status.'

            ];
        }

        return [

            'success' => true,

            'message' => 'Account status updated successfully.'

        ];
    }

    /**
     * ---------------------------------------------------------------------
     * Reset Password.
     *
     * @param int    $userId
     * @param string $password
     *
     * @return array
     * ---------------------------------------------------------------------
     */
    public function resetPassword(
        int $userId,
        string $password
    ): array {

        $updated = $this->userModel->resetPassword(

            $userId,

            password_hash(
                $password,
                PASSWORD_DEFAULT
            )

        );

        if (!$updated) {

            return [

                'success' => false,

                'message' => 'Unable to reset password.'

            ];
        }

        return [

            'success' => true,

            'message' => 'Password reset successfully.'

        ];
    }
}