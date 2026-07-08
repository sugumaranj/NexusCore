<?php

declare(strict_types=1);

/**
 * -------------------------------------------------------------------------
 * NexusCore
 * -------------------------------------------------------------------------
 * File        : AuthService.php
 * Location    : app/Services/
 * Description : Handles user authentication.
 *
 * Author      : Sugumaran J
 * Project     : NexusCore
 * -------------------------------------------------------------------------
 */

namespace App\Services;

use App\Core\Session;
use App\Models\UserModel;

final class AuthService
{
    /**
     * User model instance.
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
     * Authenticate a user.
     *
     * @param string $email
     * @param string $password
     *
     * @return bool
     * ---------------------------------------------------------------------
     */
    public function login(string $email, string $password): bool
    {
        $user = $this->userModel->findByEmail($email);

        // User not found
        if ($user === null) {
            return false;
        }

        // Account inactive
        if (!$this->userModel->isActive($user)) {
            return false;
        }

        // Password verification
        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        // Regenerate session ID
        Session::regenerate();

        // Store user information
        Session::set('user', [
            'user_id'       => $user['user_id'],
            'employee_id'   => $user['employee_id'],
            'department_id' => $user['department_id'],
            'full_name'     => $user['full_name'],
            'email'         => $user['email'],
            'role'          => $user['role']
        ]);

        // Update last login
        $this->userModel->updateLastLogin(
            (int) $user['user_id']
        );

        return true;
    }

    /**
     * ---------------------------------------------------------------------
     * Log out the current user.
     *
     * @return void
     * ---------------------------------------------------------------------
     */
    public function logout(): void
    {
        Session::destroy();
    }

    /**
     * ---------------------------------------------------------------------
     * Check whether a user is authenticated.
     *
     * @return bool
     * ---------------------------------------------------------------------
     */
    public function check(): bool
    {
        return Session::has('user');
    }

    /**
     * ---------------------------------------------------------------------
     * Get the currently authenticated user.
     *
     * @return array|null
     * ---------------------------------------------------------------------
     */
    public function user(): ?array
    {
        return Session::get('user');
    }
}