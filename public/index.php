<?php

declare(strict_types=1);

/**
 * -------------------------------------------------------------------------
 * NexusCore
 * -------------------------------------------------------------------------
 * File        : index.php
 * Location    : public/
 * Description : Front Controller
 *
 * Every HTTP request enters the application through this file.
 * It bootstraps the application, registers routes and dispatches
 * the incoming request.
 *
 * Author      : Sugumaran J
 * Project     : NexusCore
 * -------------------------------------------------------------------------
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\DepartmentController;
use App\Controllers\HomeController;
use App\Controllers\ModuleController;
use App\Controllers\StudentController;
use App\Controllers\SymposiumController;
use App\Controllers\UserController;

use App\Core\Application;
use App\Core\Bootstrap;
use App\Core\Router;

/*
|--------------------------------------------------------------------------
| Bootstrap Application
|--------------------------------------------------------------------------
|
| Load environment variables and initialize the application.
|
*/

Bootstrap::loadEnvironment(dirname(__DIR__));

$app = new Application();

/*
|--------------------------------------------------------------------------
| Create Router
|--------------------------------------------------------------------------
*/

$router = new Router();

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
|
| These routes are accessible without authentication.
|
*/

// Landing Page
$router->get(
    '/',
    [HomeController::class, 'index']
);

// Login Page
$router->get(
    '/login',
    [AuthController::class, 'showLogin']
);

// Login Form Submission
$router->post(
    '/login',
    [AuthController::class, 'login']
);

// Logout
$router->get(
    '/logout',
    [AuthController::class, 'logout']
);

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Dashboard routing based on authenticated user roles.
|
*/

// Redirect user to appropriate dashboard
$router->get(
    '/dashboard',
    [DashboardController::class, 'index']
);

// Administrator Dashboard
$router->get(
    '/dashboard/admin',
    [DashboardController::class, 'admin']
);

// Principal Dashboard
$router->get(
    '/dashboard/principal',
    [DashboardController::class, 'principal']
);

// Head of Department Dashboard
$router->get(
    '/dashboard/hod',
    [DashboardController::class, 'hod']
);

// Staff Coordinator Dashboard
$router->get(
    '/dashboard/staff',
    [DashboardController::class, 'staff']
);

/*
|--------------------------------------------------------------------------
| Department Management Routes
|--------------------------------------------------------------------------
*/

// List Departments
$router->get(
    '/departments',
    [DepartmentController::class, 'index']
);

// Display Create Department Form
$router->get(
    '/departments/create',
    [DepartmentController::class, 'create']
);

// Store Department
$router->post(
    '/departments',
    [DepartmentController::class, 'store']
);

// Display Edit Department Form
$router->get(
    '/departments/edit',
    [DepartmentController::class, 'edit']
);

// Update Department
$router->post(
    '/departments/update',
    [DepartmentController::class, 'update']
);

// Delete Department
$router->post(
    '/departments/delete',
    [DepartmentController::class, 'delete']
);

/*
|--------------------------------------------------------------------------
| User Management Routes
|--------------------------------------------------------------------------
*/

// List Users
$router->get(
    '/users',
    [UserController::class, 'index']
);

// Display Create User Form
$router->get(
    '/users/create',
    [UserController::class, 'create']
);

// Store User
$router->post(
    '/users/store',
    [UserController::class, 'store']
);

// Display Edit User Form
$router->get(
    '/users/edit',
    [UserController::class, 'edit']
);

// Update User
$router->post(
    '/users/update',
    [UserController::class, 'update']
);

// View User Details
$router->get(
    '/users/view',
    [UserController::class, 'view']
);

// Delete User
$router->post(
    '/users/delete',
    [UserController::class, 'delete']
);

// Change User Account Status
$router->post(
    '/users/change-status',
    [UserController::class, 'changeStatus']
);

// Reset User Password
$router->post(
    '/users/reset-password',
    [UserController::class, 'resetPassword']
);

/*
|--------------------------------------------------------------------------
| Placeholder Module Routes
|--------------------------------------------------------------------------
|
| These modules are currently under development.
| They display placeholder pages until implementation.
|
*/

// Student Management
$router->get(
    '/students',
    [StudentController::class, 'index']
);

// Display Create Student Form
$router->get(
    '/students/create',
    [StudentController::class, 'create']
);

// Store Student
$router->post(
    '/students/store',
    [StudentController::class, 'store']
);

// Display Edit Student Form
$router->get(
    '/students/edit',
    [StudentController::class, 'edit']
);

// Update Student
$router->post(
    '/students/update',
    [StudentController::class, 'update']
);

// View Student Details
$router->get(
    '/students/view',
    [StudentController::class, 'view']
);

// Delete Student
$router->post(
    '/students/delete',
    [StudentController::class, 'delete']
);

// Symposium Management
$router->get(
    '/symposiums',
    [SymposiumController::class, 'index']
);

// Display Create Symposium Form
$router->get(
    '/symposiums/create',
    [SymposiumController::class, 'create']
);

// Store Symposium
$router->post(
    '/symposiums/store',
    [SymposiumController::class, 'store']
);

// Display Edit Symposium Form
$router->get(
    '/symposiums/edit',
    [SymposiumController::class, 'edit']
);

// Update Symposium
$router->post(
    '/symposiums/update',
    [SymposiumController::class, 'update']
);

// View Symposium Details
$router->get(
    '/symposiums/view',
    [SymposiumController::class, 'view']
);

// Delete Symposium
$router->post(
    '/symposiums/delete',
    [SymposiumController::class, 'delete']
);

// Change Symposium Status
$router->post(
    '/symposiums/change-status',
    [SymposiumController::class, 'changeStatus']
);

// Competition Management
$router->get(
    '/competitions',
    [ModuleController::class, 'competitions']
);

// Certificate Management
$router->get(
    '/certificates',
    [ModuleController::class, 'certificates']
);

// Reports
$router->get(
    '/reports',
    [ModuleController::class, 'reports']
);

// System Settings
$router->get(
    '/settings',
    [ModuleController::class, 'settings']
);

/*
|--------------------------------------------------------------------------
| Dispatch Request
|--------------------------------------------------------------------------
|
| Match the incoming request with the registered routes
| and execute the appropriate controller action.
|
*/

$router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);