<?php

declare(strict_types=1);

/**
 * -------------------------------------------------------------------------
 * NexusCore
 * -------------------------------------------------------------------------
 * File        : edit.php
 * Location    : templates/users/
 * Description : Edit User Form
 *
 * Responsibilities
 * -------------------------------------------------------------------------
 * • Display editable user information
 * • Preserve submitted values after validation failure
 * • Display validation errors
 * • Submit updates to UserController
 *
 * NOTE
 * -------------------------------------------------------------------------
 * Employee ID is immutable and therefore displayed as read-only.
 *
 * Author      : Sugumaran J
 * Project     : NexusCore
 * -------------------------------------------------------------------------
 */

use App\Core\Session;

/*
|--------------------------------------------------------------------------
| Validation Errors
|--------------------------------------------------------------------------
*/

$errors = $errors ?? [];

/*
|--------------------------------------------------------------------------
| Business Rule Error
|--------------------------------------------------------------------------
*/

$error = Session::getFlash('error');

/*
|--------------------------------------------------------------------------
| User Data
|--------------------------------------------------------------------------
*/

$user = $user ?? [];

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">

            Edit User

        </h2>

        <p class="text-muted mb-0">

            Update the selected user's information.

        </p>

    </div>

    <a
        href="<?= base_url() ?>/users"
        class="btn btn-outline-secondary">

        <i class="bi bi-arrow-left-circle me-1"></i>

        Back

    </a>

</div>

<?php if ($error): ?>

<div class="alert alert-danger">

    <?= htmlspecialchars($error) ?>

</div>

<?php endif; ?>

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form
            action="<?= base_url() ?>/users/update"
            method="post"
            enctype="multipart/form-data">

            <input
                type="hidden"
                name="user_id"
                value="<?= (int) $user['user_id'] ?>">

            <div class="row g-4">

                <!-- Employee ID -->

                <div class="col-md-4">

                    <label class="form-label">

                        Employee ID

                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="<?= htmlspecialchars($user['employee_id']) ?>"
                        readonly>

                </div>

                <!-- Full Name -->

                <div class="col-md-8">

                    <label class="form-label">

                        Full Name

                    </label>

                    <input
                        type="text"
                        name="full_name"
                        class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($user['full_name']) ?>">

                    <?php if (isset($errors['full_name'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars($errors['full_name']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <!-- Email -->

                <div class="col-md-6">

                    <label class="form-label">

                        Email Address

                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($user['email']) ?>">

                    <?php if (isset($errors['email'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars($errors['email']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <!-- Phone -->

                <div class="col-md-6">

                    <label class="form-label">

                        Phone Number

                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($user['phone'] ?? '') ?>">

                    <?php if (isset($errors['phone'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars($errors['phone']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <!-- Department -->

                <div class="col-md-6">

                    <label class="form-label">

                        Department

                    </label>

                    <select
                        name="department_id"
                        class="form-select <?= isset($errors['department_id']) ? 'is-invalid' : '' ?>">

                        <option value="">Select Department</option>

                        <?php foreach ($departments as $department): ?>

                            <option
                                value="<?= $department['department_id'] ?>"
                                <?= ((string) ($user['department_id'] ?? '') === (string) $department['department_id']) ? 'selected' : '' ?>>

                                <?= htmlspecialchars($department['department_name']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Role -->

                <div class="col-md-6">

                    <label class="form-label">

                        Role

                    </label>

                    <select
                        name="role"
                        class="form-select <?= isset($errors['role']) ? 'is-invalid' : '' ?>">

                        <?php foreach (['Admin','Principal','HOD','Staff'] as $role): ?>

                            <option
                                value="<?= $role ?>"
                                <?= ($user['role'] === $role) ? 'selected' : '' ?>>

                                <?= $role ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Profile Photo -->

                <div class="col-md-6">

                    <label class="form-label">

                        Replace Profile Photo

                    </label>

                    <input
                        type="file"
                        name="profile_photo"
                        class="form-control">

                </div>

                <!-- Signature -->

                <div class="col-md-6">

                    <label class="form-label">

                        Replace Signature

                    </label>

                    <input
                        type="file"
                        name="signature_path"
                        class="form-control">

                </div>

                <!-- Account Status -->

                <div class="col-md-6">

                    <label class="form-label">

                        Account Status

                    </label>

                    <select
                        name="account_status"
                        class="form-select">

                        <?php foreach (['Active','Inactive','Blocked'] as $status): ?>

                            <option
                                value="<?= $status ?>"
                                <?= ($user['account_status'] === $status) ? 'selected' : '' ?>>

                                <?= $status ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

            </div>

            <div class="mt-5 d-flex gap-2">

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="bi bi-check-circle me-1"></i>

                    Update User

                </button>

                <a
                    href="<?= base_url() ?>/users"
                    class="btn btn-secondary">

                    Cancel

                </a>

            </div>

        </form>

    </div>

</div>