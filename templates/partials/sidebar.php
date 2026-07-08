<?php

declare(strict_types=1);

/**
 * -------------------------------------------------------------------------
 * NexusCore
 * -------------------------------------------------------------------------
 * File        : sidebar.php
 * Location    : templates/partials/
 * Description : Dashboard Sidebar Navigation
 *
 * Responsibilities
 * -------------------------------------------------------------------------
 * • Display application branding
 * • Display logged-in user information
 * • Display navigation menu
 * • Highlight active navigation item
 * • Support role-based navigation
 * • Display logout option
 *
 * NOTE
 * -------------------------------------------------------------------------
 * The navigation menu is generated dynamically from the menu
 * configuration below. Future modules can be added by inserting
 * a new menu item without modifying the HTML structure.
 *
 * Author      : Sugumaran J
 * Project     : NexusCore
 * -------------------------------------------------------------------------
 */

use App\Core\Session;

/*
|--------------------------------------------------------------------------
| Logged-in User
|--------------------------------------------------------------------------
*/

$user = Session::get('user', []);

$currentRole = $user['role'] ?? 'Admin';

/*
|--------------------------------------------------------------------------
| Current Request Path
|--------------------------------------------------------------------------
|
| Used to highlight the active navigation menu item.
|
*/

$currentPath = parse_url(
    $_SERVER['REQUEST_URI'] ?? '/',
    PHP_URL_PATH
) ?? '/';

/*
|--------------------------------------------------------------------------
| Sidebar Menu Configuration
|--------------------------------------------------------------------------
|
| Each menu item contains:
|
| title  -> Display text
| icon   -> Bootstrap Icon
| url    -> Navigation URL
| roles  -> Roles allowed to access the menu
|
*/

$menuItems = [

    [
        'title' => 'Dashboard',
        'icon'  => 'bi-speedometer2',
        'url'   => base_url() . '/dashboard',
        'roles' => ['Admin', 'Principal', 'HOD', 'Staff']
    ],

    [
        'title' => 'Departments',
        'icon'  => 'bi-building',
        'url'   => base_url() . '/departments',
        'roles' => ['Admin']
    ],

    [
        'title' => 'Users',
        'icon'  => 'bi-people',
        'url'   => base_url() . '/users',
        'roles' => ['Admin']
    ],

    [
        'title' => 'Students',
        'icon'  => 'bi-person-vcard',
        'url'   => base_url() . '/students',
        'roles' => ['Admin', 'Principal', 'HOD', 'Staff']
    ],

    [
        'title' => 'Symposiums',
        'icon'  => 'bi-calendar-event',
        'url'   => base_url() . '/symposiums',
        'roles' => ['Admin', 'Principal', 'HOD', 'Staff']
    ],

    [
        'title' => 'Competitions',
        'icon'  => 'bi-trophy',
        'url'   => base_url() . '/competitions',
        'roles' => ['Admin', 'Principal', 'HOD', 'Staff']
    ],

    [
        'title' => 'Certificates',
        'icon'  => 'bi-award',
        'url'   => base_url() . '/certificates',
        'roles' => ['Admin', 'Principal', 'HOD', 'Staff']
    ],

    [
        'title' => 'Reports',
        'icon'  => 'bi-bar-chart',
        'url'   => base_url() . '/reports',
        'roles' => ['Admin', 'Principal', 'HOD']
    ],

    [
        'title' => 'Settings',
        'icon'  => 'bi-gear',
        'url'   => base_url() . '/settings',
        'roles' => ['Admin']
    ]

];

?>

<!-- =============================================================== -->
<!-- Dashboard Sidebar -->
<!-- =============================================================== -->

<aside class="dashboard-sidebar">

    <!-- =========================================================== -->
    <!-- Application Brand -->
    <!-- =========================================================== -->

    <div class="sidebar-brand">

        <i class="bi bi-mortarboard-fill me-2"></i>

        <span>NexusCore</span>

    </div>

    <!-- =========================================================== -->
    <!-- Logged-in User -->
    <!-- =========================================================== -->

    <div class="sidebar-user text-center py-3">

        <div class="avatar-circle mb-3">

            <i class="bi bi-person-fill"></i>

        </div>

        <h6 class="mb-1">

            <?= htmlspecialchars(
                $user['full_name'] ?? 'Administrator',
                ENT_QUOTES,
                'UTF-8'
            ) ?>

        </h6>

        <small class="text-secondary">

            <?= htmlspecialchars(
                $currentRole,
                ENT_QUOTES,
                'UTF-8'
            ) ?>

        </small>

    </div>

    <hr class="sidebar-divider">

    <!-- =========================================================== -->
    <!-- Navigation Menu -->
    <!-- =========================================================== -->

    <ul class="sidebar-menu">

        <?php foreach ($menuItems as $item): ?>

            <?php

            /*
            |--------------------------------------------------------------------------
            | Role Authorization
            |--------------------------------------------------------------------------
            */

            if (!in_array($currentRole, $item['roles'], true)) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Active Menu Detection
            |--------------------------------------------------------------------------
            */

            $itemPath = parse_url(
                $item['url'],
                PHP_URL_PATH
            ) ?? '';

            $isActive =

                $currentPath === $itemPath ||

                str_starts_with(
                    $currentPath,
                    $itemPath . '/'
                );

            ?>

            <li>

                <a
                    href="<?= htmlspecialchars(
                        $item['url'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    class="<?= $isActive ? 'active' : '' ?>">

                    <i class="bi <?= htmlspecialchars(
                        $item['icon'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"></i>

                    <span>

                        <?= htmlspecialchars(
                            $item['title'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </span>

                </a>

            </li>

        <?php endforeach; ?>

    </ul>

    <!-- =========================================================== -->
    <!-- Sidebar Footer -->
    <!-- =========================================================== -->

    <div class="sidebar-footer mt-auto">

        <a
            href="<?= base_url() ?>/logout"
            class="logout-link">

            <i class="bi bi-box-arrow-right"></i>

            <span>Logout</span>

        </a>

    </div>

</aside>