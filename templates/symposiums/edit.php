<?php

declare(strict_types=1);

use App\Core\Session;

$old = $old ?? [];
$errors = $errors ?? [];
$error = Session::getFlash('error');
$symposium = $symposium ?? [];
$departments = $departments ?? [];
$academicYears = $academicYears ?? [];
$symposiumTypes = $symposiumTypes ?? [];
$symposiumStatuses = $symposiumStatuses ?? [];

function value(array $old, array $symposium, string $field): string
{
    if (isset($old[$field])) {
        return (string) $old[$field];
    }

    return (string) ($symposium[$field] ?? '');
}

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">Edit Symposium</h2>

        <p class="text-muted mb-0">

            Update symposium details and supporting documents.

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

        <form method="post" action="<?= base_url() ?>/symposiums/update" enctype="multipart/form-data">

            <input type="hidden" name="symposium_id" value="<?= (int) ($symposium['symposium_id'] ?? 0) ?>">

            <input type="hidden" name="symposium_code" value="<?= htmlspecialchars((string) ($symposium['symposium_code'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label">Symposium Code</label>

                    <input
                        type="text"
                        class="form-control"
                        value="<?= htmlspecialchars((string) ($symposium['symposium_code'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                        disabled>

                    <div class="form-text">Symposium code cannot be changed after creation.</div>

                </div>

                <div class="col-md-6">

                    <label class="form-label">Title</label>

                    <input
                        type="text"
                        name="title"
                        class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars(value($old, $symposium, 'title'), ENT_QUOTES, 'UTF-8') ?>">

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
                                <?= (value($old, $symposium, 'symposium_type') === $type) ? 'selected' : '' ?>>

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
                                <?= ((string) value($old, $symposium, 'organizing_department_id') === (string) $department['department_id']) ? 'selected' : '' ?>>

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
                                <?= (value($old, $symposium, 'academic_year') === (string) $year) ? 'selected' : '' ?>>

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
                        rows="5"><?= htmlspecialchars(value($old, $symposium, 'description'), ENT_QUOTES, 'UTF-8') ?></textarea>

                    <?php if (isset($errors['description'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['description'], ENT_QUOTES, 'UTF-8') ?>

                        </div>

                    <?php endif; ?>

                </div>

                <?php
                    $registrationStart = value($old, $symposium, 'registration_start');
                    $registrationEnd = value($old, $symposium, 'registration_end');
                    $eventStartDate = value($old, $symposium, 'event_start_date');
                    $eventEndDate = value($old, $symposium, 'event_end_date');

                    $registrationStart = $registrationStart !== '' ? str_replace(' ', 'T', substr($registrationStart, 0, 16)) : '';
                    $registrationEnd = $registrationEnd !== '' ? str_replace(' ', 'T', substr($registrationEnd, 0, 16)) : '';
                ?>

                <div class="col-md-6">

                    <label class="form-label">Registration Start</label>

                    <input
                        type="datetime-local"
                        name="registration_start"
                        class="form-control <?= isset($errors['registration_start']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($registrationStart, ENT_QUOTES, 'UTF-8') ?>">

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
                        value="<?= htmlspecialchars($registrationEnd, ENT_QUOTES, 'UTF-8') ?>">

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
                        value="<?= htmlspecialchars($eventStartDate, ENT_QUOTES, 'UTF-8') ?>">

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
                        value="<?= htmlspecialchars($eventEndDate, ENT_QUOTES, 'UTF-8') ?>">

                    <?php if (isset($errors['event_end_date'])): ?>

                        <div class="invalid-feedback">

                            <?= htmlspecialchars((string) $errors['event_end_date'], ENT_QUOTES, 'UTF-8') ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-4">

                    <label class="form-label">Brochure</label>

                    <?php if (!empty($symposium['brochure_path'])): ?>

                        <div class="mb-2">

                            <a href="<?= asset($symposium['brochure_path']) ?>" target="_blank" class="text-decoration-none">

                                <?= htmlspecialchars((string) symposium_file_basename($symposium['brochure_path']), ENT_QUOTES, 'UTF-8') ?>

                            </a>

                        </div>

                    <?php endif; ?>

                    <input
                        type="file"
                        name="brochure"
                        class="form-control <?= isset($errors['brochure']) ? 'is-invalid' : '' ?>">

                    <div class="form-check mt-2">

                        <input
                            type="checkbox"
                            name="remove_brochure"
                            value="1"
                            class="form-check-input"
                            id="removeBrochure">

                        <label class="form-check-label" for="removeBrochure">

                            Remove existing brochure

                        </label>

                    </div>

                    <?php if (isset($errors['brochure'])): ?>

                        <div class="invalid-feedback d-block">

                            <?= htmlspecialchars((string) $errors['brochure'], ENT_QUOTES, 'UTF-8') ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-4">

                    <label class="form-label">Circular</label>

                    <?php if (!empty($symposium['circular_path'])): ?>

                        <div class="mb-2">

                            <a href="<?= asset($symposium['circular_path']) ?>" target="_blank" class="text-decoration-none">

                                <?= htmlspecialchars((string) symposium_file_basename($symposium['circular_path']), ENT_QUOTES, 'UTF-8') ?>

                            </a>

                        </div>

                    <?php endif; ?>

                    <input
                        type="file"
                        name="circular"
                        class="form-control <?= isset($errors['circular']) ? 'is-invalid' : '' ?>">

                    <div class="form-check mt-2">

                        <input
                            type="checkbox"
                            name="remove_circular"
                            value="1"
                            class="form-check-input"
                            id="removeCircular">

                        <label class="form-check-label" for="removeCircular">

                            Remove existing circular

                        </label>

                    </div>

                    <?php if (isset($errors['circular'])): ?>

                        <div class="invalid-feedback d-block">

                            <?= htmlspecialchars((string) $errors['circular'], ENT_QUOTES, 'UTF-8') ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-md-4">

                    <label class="form-label">Banner</label>

                    <?php if (!empty($symposium['banner_path'])): ?>

                        <div class="mb-2">

                            <?php if (symposium_is_image_file($symposium['banner_path'])): ?>

                                <img src="<?= asset($symposium['banner_path']) ?>" class="img-fluid rounded" alt="Banner Preview">

                            <?php else: ?>

                                <a href="<?= asset($symposium['banner_path']) ?>" target="_blank" class="text-decoration-none">

                                    <?= htmlspecialchars((string) symposium_file_basename($symposium['banner_path']), ENT_QUOTES, 'UTF-8') ?>

                                </a>

                            <?php endif; ?>

                        </div>

                    <?php endif; ?>

                    <input
                        type="file"
                        name="banner"
                        class="form-control <?= isset($errors['banner']) ? 'is-invalid' : '' ?>">

                    <div class="form-check mt-2">

                        <input
                            type="checkbox"
                            name="remove_banner"
                            value="1"
                            class="form-check-input"
                            id="removeBanner">

                        <label class="form-check-label" for="removeBanner">

                            Remove existing banner

                        </label>

                    </div>

                    <?php if (isset($errors['banner'])): ?>

                        <div class="invalid-feedback d-block">

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
                                <?= (value($old, $symposium, 'status') === $statusOption) ? 'selected' : '' ?>>

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

                    <button type="submit" class="btn btn-primary">Update Symposium</button>

                </div>

            </div>

        </form>

    </div>

</div>
