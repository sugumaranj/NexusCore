<?php

declare(strict_types=1);

/**
 * -------------------------------------------------------------------------
 * NexusCore
 * -------------------------------------------------------------------------
 * File        : SymposiumModel.php
 * Location    : app/Models/
 * Description : Handles all Symposium database operations.
 *
 * Responsibilities
 * -------------------------------------------------------------------------
 * • Retrieve symposiums
 * • Retrieve a single symposium
 * • Find symposium by code
 * • Create symposium
 * • Update symposium
 * • Delete symposium
 * • Search and filter symposiums
 * • Count symposiums by status
 *
 * NOTE
 * -------------------------------------------------------------------------
 * This class communicates ONLY with the database.
 * Business rules belong inside SymposiumService.
 *
 * Author      : Sugumaran J
 * Project     : NexusCore
 * -------------------------------------------------------------------------
 */

namespace App\Models;

use App\Database\Database;
use PDO;

final class SymposiumModel
{
    /**
     * Database connection.
     *
     * @var PDO
     */
    private PDO $db;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * ---------------------------------------------------------------------
     * Retrieve symposiums with optional search and filters.
     *
     * @param string|null $search
     * @param string|null $departmentId
     * @param string|null $academicYear
     * @param string|null $symposiumType
     * @param string|null $status
     * @param string|null $quickFilter
     * @param int|null    $scopeDepartmentId
     * @param int|null    $scopeCreatedBy
     * @param string|null $scopeStatus
     *
     * @return array
     * ---------------------------------------------------------------------
     */
    public function getAll(
        ?string $search = null,
        ?string $departmentId = null,
        ?string $academicYear = null,
        ?string $symposiumType = null,
        ?string $status = null,
        ?string $quickFilter = null,
        ?int $scopeDepartmentId = null,
        ?int $scopeCreatedBy = null,
        ?string $scopeStatus = null
    ): array {
        $sql = "
            SELECT
                s.symposium_id,
                s.symposium_code,
                s.title,
                s.symposium_type,
                s.organizing_department_id,
                s.academic_year,
                s.description,
                s.brochure_path,
                s.circular_path,
                s.banner_path,
                s.registration_start,
                s.registration_end,
                s.event_start_date,
                s.event_end_date,
                s.status,
                s.created_by,
                s.created_at,
                s.updated_at,
                d.department_name,
                u.full_name AS created_by_name
            FROM symposiums s
            LEFT JOIN departments d
                ON d.department_id = s.organizing_department_id
            LEFT JOIN users u
                ON u.user_id = s.created_by
        ";

        $conditions = [];
        $params = [];

        if ($scopeDepartmentId !== null) {
            $conditions[] = 's.organizing_department_id = :scope_department_id';
            $params['scope_department_id'] = $scopeDepartmentId;
        }

        if ($scopeCreatedBy !== null) {
            $conditions[] = 's.created_by = :scope_created_by';
            $params['scope_created_by'] = $scopeCreatedBy;
        }

        if ($scopeStatus !== null) {
            $conditions[] = 's.status = :scope_status';
            $params['scope_status'] = $scopeStatus;
        }

        if ($search !== null && trim($search) !== '') {
            $keyword = '%' . trim($search) . '%';

            $conditions[] = '(
                LOWER(s.symposium_code) LIKE LOWER(:keyword_code)
                OR LOWER(s.title) LIKE LOWER(:keyword_title)
                OR LOWER(d.department_name) LIKE LOWER(:keyword_department)
                OR CAST(s.academic_year AS CHAR) LIKE :keyword_year
                OR LOWER(s.status) LIKE LOWER(:keyword_status)
                OR LOWER(s.description) LIKE LOWER(:keyword_description)
            )';

            $params['keyword_code'] = $keyword;
            $params['keyword_title'] = $keyword;
            $params['keyword_department'] = $keyword;
            $params['keyword_year'] = $keyword;
            $params['keyword_status'] = $keyword;
            $params['keyword_description'] = $keyword;
        }

        if ($departmentId !== null && trim($departmentId) !== '') {
            $conditions[] = 's.organizing_department_id = :department_id';
            $params['department_id'] = (int) trim($departmentId);
        }

        if ($academicYear !== null && trim($academicYear) !== '') {
            $conditions[] = 's.academic_year = :academic_year';
            $params['academic_year'] = (int) trim($academicYear);
        }

        if ($symposiumType !== null && trim($symposiumType) !== '') {
            $conditions[] = 's.symposium_type = :symposium_type';
            $params['symposium_type'] = trim($symposiumType);
        }

        if ($status !== null && trim($status) !== '') {
            $conditions[] = 's.status = :status';
            $params['status'] = trim($status);
        }

        if ($quickFilter !== null && trim($quickFilter) !== '') {
            $conditions[] = $this->buildQuickFilterCondition(trim($quickFilter));
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY s.event_start_date DESC, s.symposium_code ASC';

        $statement = $this->db->prepare($sql);

        $statement->execute($params);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * ---------------------------------------------------------------------
     * Retrieve a symposium by ID.
     *
     * @param int $symposiumId
     *
     * @return array|false
     * ---------------------------------------------------------------------
     */
    public function findById(int $symposiumId): array|false
    {
        $sql = "
            SELECT
                s.symposium_id,
                s.symposium_code,
                s.title,
                s.symposium_type,
                s.organizing_department_id,
                s.academic_year,
                s.description,
                s.brochure_path,
                s.circular_path,
                s.banner_path,
                s.registration_start,
                s.registration_end,
                s.event_start_date,
                s.event_end_date,
                s.status,
                s.created_by,
                s.created_at,
                s.updated_at,
                d.department_name,
                u.full_name AS created_by_name
            FROM symposiums s
            LEFT JOIN departments d
                ON d.department_id = s.organizing_department_id
            LEFT JOIN users u
                ON u.user_id = s.created_by
            WHERE s.symposium_id = :symposium_id
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->bindValue(':symposium_id', $symposiumId, PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * ---------------------------------------------------------------------
     * Find symposium by code.
     *
     * @param string $symposiumCode
     *
     * @return array|false
     * ---------------------------------------------------------------------
     */
    public function findByCode(string $symposiumCode): array|false
    {
        $sql = "
            SELECT *
            FROM symposiums
            WHERE symposium_code = :symposium_code
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->bindValue(':symposium_code', strtoupper(trim($symposiumCode)));

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * ---------------------------------------------------------------------
     * Create a new symposium.
     *
     * @param array $data
     *
     * @return bool
     * ---------------------------------------------------------------------
     */
    public function create(array $data): bool
    {
        $sql = "
            INSERT INTO symposiums
            (
                symposium_code,
                title,
                symposium_type,
                organizing_department_id,
                academic_year,
                description,
                brochure_path,
                circular_path,
                banner_path,
                registration_start,
                registration_end,
                event_start_date,
                event_end_date,
                status,
                created_by
            )
            VALUES
            (
                :symposium_code,
                :title,
                :symposium_type,
                :organizing_department_id,
                :academic_year,
                :description,
                :brochure_path,
                :circular_path,
                :banner_path,
                :registration_start,
                :registration_end,
                :event_start_date,
                :event_end_date,
                :status,
                :created_by
            )
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'symposium_code' => $data['symposium_code'],
            'title' => $data['title'],
            'symposium_type' => $data['symposium_type'],
            'organizing_department_id' => $data['organizing_department_id'],
            'academic_year' => $data['academic_year'],
            'description' => $data['description'],
            'brochure_path' => $data['brochure_path'],
            'circular_path' => $data['circular_path'],
            'banner_path' => $data['banner_path'],
            'registration_start' => $data['registration_start'],
            'registration_end' => $data['registration_end'],
            'event_start_date' => $data['event_start_date'],
            'event_end_date' => $data['event_end_date'],
            'status' => $data['status'],
            'created_by' => $data['created_by']
        ]);
    }

    /**
     * ---------------------------------------------------------------------
     * Update an existing symposium.
     *
     * @param int   $symposiumId
     * @param array $data
     *
     * @return bool
     * ---------------------------------------------------------------------
     */
    public function update(int $symposiumId, array $data): bool
    {
        $sql = "
            UPDATE symposiums
            SET
                title = :title,
                symposium_type = :symposium_type,
                organizing_department_id = :organizing_department_id,
                academic_year = :academic_year,
                description = :description,
                brochure_path = :brochure_path,
                circular_path = :circular_path,
                banner_path = :banner_path,
                registration_start = :registration_start,
                registration_end = :registration_end,
                event_start_date = :event_start_date,
                event_end_date = :event_end_date,
                status = :status
            WHERE symposium_id = :symposium_id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'title' => $data['title'],
            'symposium_type' => $data['symposium_type'],
            'organizing_department_id' => $data['organizing_department_id'],
            'academic_year' => $data['academic_year'],
            'description' => $data['description'],
            'brochure_path' => $data['brochure_path'],
            'circular_path' => $data['circular_path'],
            'banner_path' => $data['banner_path'],
            'registration_start' => $data['registration_start'],
            'registration_end' => $data['registration_end'],
            'event_start_date' => $data['event_start_date'],
            'event_end_date' => $data['event_end_date'],
            'status' => $data['status'],
            'symposium_id' => $symposiumId
        ]);
    }

    /**
     * ---------------------------------------------------------------------
     * Update symposium status.
     *
     * @param int    $symposiumId
     * @param string $status
     *
     * @return bool
     * ---------------------------------------------------------------------
     */
    public function updateStatus(int $symposiumId, string $status): bool
    {
        $sql = "
            UPDATE symposiums
            SET status = :status
            WHERE symposium_id = :symposium_id
        ";

        $statement = $this->db->prepare($sql);

        $statement->bindValue(':status', $status);
        $statement->bindValue(':symposium_id', $symposiumId, PDO::PARAM_INT);

        return $statement->execute();
    }

    /**
     * ---------------------------------------------------------------------
     * Delete a symposium.
     *
     * @param int $symposiumId
     *
     * @return bool
     * ---------------------------------------------------------------------
     */
    public function delete(int $symposiumId): bool
    {
        $sql = "
            DELETE FROM symposiums
            WHERE symposium_id = :symposium_id
        ";

        $statement = $this->db->prepare($sql);

        $statement->bindValue(':symposium_id', $symposiumId, PDO::PARAM_INT);

        return $statement->execute();
    }

    /**
     * ---------------------------------------------------------------------
     * Search symposiums.
     *
     * @param string|null $search
     * @param string|null $departmentId
     * @param string|null $academicYear
     * @param string|null $symposiumType
     * @param string|null $status
     * @param string|null $quickFilter
     * @param int|null    $scopeDepartmentId
     * @param int|null    $scopeCreatedBy
     * @param string|null $scopeStatus
     *
     * @return array
     * ---------------------------------------------------------------------
     */
    public function search(
        ?string $search = null,
        ?string $departmentId = null,
        ?string $academicYear = null,
        ?string $symposiumType = null,
        ?string $status = null,
        ?string $quickFilter = null,
        ?int $scopeDepartmentId = null,
        ?int $scopeCreatedBy = null,
        ?string $scopeStatus = null
    ): array {
        return $this->getAll(
            $search,
            $departmentId,
            $academicYear,
            $symposiumType,
            $status,
            $quickFilter,
            $scopeDepartmentId,
            $scopeCreatedBy,
            $scopeStatus
        );
    }

    /**
     * ---------------------------------------------------------------------
     * Get distinct academic years from symposium records.
     *
     * @return array
     * ---------------------------------------------------------------------
     */
    public function getDistinctAcademicYears(): array
    {
        $sql = "
            SELECT DISTINCT academic_year
            FROM symposiums
            ORDER BY academic_year DESC
        ";

        $statement = $this->db->query($sql);

        $years = [];

        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
            if (!empty($row['academic_year'])) {
                $years[] = (string) $row['academic_year'];
            }
        }

        return $years;
    }

    /**
     * ---------------------------------------------------------------------
     * Count all symposiums.
     *
     * @param int|null    $scopeDepartmentId
     * @param int|null    $scopeCreatedBy
     * @param string|null $scopeStatus
     *
     * @return int
     * ---------------------------------------------------------------------
     */
    public function countAll(
        ?int $scopeDepartmentId = null,
        ?int $scopeCreatedBy = null,
        ?string $scopeStatus = null
    ): int {
        return $this->countByCondition('', [], $scopeDepartmentId, $scopeCreatedBy, $scopeStatus);
    }

    /**
     * ---------------------------------------------------------------------
     * Count symposiums by status.
     *
     * @param string      $status
     * @param int|null    $scopeDepartmentId
     * @param int|null    $scopeCreatedBy
     * @param string|null $scopeStatus
     *
     * @return int
     * ---------------------------------------------------------------------
     */
    public function countByStatus(
        string $status,
        ?int $scopeDepartmentId = null,
        ?int $scopeCreatedBy = null,
        ?string $scopeStatus = null
    ): int {
        return $this->countByCondition(
            's.status = :status',
            ['status' => $status],
            $scopeDepartmentId,
            $scopeCreatedBy,
            $scopeStatus
        );
    }

    /**
     * ---------------------------------------------------------------------
     * Build quick filter SQL condition.
     *
     * @param string $quickFilter
     *
     * @return string
     * ---------------------------------------------------------------------
     */
    private function buildQuickFilterCondition(string $quickFilter): string
    {
        return match ($quickFilter) {
            'Registration Open' => "(
                s.status = 'Registration Open'
                OR (NOW() BETWEEN s.registration_start AND s.registration_end)
            )",
            'Registration Closed' => "(
                s.status = 'Registration Closed'
                OR (NOW() > s.registration_end AND s.event_start_date > CURDATE())
            )",
            'Upcoming' => "(
                s.event_start_date > CURDATE()
                AND s.status NOT IN ('Completed', 'Cancelled')
            )",
            'Completed' => "(
                s.status = 'Completed'
                OR s.event_end_date < CURDATE()
            )",
            default => '1 = 1'
        };
    }

    /**
     * ---------------------------------------------------------------------
     * Count symposiums with optional scope and extra condition.
     *
     * @param string      $extraCondition
     * @param array       $extraParams
     * @param int|null    $scopeDepartmentId
     * @param int|null    $scopeCreatedBy
     * @param string|null $scopeStatus
     *
     * @return int
     * ---------------------------------------------------------------------
     */
    private function countByCondition(
        string $extraCondition,
        array $extraParams,
        ?int $scopeDepartmentId = null,
        ?int $scopeCreatedBy = null,
        ?string $scopeStatus = null
    ): int {
        $sql = 'SELECT COUNT(*) AS total FROM symposiums s';
        $conditions = [];
        $params = $extraParams;

        if ($extraCondition !== '') {
            $conditions[] = $extraCondition;
        }

        if ($scopeDepartmentId !== null) {
            $conditions[] = 's.organizing_department_id = :scope_department_id';
            $params['scope_department_id'] = $scopeDepartmentId;
        }

        if ($scopeCreatedBy !== null) {
            $conditions[] = 's.created_by = :scope_created_by';
            $params['scope_created_by'] = $scopeCreatedBy;
        }

        if ($scopeStatus !== null) {
            $conditions[] = 's.status = :scope_status';
            $params['scope_status'] = $scopeStatus;
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $statement = $this->db->prepare($sql);

        $statement->execute($params);

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return (int) ($row['total'] ?? 0);
    }

    /**
     * Count competitions for a symposium.
     *
     * @param int $symposiumId
     *
     * @return int
     */
    public function countCompetitions(int $symposiumId): int
    {
        $sql = 'SELECT COUNT(*) AS total FROM competitions WHERE symposium_id = :symposium_id';

        $statement = $this->db->prepare($sql);

        $statement->execute(['symposium_id' => $symposiumId]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return (int) ($row['total'] ?? 0);
    }
}
