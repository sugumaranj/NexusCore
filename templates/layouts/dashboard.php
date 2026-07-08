<?php

declare(strict_types=1);

/**
 * -------------------------------------------------------------------------
 * NexusCore
 * -------------------------------------------------------------------------
 * File        : dashboard.php
 * Location    : templates/layouts/
 * Description : Master Dashboard Layout
 *
 * This layout is used by all authenticated dashboard pages.
 *
 * It automatically loads:
 * -------------------------------------------------------
 * • Sidebar Navigation
 * • Top Navigation Bar
 * • Page Content
 * • Footer
 * • Bootstrap CSS & JS
 * • Global Application Styles
 * • Dashboard Styles
 *
 * Every dashboard view is rendered inside:
 *      <main class="dashboard-main">
 *
 * Author      : Sugumaran J
 * Project     : NexusCore
 * -------------------------------------------------------------------------
 */

/*
|--------------------------------------------------------------------------
| Default Page Title
|--------------------------------------------------------------------------
|
| If a page does not provide a title, use the application name.
|
*/

$pageTitle = $pageTitle ?? config('name');

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <!-- =============================================================== -->
    <!-- Basic Meta Information -->
    <!-- =============================================================== -->

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <meta
        http-equiv="X-UA-Compatible"
        content="IE=edge">

    <title>

        <?= htmlspecialchars(
            $pageTitle,
            ENT_QUOTES,
            'UTF-8'
        ) ?>

    </title>

    <!-- =============================================================== -->
    <!-- Bootstrap CSS -->
    <!-- =============================================================== -->

    <link
        rel="stylesheet"
        href="<?= asset('assets/css/bootstrap.min.css') ?>">

    <!-- =============================================================== -->
    <!-- Bootstrap Icons -->
    <!-- =============================================================== -->

    <link
        rel="stylesheet"
        href="<?= asset('assets/icons/bootstrap-icons/font/bootstrap-icons.css') ?>">

    <!-- =============================================================== -->
    <!-- Global Application Styles -->
    <!-- =============================================================== -->

    <link
        rel="stylesheet"
        href="<?= asset('assets/css/variables.css') ?>">

    <link
        rel="stylesheet"
        href="<?= asset('assets/css/app.css') ?>">

    <!-- =============================================================== -->
    <!-- Dashboard Specific Styles -->
    <!-- =============================================================== -->

    <link
        rel="stylesheet"
        href="<?= asset('assets/css/dashboard.css') ?>">

</head>

<body class="dashboard-body">

<!-- =============================================================== -->
<!-- Dashboard Wrapper -->
<!-- =============================================================== -->

<div class="dashboard-wrapper">

    <!-- =========================================================== -->
    <!-- Sidebar -->
    <!-- =========================================================== -->

    <?php require dirname(__DIR__) . '/partials/sidebar.php'; ?>

    <!-- =========================================================== -->
    <!-- Dashboard Content Area -->
    <!-- =========================================================== -->

    <div class="dashboard-content">

        <!-- ======================================================= -->
        <!-- Top Navigation -->
        <!-- ======================================================= -->

        <?php require dirname(__DIR__) . '/partials/navbar.php'; ?>

        <!-- ======================================================= -->
        <!-- Main Page Content -->
        <!-- ======================================================= -->

        <main class="dashboard-main">

            <?php require $contentFile; ?>

        </main>

        <!-- ======================================================= -->
        <!-- Footer -->
        <!-- ======================================================= -->

        <?php require dirname(__DIR__) . '/partials/footer.php'; ?>

    </div>

</div>

<!-- =============================================================== -->
<!-- Bootstrap JavaScript Bundle -->
<!-- =============================================================== -->

<script
    src="<?= asset('assets/js/vendor/bootstrap.bundle.min.js') ?>">
</script>

</body>

</html>
