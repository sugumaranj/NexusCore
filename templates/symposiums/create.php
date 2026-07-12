<?php

declare(strict_types=1);

use App\Core\Session;

$old = $old ?? [];
$errors = $errors ?? [];
$error = Session::getFlash('error');
$departments = $departments ?? [];
$academicYears = $academicYears ?? [];
$symposiumTypes = $symposiumTypes ?? [];
$symposiumStatuses = $symposiumStatuses ?? [];

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">Create Symposium</h2>

        <p class="text-muted mb-0">

            Capture symposium details, dates and supporting documents.

        </p>

    </div>

    <a href="<?= base_url() ?>/symposiums" class="btn btn-outline-secondary">

        <i class="bi bi-arrow-left-circle me-1"></i>

        Back

    </a>

</div>

<?php if ($error): ?>

    <div class="alert alert-danger">

        <?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?>

    </div>

<?php endif; ?>

<?php if (!empty($errors)): ?>

    <div class="alert alert-danger">

        <strong>Please correct the following errors.</strong>

    </div>

<?php endif; ?>

<div class="card shadow-sm border-0">

    <div class="card-body">

        <form method="post" action="<?= base_url() ?>/symposiums/store" enctype="multipart/form-data">

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label">Symposium Code</label>

                    <input
                        type="text"
                        name="symposium_code"
                        class="form-control <?= isset($errors['symposium_code']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars((string) ($old['symposium_code'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">

                    <?php if (isset($errors['symposium_code'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['symposium_code'], ENT_QUOTES, 'UTF-8') ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-6">

                    <label class="form-label">Title</label>

                    <input
                        type="text"
                        name="title"
                        class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars((string) ($old['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">

                    <?php if (isset($errors['title'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['title'], ENT_QUOTES, 'UTF-8') ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-4">

                    <label class="form-label">Symposium Type</label>

                    <select name="symposium_type" class="form-select <?= isset($errors['symposium_type']) ? 'is-invalid' : '' ?>">

                        <option value="">Select Type</option>

                        <?php foreach ($symposiumTypes as $type): ?>

                            <option
                                value="<?= htmlspecialchars((string) $type, ENT_QUOTES, 'UTF-8') ?>"
                                <?= (($old['symposium_type'] ?? '') === $type) ? 'selected' : '' ?>>

                                <?= htmlspecialchars((string) $type, ENT_QUOTES, 'UTF-8') ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <?php if (isset($errors['symposium_type'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['symposium_type'], ENT_QUOTES, 'UTF-8') ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-4">

                    <label class="form-label">Organizing Department</label>

                    <select name="organizing_department_id" class="form-select <?= isset($errors['organizing_department_id']) ? 'is-invalid' : '' ?>">

                        <option value="">Select Department</option>

                        <?php foreach ($departments as $department): ?>

                            <option
                                value="<?= (int) $department['department_id'] ?>"
                                <?= ((string) ($old['organizing_department_id'] ?? '') === (string) $department['department_id']) ? 'selected' : '' ?>>

                                <?= htmlspecialchars((string) $department['department_name'], ENT_QUOTES, 'UTF-8') ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <?php if (isset($errors['organizing_department_id'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['organizing_department_id'], ENT_QUOTES, 'UTF-8') ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-4">

                    <label class="form-label">Academic Year</label>

                    <select name="academic_year" class="form-select <?= isset($errors['academic_year']) ? 'is-invalid' : '' ?>">

                        <option value="">Select Academic Year</option>

                        <?php foreach ($academicYears as $year): ?>

                            <option
                                value="<?= htmlspecialchars((string) $year, ENT_QUOTES, 'UTF-8') ?>"
                                <?= (($old['academic_year'] ?? '') === (string) $year) ? 'selected' : '' ?>>

                                <?= htmlspecialchars((string) $year, ENT_QUOTES, 'UTF-8') ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <?php if (isset($errors['academic_year'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['academic_year'], ENT_QUOTES, 'UTF-8') ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-12">

                    <label class="form-label">Description</label>

                    <textarea
                        name="description"
                        class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>"
                        rows="5"><?= htmlspecialchars((string) ($old['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>

                    <?php if (isset($errors['description'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['description'], ENT_QUOTES, 'UTF-8') ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-6">

                    <label class="form-label">Registration Start</label>

                    <input
                        type="datetime-local"
                        name="registration_start"
                        class="form-control <?= isset($errors['registration_start']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars((string) ($old['registration_start'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">

                    <?php if (isset($errors['registration_start'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['registration_start'], ENT_QUOTES, 'UTF-8') ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-6">

                    <label class="form-label">Registration End</label>

                    <input
                        type="datetime-local"
                        name="registration_end"
                        class="form-control <?= isset($errors['registration_end']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars((string) ($old['registration_end'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">

                    <?php if (isset($errors['registration_end'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['registration_end'], ENT_QUOTES, 'UTF-8') ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-6">

                    <label class="form-label">Event Start</label>

                    <input
                        type="date"
                        name="event_start_date"
                        class="form-control <?= isset($errors['event_start_date']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars((string) ($old['event_start_date'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">

                    <?php if (isset($errors['event_start_date'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['event_start_date'], ENT_QUOTES, 'UTF-8') ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-6">

                    <label class="form-label">Event End</label>

                    <input
                        type="date"
                        name="event_end_date"
                        class="form-control <?= isset($errors['event_end_date']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars((string) ($old['event_end_date'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">

                    <?php if (isset($errors['event_end_date'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['event_end_date'], ENT_QUOTES, 'UTF-8') ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-4">

                    <label class="form-label">Brochure Upload</label>

                    <input
                        type="file"
                        name="brochure"
                        class="form-control <?= isset($errors['brochure']) ? 'is-invalid' : '' ?>">

                    <?php if (isset($errors['brochure'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['brochure'], ENT_QUOTES, 'UTF-8') ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-4">

                    <label class="form-label">Circular Upload</label>

                    <input
                        type="file"
                        name="circular"
                        class="form-control <?= isset($errors['circular']) ? 'is-invalid' : '' ?>">

                    <?php if (isset($errors['circular'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['circular'], ENT_QUOTES, 'UTF-8') ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-4">

                    <label class="form-label">Banner Upload</label>

                    <input
                        type="file"
                        name="banner"
                        class="form-control <?= isset($errors['banner']) ? 'is-invalid' : '' ?>">

                    <?php if (isset($errors['banner'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['banner'], ENT_QUOTES, 'UTF-8') ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-4">

                    <label class="form-label">Status</label>

                    <select name="status" class="form-select <?= isset($errors['status']) ? 'is-invalid' : '' ?>">

                        <option value="">Select Status</option>

                        <?php foreach ($symposiumStatuses as $statusOption): ?>

                            <option
                                value="<?= htmlspecialchars((string) $statusOption, ENT_QUOTES, 'UTF-8') ?>"
                                <?= (($old['status'] ?? '') === $statusOption) ? 'selected' : '' ?>>

                                <?= htmlspecialchars((string) $statusOption, ENT_QUOTES, 'UTF-8') ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <?php if (isset($errors['status'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['status'], ENT_QUOTES, 'UTF-8') ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-12 text-end">

                    <button type="submit" class="btn btn-primary">Create Symposium</button>

                </div>

            </div>

        </form>

    </div>

</div>
