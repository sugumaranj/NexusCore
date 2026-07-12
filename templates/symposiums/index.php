<?php

declare(strict_types=1);

use App\Core\Session;

$success = Session::getFlash('success');
$error = Session::getFlash('error');

$symposiums = $symposiums ?? [];
$search = $search ?? '';
$departmentId = $department_id ?? '';
$academicYear = $academic_year ?? '';
$symposiumType = $symposium_type ?? '';
$status = $status ?? '';
$quickFilter = $quick_filter ?? '';
$departments = $departments ?? [];
$academicYears = $academicYears ?? [];
$symposiumTypes = $symposiumTypes ?? [];
$symposiumStatuses = $symposiumStatuses ?? [];
$quickFilters = $quickFilters ?? [];
$totalSymposiums = $totalSymposiums ?? 0;
$draftCount = $draftCount ?? 0;
$registrationOpenCount = $registrationOpenCount ?? 0;
$registrationClosedCount = $registrationClosedCount ?? 0;
$completedCount = $completedCount ?? 0;
$cancelledCount = $cancelledCount ?? 0;
$canCreateSymposium = $canCreateSymposium ?? false;

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">

            Symposium Management

        </h2>

        <p class="text-muted mb-0">

            Manage symposiums, schedules, registrations and publication status.

        </p>

    </div>

    <?php if ($canCreateSymposium): ?>

        <a
            href="<?= base_url() ?>/symposiums/create"
            class="btn btn-primary">

            <i class="bi bi-plus-circle me-1"></i>

            Add Symposium

        </a>

    <?php endif; ?>

</div>

<?php if ($success): ?>

    <div class="alert alert-success alert-dismissible fade show">

        <?= htmlspecialchars((string) $success, ENT_QUOTES, 'UTF-8') ?>

        <button
            class="btn-close"
            data-bs-dismiss="alert"></button>

    </div>

<?php endif; ?>

<?php if ($error): ?>

    <div class="alert alert-danger alert-dismissible fade show">

        <?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?>

        <button
            class="btn-close"
            data-bs-dismiss="alert"></button>

    </div>

<?php endif; ?>

<div class="row g-4 mb-4">

    <div class="col-md-4 col-lg-2">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-muted">Total Symposiums</h6>

                <h2><?= (int) $totalSymposiums ?></h2>

            </div>

        </div>

    </div>

    <div class="col-md-4 col-lg-2">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-success">Draft</h6>

                <h2><?= (int) $draftCount ?></h2>

            </div>

        </div>

    </div>

    <div class="col-md-4 col-lg-2">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-success">Registration Open</h6>

                <h2><?= (int) $registrationOpenCount ?></h2>

            </div>

        </div>

    </div>

    <div class="col-md-4 col-lg-2">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-warning">Registration Closed</h6>

                <h2><?= (int) $registrationClosedCount ?></h2>

            </div>

        </div>

    </div>

    <div class="col-md-4 col-lg-2">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-primary">Completed</h6>

                <h2><?= (int) $completedCount ?></h2>

            </div>

        </div>

    </div>

    <div class="col-md-4 col-lg-2">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h6 class="text-danger">Cancelled</h6>

                <h2><?= (int) $cancelledCount ?></h2>

            </div>

        </div>

    </div>

</div>

<div class="card shadow-sm border-0 mb-4">

    <div class="card-body">

        <form
            action="<?= base_url() ?>/symposiums"
            method="get"
            class="row g-3">

            <div class="col-md-3">

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search symposium..."
                    value="<?= htmlspecialchars((string) $search, ENT_QUOTES, 'UTF-8') ?>">

            </div>

            <div class="col-md-2">

                <select name="department_id" class="form-select">

                    <option value="">All Departments</option>

                    <?php foreach ($departments as $department): ?>

                        <option
                            value="<?= (int) $department['department_id'] ?>"
                            <?= ((string) $departmentId === (string) $department['department_id']) ? 'selected' : '' ?>>

                            <?= htmlspecialchars((string) $department['department_name'], ENT_QUOTES, 'UTF-8') ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="col-md-2">

                <select name="academic_year" class="form-select">

                    <option value="">All Years</option>

                    <?php foreach ($academicYears as $year): ?>

                        <option
                            value="<?= htmlspecialchars((string) $year, ENT_QUOTES, 'UTF-8') ?>"
                            <?= ($academicYear === (string) $year) ? 'selected' : '' ?>>

                            <?= htmlspecialchars((string) $year, ENT_QUOTES, 'UTF-8') ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="col-md-2">

                <select name="symposium_type" class="form-select">

                    <option value="">All Types</option>

                    <?php foreach ($symposiumTypes as $type): ?>

                        <option
                            value="<?= htmlspecialchars((string) $type, ENT_QUOTES, 'UTF-8') ?>"
                            <?= ($symposiumType === $type) ? 'selected' : '' ?>>

                            <?= htmlspecialchars((string) $type, ENT_QUOTES, 'UTF-8') ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="col-md-2">

                <select name="status" class="form-select">

                    <option value="">All Status</option>

                    <?php foreach ($symposiumStatuses as $statusOption): ?>

                        <option
                            value="<?= htmlspecialchars((string) $statusOption, ENT_QUOTES, 'UTF-8') ?>"
                            <?= ($status === $statusOption) ? 'selected' : '' ?>>

                            <?= htmlspecialchars((string) $statusOption, ENT_QUOTES, 'UTF-8') ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="col-md-2">

                <select name="quick_filter" class="form-select">

                    <option value="">All Filters</option>

                    <?php foreach ($quickFilters as $filter): ?>

                        <option
                            value="<?= htmlspecialchars((string) $filter, ENT_QUOTES, 'UTF-8') ?>"
                            <?= ($quickFilter === $filter) ? 'selected' : '' ?>>

                            <?= htmlspecialchars((string) $filter, ENT_QUOTES, 'UTF-8') ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="col-md-1">

                <button type="submit" class="btn btn-outline-primary w-100">Search</button>

            </div>

            <div class="col-md-1">

                <a href="<?= base_url() ?>/symposiums" class="btn btn-outline-secondary w-100">Reset</a>

            </div>

        </form>

    </div>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body table-responsive">

        <table class="table table-hover align-middle">

            <thead class="table-light">

                <tr>

                    <th>Symposium Code</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Department</th>
                    <th>Academic Year</th>
                    <th>Registration Period</th>
                    <th>Event Dates</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th width="230">Actions</th>

                </tr>

            </thead>

            <tbody>

                <?php if (empty($symposiums)): ?>

                    <tr>

                        <td colspan="10" class="text-center text-muted py-5">

                            No symposiums found.

                        </td>

                    </tr>

                <?php else: ?>

                    <?php foreach ($symposiums as $symposium): ?>

                        <tr>

                            <td><?= htmlspecialchars((string) ($symposium['symposium_code'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>

                            <td><?= htmlspecialchars((string) ($symposium['title'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>

                            <td><?= htmlspecialchars((string) ($symposium['symposium_type'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>

                            <td><?= htmlspecialchars((string) ($symposium['department_name'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>

                            <td><?= htmlspecialchars((string) ($symposium['academic_year'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>

                            <td>

                                <?= htmlspecialchars((string) symposium_format_datetime($symposium['registration_start']), ENT_QUOTES, 'UTF-8') ?><br>
                                <small class="text-muted">to</small><br>
                                <?= htmlspecialchars((string) symposium_format_datetime($symposium['registration_end']), ENT_QUOTES, 'UTF-8') ?>

                            </td>

                            <td>

                                <?= htmlspecialchars((string) symposium_format_date($symposium['event_start_date']), ENT_QUOTES, 'UTF-8') ?><br>
                                <small class="text-muted">to</small><br>
                                <?= htmlspecialchars((string) symposium_format_date($symposium['event_end_date']), ENT_QUOTES, 'UTF-8') ?>

                            </td>

                            <td>

                                <span class="badge bg-<?= symposium_status_badge_class((string) ($symposium['status'] ?? '')) ?>">

                                    <?= htmlspecialchars((string) ($symposium['status'] ?? '-'), ENT_QUOTES, 'UTF-8') ?>

                                </span>

                            </td>

                            <td><?= htmlspecialchars((string) ($symposium['created_by_name'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>

                            <td>

                                <div class="d-flex flex-wrap gap-2">

                                    <a href="<?= base_url() ?>/symposiums/view?id=<?= (int) ($symposium['symposium_id'] ?? 0) ?>" class="btn btn-outline-primary btn-sm">

                                        <i class="bi bi-eye"></i>

                                    </a>

                                    <?php if (!empty($symposium['can_edit'])): ?>

                                        <a href="<?= base_url() ?>/symposiums/edit?id=<?= (int) ($symposium['symposium_id'] ?? 0) ?>" class="btn btn-outline-secondary btn-sm">

                                            <i class="bi bi-pencil"></i>

                                        </a>

                                    <?php endif; ?>

                                    <?php if (!empty($symposium['can_delete'])): ?>

                                        <form action="<?= base_url() ?>/symposiums/delete" method="post" class="d-inline">

                                            <input type="hidden" name="symposium_id" value="<?= (int) ($symposium['symposium_id'] ?? 0) ?>">

                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this symposium?');">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    <?php endif; ?>

                                    <?php if (!empty($symposium['can_change_status'])): ?>

                                        <a href="<?= base_url() ?>/symposiums/view?id=<?= (int) ($symposium['symposium_id'] ?? 0) ?>#status" class="btn btn-outline-info btn-sm">

                                            <i class="bi bi-gear"></i>

                                        </a>

                                    <?php endif; ?>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>
