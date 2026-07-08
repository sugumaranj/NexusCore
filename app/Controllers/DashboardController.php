<?php

declare(strict_types=1);

/**
 * -------------------------------------------------------------------------
 * NexusCore
 * -------------------------------------------------------------------------
 * File        : DashboardController.php
 * Location    : app/Controllers/
 * Description : Handles all authenticated dashboard pages.
 *
 * Responsibilities
 * -------------------------------------------------------------------------
 * • Redirect authenticated users to their role-specific dashboard.
 * • Render Administrator dashboard.
 * • Render Principal dashboard.
 * • Render HOD dashboard.
 * • Render Staff Coordinator dashboard.
 *
 * NOTE
 * -------------------------------------------------------------------------
 * This controller extends BaseController to reuse:
 * • View rendering
 * • Redirect helper
 * • Authentication helper
 * • Flash messages
 *
 * Author      : Sugumaran J
 * Project     : NexusCore
 * -------------------------------------------------------------------------
 */

namespace App\Controllers;

use App\Core\Session;
use App\Middleware\AuthMiddleware;

final class DashboardController extends BaseController
{
    /**
     * ---------------------------------------------------------------------
     * Redirect user to the appropriate dashboard.
     *
     * This method determines the logged-in user's role and redirects
     * them to the corresponding dashboard.
     *
     * @return never
     * ---------------------------------------------------------------------
     */
    public function index(): never
    {
        AuthMiddleware::handle();

        $user = Session::get('user', []);

        $target = match ($user['role'] ?? '') {

            'Admin'      => '/dashboard/admin',

            'Principal'  => '/dashboard/principal',

            'HOD'        => '/dashboard/hod',

            'Staff'      => '/dashboard/staff',

            default      => '/dashboard/admin',

        };

        $this->redirect($target);
    }

    /**
     * ---------------------------------------------------------------------
     * Administrator Dashboard
     *
     * @return void
     * ---------------------------------------------------------------------
     */
    public function admin(): void
    {
        AuthMiddleware::handle();

        $this->render(
            'dashboard.admin',
            [
                'pageTitle' => 'Administrator Dashboard',
                'user'      => Session::get('user')
            ]
        );
    }

    /**
     * ---------------------------------------------------------------------
     * Principal Dashboard
     *
     * @return void
     * ---------------------------------------------------------------------
     */
    public function principal(): void
    {
        AuthMiddleware::handle();

        $this->render(
            'dashboard.principal',
            [
                'pageTitle' => 'Principal Dashboard',
                'user'      => Session::get('user')
            ]
        );
    }

    /**
     * ---------------------------------------------------------------------
     * HOD Dashboard
     *
     * @return void
     * ---------------------------------------------------------------------
     */
    public function hod(): void
    {
        AuthMiddleware::handle();

        $this->render(
            'dashboard.hod',
            [
                'pageTitle' => 'HOD Dashboard',
                'user'      => Session::get('user')
            ]
        );
    }

    /**
     * ---------------------------------------------------------------------
     * Staff Coordinator Dashboard
     *
     * @return void
     * ---------------------------------------------------------------------
     */
    public function staff(): void
    {
        AuthMiddleware::handle();

        $this->render(
            'dashboard.staff',
            [
                'pageTitle' => 'Staff Coordinator Dashboard',
                'user'      => Session::get('user')
            ]
        );
    }
}