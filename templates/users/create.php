<?php

declare(strict_types=1);

/**
 * -------------------------------------------------------------------------
 * NexusCore
 * -------------------------------------------------------------------------
 * File        : create.php
 * Location    : templates/users/
 * Description : Create User Form
 *
 * Responsibilities
 * -------------------------------------------------------------------------
 * • Display user creation form
 * • Display validation errors
 * • Preserve previously entered values
 * • Submit data to UserController
 *
 * Author      : Sugumaran J
 * Project     : NexusCore
 * -------------------------------------------------------------------------
 */

use App\Core\Session;

/*
|--------------------------------------------------------------------------
| Old Input
|--------------------------------------------------------------------------
*/

$old = $old ?? [];

/*
|--------------------------------------------------------------------------
| Validation Errors
|--------------------------------------------------------------------------
*/

$errors = $errors ?? [];

/*
|--------------------------------------------------------------------------
| Flash Error
|--------------------------------------------------------------------------
*/

$error = Session::getFlash('error');

?>

<!-- =============================================================== -->
<!-- Page Header -->
<!-- =============================================================== -->

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">

            Create User

        </h2>

        <p class="text-muted mb-0">

            Create a new staff account for NexusCore.

        </p>

    </div>

    <a
        href="<?= base_url() ?>/users"
        class="btn btn-outline-secondary">

        <i class="bi bi-arrow-left-circle me-1"></i>

        Back

    </a>

</div>

<!-- =============================================================== -->
<!-- Flash Error -->
<!-- =============================================================== -->

<?php if ($error): ?>

<div class="alert alert-danger">

    <?= htmlspecialchars($error) ?>

</div>

<?php endif; ?>

<!-- =============================================================== -->
<!-- Validation Errors -->
<!-- =============================================================== -->

<?php if (!empty($errors)): ?>

<div class="alert alert-danger">

    <strong>

        Please correct the following errors.

    </strong>

</div>

<?php endif; ?>

<!-- =============================================================== -->
<!-- Create User Form -->
<!-- =============================================================== -->

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form
            method="post"
            action="<?= base_url() ?>/users/store"
            enctype="multipart/form-data">

            <div class="row g-4">

                <!-- Employee ID -->

                <div class="col-md-4">

                    <label class="form-label">

                        Employee ID

                    </label>

                    <input
                        type="text"
                        name="employee_id"
                        class="form-control <?= isset($errors['employee_id']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($old['employee_id'] ?? '') ?>">

                    <?php if (isset($errors['employee_id'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars($errors['employee_id']) ?>

                        </div>

                    <?php endif; ?>

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
                        value="<?= htmlspecialchars($old['full_name'] ?? '') ?>">

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
                        value="<?= htmlspecialchars($old['email'] ?? '') ?>">

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
                        value="<?= htmlspecialchars($old['phone'] ?? '') ?>">

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

                        <option value="">

                            Select Department

                        </option>

                        <?php foreach ($departments as $department): ?>

                            <option
                                value="<?= $department['department_id'] ?>"
                                <?= (($old['department_id'] ?? '') == $department['department_id']) ? 'selected' : '' ?>>

                                <?= htmlspecialchars($department['department_name']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <?php if (isset($errors['department_id'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars($errors['department_id']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <!-- Role -->

                <div class="col-md-6">

                    <label class="form-label">

                        Role

                    </label>

                    <select
                        name="role"
                        class="form-select <?= isset($errors['role']) ? 'is-invalid' : '' ?>">

                        <?php

                        $roles = [

                            'Admin',

                            'Principal',

                            'HOD',

                            'Staff'

                        ];

                        ?>

                        <option value="">

                            Select Role

                        </option>

                        <?php foreach ($roles as $role): ?>

                            <option
                                value="<?= $role ?>"
                                <?= (($old['role'] ?? '') === $role) ? 'selected' : '' ?>>

                                <?= $role ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <?php if (isset($errors['role'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars($errors['role']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <!-- Password -->

                <div class="col-md-6">

                    <label class="form-label">

                        Password

                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>">

                    <?php if (isset($errors['password'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars($errors['password']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <!-- Confirm Password -->

                <div class="col-md-6">

                    <label class="form-label">

                        Confirm Password

                    </label>

                    <input
                        type="password"
                        name="confirm_password"
                        class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>">

                    <?php if (isset($errors['confirm_password'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars($errors['confirm_password']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <!-- Profile Photo -->

                <div class="col-md-6">

                    <label class="form-label">

                        Profile Photo

                    </label>

                    <input
                        type="file"
                        name="profile_photo"
                        class="form-control <?= isset($errors['profile_photo']) ? 'is-invalid' : '' ?>">

                    <?php if (isset($errors['profile_photo'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars($errors['profile_photo']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <!-- Signature -->

                <div class="col-md-6">

                    <label class="form-label">

                        Signature

                    </label>

                    <input
                        type="file"
                        name="signature_path"
                        class="form-control <?= isset($errors['signature_path']) ? 'is-invalid' : '' ?>">

                    <?php if (isset($errors['signature_path'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars($errors['signature_path']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <!-- Status -->

                <div class="col-md-6">

                    <label class="form-label">

                        Account Status

                    </label>

                    <select
                        name="account_status"
                        class="form-select <?= isset($errors['account_status']) ? 'is-invalid' : '' ?>">

                        <?php

                        $statuses = [

                            'Active',

                            'Inactive',

                            'Blocked'

                        ];

                        ?>

                        <?php foreach ($statuses as $status): ?>

                            <option
                                value="<?= $status ?>"
                                <?= (($old['account_status'] ?? 'Active') === $status) ? 'selected' : '' ?>>

                                <?= $status ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <?php if (isset($errors['account_status'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars($errors['account_status']) ?>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

            <!-- Action Buttons -->

            <div class="mt-5 d-flex gap-2">

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="bi bi-check-circle me-1"></i>

                    Create User

                </button>

                <button
                    type="reset"
                    class="btn btn-secondary">

                    Reset

                </button>

                <a
                    href="<?= base_url() ?>/users"
                    class="btn btn-outline-danger">

                    Cancel

                </a>

            </div>

        </form>

    </div>

</div>