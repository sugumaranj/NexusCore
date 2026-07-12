<?php

declare(strict_types=1);

/**
 * -------------------------------------------------------------------------
 * NexusCore
 * -------------------------------------------------------------------------
 * File        : edit.php
 * Location    : templates/students/
 * Description : Edit Student Form
 *
 * Responsibilities
 * -------------------------------------------------------------------------
 * • Display editable student information
 * • Preserve submitted values after validation failure
 * • Display validation errors
 * • Submit updates to StudentController
 *
 * NOTE
 * -------------------------------------------------------------------------
 * Register number is immutable and therefore displayed as read-only.
 *
 * Author      : Sugumaran J
 * Project     : NexusCore
 * -------------------------------------------------------------------------
 */

use App\Core\Session;

$errors = $errors ?? [];

$error = Session::getFlash('error');

$student = $student ?? [];

$student = array_merge([
    'student_id' => 0,
    'register_number' => '',
    'roll_number' => '',
    'department_id' => '',
    'full_name' => '',
    'email' => '',
    'phone' => '',
    'gender' => '',
    'dob' => '',
    'academic_year' => '',
    'semester' => '',
    'section' => '',
    'admission_year' => '',
    'graduation_year' => '',
    'profile_photo' => '',
    'account_status' => 'Active',
    'created_at' => '',
    'updated_at' => ''
], $student);

$departments = $departments ?? [];

$profilePhoto = $student['profile_photo'] ?? null;

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">

            Edit Student

        </h2>

        <p class="text-muted mb-0">

            Update the selected student's information.

        </p>

    </div>

    <a
        href="<?= base_url() ?>/students"
        class="btn btn-outline-secondary">

        <i class="bi bi-arrow-left-circle me-1"></i>

        Back

    </a>

</div>

<?php if ($error): ?>

<div class="alert alert-danger">

    <?= htmlspecialchars((string) $error) ?>

</div>

<?php endif; ?>

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form
            action="<?= base_url() ?>/students/update"
            method="post"
            enctype="multipart/form-data">

            <input
                type="hidden"
                name="student_id"
                value="<?= (int) $student['student_id'] ?>">

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label">

                        Profile Photo

                    </label>

                    <?php if (!empty($profilePhoto)): ?>

                        <div class="mb-2">

                            <img
                                src="<?= asset($profilePhoto) ?>"
                                class="rounded-circle"
                                width="80"
                                height="80"
                                alt="Profile Photo">

                        </div>

                    <?php endif; ?>

                    <input
                        type="file"
                        name="profile_photo"
                        class="form-control <?= isset($errors['profile_photo']) ? 'is-invalid' : '' ?>">

                    <?php if (isset($errors['profile_photo'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['profile_photo']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Register Number

                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="<?= htmlspecialchars((string) $student['register_number']) ?>"
                        readonly>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Roll Number

                    </label>

                    <input
                        type="text"
                        name="roll_number"
                        class="form-control <?= isset($errors['roll_number']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars((string) $student['roll_number']) ?>">

                    <?php if (isset($errors['roll_number'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['roll_number']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Full Name

                    </label>

                    <input
                        type="text"
                        name="full_name"
                        class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars((string) $student['full_name']) ?>">

                    <?php if (isset($errors['full_name'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['full_name']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Email Address

                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars((string) $student['email']) ?>">

                    <?php if (isset($errors['email'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['email']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Phone Number

                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars((string) $student['phone']) ?>">

                    <?php if (isset($errors['phone'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['phone']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Gender

                    </label>

                    <select
                        name="gender"
                        class="form-select <?= isset($errors['gender']) ? 'is-invalid' : '' ?>">

                        <?php foreach (['Male', 'Female'] as $gender): ?>

                            <option
                                value="<?= $gender ?>"
                                <?= ((string) $student['gender'] === $gender) ? 'selected' : '' ?>>

                                <?= $gender ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <?php if (isset($errors['gender'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['gender']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Date of Birth

                    </label>

                    <input
                        type="date"
                        name="dob"
                        class="form-control <?= isset($errors['dob']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars((string) $student['dob']) ?>">

                    <?php if (isset($errors['dob'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['dob']) ?>

                        </div>

                    <?php endif; ?>

                </div>

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
                                value="<?= (int) $department['department_id'] ?>"
                                <?= ((string) $student['department_id'] === (string) $department['department_id']) ? 'selected' : '' ?>>

                                <?= htmlspecialchars((string) $department['department_name']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <?php if (isset($errors['department_id'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['department_id']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Academic Year

                    </label>

                    <select
                        name="academic_year"
                        class="form-select <?= isset($errors['academic_year']) ? 'is-invalid' : '' ?>">

                        <option value="">Select Year</option>

                        <option value="1" <?= ((string) $student['academic_year'] === '1') ? 'selected' : '' ?>>
                            First Year
                        </option>

                        <option value="2" <?= ((string) $student['academic_year'] === '2') ? 'selected' : '' ?>>
                            Second Year
                        </option>

                        <option value="3" <?= ((string) $student['academic_year'] === '3') ? 'selected' : '' ?>>
                            Third Year
                        </option>

                    </select>

                    <?php if (isset($errors['academic_year'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['academic_year']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Semester

                    </label>

                    <select
                        name="semester"
                        class="form-select <?= isset($errors['semester']) ? 'is-invalid' : '' ?>">

                        <option value="">Select Semester</option>

                        <?php for ($semester = 1; $semester <= 6; $semester++): ?>

                            <option
                                value="<?= $semester ?>"
                                <?= ((string) $student['semester'] === (string) $semester) ? 'selected' : '' ?>>

                                <?= htmlspecialchars((string) semester_label($semester), ENT_QUOTES, 'UTF-8') ?>

                            </option>

                        <?php endfor; ?>

                    </select>

                    <?php if (isset($errors['semester'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['semester']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Section

                    </label>

                    <input
                        type="text"
                        name="section"
                        class="form-control <?= isset($errors['section']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars((string) $student['section']) ?>">

                    <?php if (isset($errors['section'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['section']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Admission Year

                    </label>

                    <input
                        type="text"
                        name="admission_year"
                        class="form-control <?= isset($errors['admission_year']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars((string) $student['admission_year']) ?>">

                    <?php if (isset($errors['admission_year'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['admission_year']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Graduation Year

                    </label>

                    <input
                        type="text"
                        name="graduation_year"
                        class="form-control <?= isset($errors['graduation_year']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars((string) $student['graduation_year']) ?>">

                    <?php if (isset($errors['graduation_year'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['graduation_year']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <?php if (!empty($profilePhoto)): ?>

                    <div class="col-md-6 d-flex align-items-center">

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="remove_profile_photo"
                                id="remove_profile_photo"
                                value="1">

                            <label class="form-check-label" for="remove_profile_photo">

                                Remove Profile Photo

                            </label>

                        </div>

                    </div>

                <?php endif; ?>

                <div class="col-md-6">

                    <label class="form-label">

                        Account Status

                    </label>

                    <select
                        name="account_status"
                        class="form-select <?= isset($errors['account_status']) ? 'is-invalid' : '' ?>">

                        <?php foreach (['Active', 'Inactive'] as $statusOption): ?>

                            <option
                                value="<?= $statusOption ?>"
                                <?= ((string) $student['account_status'] === $statusOption) ? 'selected' : '' ?>>

                                <?= $statusOption ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <?php if (isset($errors['account_status'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['account_status']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-12">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Update Student

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
