<?php

declare(strict_types=1);

use App\Core\Session;

$success = Session::getFlash('success');
$error = Session::getFlash('error');
$symposium = $symposium ?? [];
$symposiumStatuses = $symposiumStatuses ?? [];
$canChangeStatus = $canChangeStatus ?? false;

function safeValue(array $symposium, string $field): string
{
    return htmlspecialchars((string) ($symposium[$field] ?? '-'), ENT_QUOTES, 'UTF-8');
}

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">View Symposium</h2>

        <p class="text-muted mb-0">Review symposium details, supporting files and registration timeline.</p>

    </div>

    <div class="d-flex gap-2">

        <a href="<?= base_url() ?>/symposiums" class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left-circle me-1"></i>

            Back

        </a>

        <?php if ($canChangeStatus): ?>

            <a href="#status" class="btn btn-outline-info">

                <i class="bi bi-gear"></i>

                Change Status

            </a>

        <?php endif; ?>

    </div>

</div>

<?php if ($success): ?>

    <div class="alert alert-success alert-dismissible fade show">

        <?= htmlspecialchars((string) $success, ENT_QUOTES, 'UTF-8') ?>

        <button class="btn-close" data-bs-dismiss="alert"></button>

    </div>

<?php endif; ?>

<?php if ($error): ?>

    <div class="alert alert-danger alert-dismissible fade show">

        <?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?>

        <button class="btn-close" data-bs-dismiss="alert"></button>

    </div>

<?php endif; ?>

<div class="row g-4">

    <div class="col-lg-8">

        <div class="card shadow-sm border-0">

            <?php if (!empty($symposium['banner_path']) && symposium_is_image_file($symposium['banner_path'])): ?>

                <img src="<?= asset($symposium['banner_path']) ?>" class="card-img-top" alt="Symposium Banner">

            <?php endif; ?>

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start mb-3">

                    <div>

                        <h3 class="mb-1"><?= safeValue($symposium, 'title') ?></h3>

                        <span class="badge bg-<?= symposium_status_badge_class((string) ($symposium['status'] ?? '')) ?>">

                            <?= safeValue($symposium, 'status') ?>

                        </span>

                    </div>

                    <div class="text-end text-secondary">

                        <small>Created by</small><br>
                        <strong><?= safeValue($symposium, 'created_by_name') ?></strong>

                    </div>

                </div>

                <div class="mb-4">

                    <p><?= nl2br(htmlspecialchars((string) ($symposium['description'] ?? ''), ENT_QUOTES, 'UTF-8')) ?></p>

                </div>

                <div class="row g-3">

                    <div class="col-sm-6">

                        <div class="fw-semibold text-muted mb-1">Symposium Code</div>
                        <div><?= safeValue($symposium, 'symposium_code') ?></div>

                    </div>

                    <div class="col-sm-6">

                        <div class="fw-semibold text-muted mb-1">Type</div>
                        <div><?= safeValue($symposium, 'symposium_type') ?></div>

                    </div>

                    <div class="col-sm-6">

                        <div class="fw-semibold text-muted mb-1">Department</div>
                        <div><?= safeValue($symposium, 'department_name') ?></div>

                    </div>

                    <div class="col-sm-6">

                        <div class="fw-semibold text-muted mb-1">Academic Year</div>
                        <div><?= safeValue($symposium, 'academic_year') ?></div>

                    </div>

                    <div class="col-sm-6">

                        <div class="fw-semibold text-muted mb-1">Registration Period</div>
                        <div>
                            <?= htmlspecialchars((string) symposium_format_datetime($symposium['registration_start']), ENT_QUOTES, 'UTF-8') ?><br>
                            <small class="text-muted">to</small><br>
                            <?= htmlspecialchars((string) symposium_format_datetime($symposium['registration_end']), ENT_QUOTES, 'UTF-8') ?>
                        </div>

                    </div>

                    <div class="col-sm-6">

                        <div class="fw-semibold text-muted mb-1">Event Dates</div>
                        <div>
                            <?= htmlspecialchars((string) symposium_format_date($symposium['event_start_date']), ENT_QUOTES, 'UTF-8') ?><br>
                            <small class="text-muted">to</small><br>
                            <?= htmlspecialchars((string) symposium_format_date($symposium['event_end_date']), ENT_QUOTES, 'UTF-8') ?>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="row g-3 mt-3">

            <div class="col-md-6">

                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <h6 class="fw-semibold">Brochure</h6>

                        <?php if (!empty($symposium['brochure_path'])): ?>

                            <a href="<?= asset($symposium['brochure_path']) ?>" target="_blank" class="btn btn-outline-primary btn-sm mt-2">

                                <i class="bi bi-download"></i> Download

                            </a>

                        <?php else: ?>

                            <p class="text-muted mb-0">No brochure uploaded.</p>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

            <div class="col-md-6">

                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <h6 class="fw-semibold">Circular</h6>

                        <?php if (!empty($symposium['circular_path'])): ?>

                            <a href="<?= asset($symposium['circular_path']) ?>" target="_blank" class="btn btn-outline-primary btn-sm mt-2">

                                <i class="bi bi-download"></i> Download

                            </a>

                        <?php else: ?>

                            <p class="text-muted mb-0">No circular uploaded.</p>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h5 class="mb-3">Metadata</h5>

                <dl class="row">

                    <dt class="col-5 text-muted">Created At</dt>
                    <dd class="col-7"><?= htmlspecialchars((string) ($symposium['created_at'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></dd>

                    <dt class="col-5 text-muted">Updated At</dt>
                    <dd class="col-7"><?= htmlspecialchars((string) ($symposium['updated_at'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></dd>

                </dl>

            </div>

        </div>

        <?php if ($canChangeStatus): ?>

            <div class="card shadow-sm border-0 mt-3" id="status">

                <div class="card-body">

                    <h5 class="mb-3">Change Status</h5>

                    <form action="<?= base_url() ?>/symposiums/change-status" method="post">

                        <input type="hidden" name="symposium_id" value="<?= (int) ($symposium['symposium_id'] ?? 0) ?>">

                        <div class="mb-3">

                            <label class="form-label">Status</label>

                            <select name="status" class="form-select">

                                <?php foreach ($symposiumStatuses as $statusOption): ?>

                                    <option
                                        value="<?= htmlspecialchars((string) $statusOption, ENT_QUOTES, 'UTF-8') ?>"
                                        <?= ((string) ($symposium['status'] ?? '') === $statusOption) ? 'selected' : '' ?>>

                                        <?= htmlspecialchars((string) $statusOption, ENT_QUOTES, 'UTF-8') ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                        <button type="submit" class="btn btn-primary w-100">Update Status</button>

                    </form>

                </div>

            </div>

        <?php endif; ?>

    </div>

</div>
