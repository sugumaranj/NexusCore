<?php

declare(strict_types=1);

/**
 * -------------------------------------------------------------------------
 * NexusCore
 * -------------------------------------------------------------------------
 * File        : admin.php
 * Location    : templates/dashboard/
 * Description : Administrator Dashboard
 *
 * This file contains ONLY the dashboard page content.
 * Sidebar, Navbar and Footer are loaded automatically
 * from templates/layouts/dashboard.php.
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

$user = Session::get('user');

?>

<!-- ============================================================= -->
<!-- Page Header -->
<!-- ============================================================= -->

<div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

    <div>

        <h1 class="fw-bold mb-1">

            Welcome,
            <?= htmlspecialchars($user['full_name'] ?? 'Administrator') ?>

        </h1>

        <p class="text-muted mb-0">

            Administrator Dashboard

        </p>

    </div>

</div>

<!-- ============================================================= -->
<!-- Dashboard Statistics -->
<!-- ============================================================= -->

<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">

    <!-- Departments -->

    <div class="col">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">

                            Total Departments

                        </small>

                        <h2 class="fw-bold mt-2">

                            0

                        </h2>

                    </div>

                    <div
                        class="bg-primary bg-opacity-10 rounded-circle p-3">

                        <i
                            class="bi bi-building text-primary fs-2"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Students -->

    <div class="col">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">

                            Total Students

                        </small>

                        <h2 class="fw-bold mt-2">

                            0

                        </h2>

                    </div>

                    <div
                        class="bg-success bg-opacity-10 rounded-circle p-3">

                        <i
                            class="bi bi-people-fill text-success fs-2"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Symposiums -->

    <div class="col">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">

                            Total Symposiums

                        </small>

                        <h2 class="fw-bold mt-2">

                            0

                        </h2>

                    </div>

                    <div
                        class="bg-warning bg-opacity-10 rounded-circle p-3">

                        <i
                            class="bi bi-calendar-event text-warning fs-2"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Competitions -->

    <div class="col">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">

                            Total Competitions

                        </small>

                        <h2 class="fw-bold mt-2">

                            0

                        </h2>

                    </div>

                    <div
                        class="bg-danger bg-opacity-10 rounded-circle p-3">

                        <i
                            class="bi bi-trophy-fill text-danger fs-2"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- ============================================================= -->
<!-- Quick Actions -->
<!-- ============================================================= -->

<div class="card shadow-sm border-0 mt-5">

    <div class="card-header bg-white">

        <h5 class="mb-0">

            Quick Actions

        </h5>

    </div>

    <div class="card-body">

        <div class="row g-3">

            <!-- Add Department -->

            <div class="col-xl-auto col-lg-auto col-md-6 col-sm-12">

                <a
                    href="<?= base_url() ?>/departments/create"
                    class="btn btn-primary w-100">

                    <i class="bi bi-building-add me-2"></i>

                    Add Department

                </a>

            </div>

            <!-- Add User -->

            <div class="col-xl-auto col-lg-auto col-md-6 col-sm-12">

                <a
                    href="<?= base_url() ?>/users"
                    class="btn btn-success w-100">

                    <i class="bi bi-person-plus-fill me-2"></i>

                    Add User

                </a>

            </div>

            <!-- Create Symposium -->

            <div class="col-xl-auto col-lg-auto col-md-6 col-sm-12">

                <a
                    href="<?= base_url() ?>/symposiums"
                    class="btn btn-warning text-dark w-100">

                    <i class="bi bi-calendar-plus me-2"></i>

                    Create Symposium

                </a>

            </div>

            <!-- Reports -->

            <div class="col-xl-auto col-lg-auto col-md-6 col-sm-12">

                <a
                    href="<?= base_url() ?>/reports"
                    class="btn btn-info text-white w-100">

                    <i class="bi bi-bar-chart-line-fill me-2"></i>

                    View Reports

                </a>

            </div>

        </div>

    </div>

</div>

<!-- ============================================================= -->
<!-- Recent Activity (Placeholder)
<!-- ============================================================= -->

<div class="card shadow-sm border-0 mt-5">

    <div class="card-header bg-white">

        <h5 class="mb-0">

            Recent Activity

        </h5>

    </div>

    <div class="card-body text-center text-muted py-5">

        <i class="bi bi-clock-history fs-1 mb-3"></i>

        <p class="mb-0">

            No recent activities available.

        </p>

    </div>

</div>
