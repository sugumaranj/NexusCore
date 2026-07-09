<?php

declare(strict_types=1);

/**
 * -------------------------------------------------------------------------
 * NexusCore
 * -------------------------------------------------------------------------
 * File        : UserModel.php
 * Location    : app/Models/
 * Description : Handles all User database operations.
 *
 * Responsibilities
 * ----------------
 * • Retrieve users
 * • Retrieve a single user
 * • Find user by Employee ID
 * • Find user by Email
 * • Create user
 * • Update user
 * • Delete user
 * • Update account status
 * • Reset password
 *
 * NOTE
 * ----
 * This class communicates ONLY with the database.
 * Business rules belong inside UserService.
 *
 * Author      : Sugumaran J
 * Project     : NexusCore
 * -------------------------------------------------------------------------
 */

namespace App\Models;

use App\Database\Database;
use PDO;

final class UserModel
{
    /**
     * Database connection.
     *
     * @var PDO
     */
    private PDO $db;

    /**
     * Constructor.
     *
     * Initialize the shared PDO connection.
     */
    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
 * ---------------------------------------------------------------------
 * Retrieve Users.
 *
 * If a search keyword is provided, the results are filtered using:
 * • Employee ID
 * • Full Name
 * • Email Address
 * • Phone Number
 * • Role
 * • Department Name
 * • Account Status
 *
 * Otherwise, all users are returned.
 *
 * @param string|null $search
 * @param string|null $role
 * @param string|null $status
 *
 * @return array
 * ---------------------------------------------------------------------
 */
public function getAll(
    ?string $search = null,
    ?string $role = null,
    ?string $status = null
): array
{
    $sql = "
        SELECT
            u.user_id,
            u.employee_id,
            u.department_id,
            u.full_name,
            u.email,
            u.phone,
            u.role,
            u.profile_photo,
            u.signature_path,
            u.account_status,
            u.last_login,
            u.created_at,
            u.updated_at,
            d.department_name
        FROM users u
        LEFT JOIN departments d
            ON d.department_id = u.department_id
    ";

    $conditions = [];

    $params = [];

    /*
    |--------------------------------------------------------------------------
    | Search Keyword
    |--------------------------------------------------------------------------
    */

    if ($search !== null && trim($search) !== '') {

        // Use unique placeholders for every LIKE occurrence to
        // avoid driver issues with repeated named parameters.
        $conditions[] = "
            (
                u.employee_id LIKE :keyword_employee_id
                OR u.full_name LIKE :keyword_full_name
                OR u.email LIKE :keyword_email
                OR u.phone LIKE :keyword_phone
                OR u.role LIKE :keyword_role
                OR d.department_name LIKE :keyword_department
            )
        ";

        $kw = '%' . trim($search) . '%';

        $params['keyword_employee_id'] = $kw;
        $params['keyword_full_name']   = $kw;
        $params['keyword_email']       = $kw;
        $params['keyword_phone']       = $kw;
        $params['keyword_role']        = $kw;
        $params['keyword_department']  = $kw;
    }

    /*
    |--------------------------------------------------------------------------
    | Role Filter
    |--------------------------------------------------------------------------
    */

    if ($role !== null && trim($role) !== '') {

        $conditions[] = 'u.role = :role';

        $params['role'] = trim($role);
    }

    /*
    |--------------------------------------------------------------------------
    | Account Status Filter
    |--------------------------------------------------------------------------
    */

    if ($status !== null && trim($status) !== '') {

        $conditions[] = 'u.account_status = :status';

        $params['status'] = trim($status);
    }

    if (!empty($conditions)) {

        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $sql .= " ORDER BY u.full_name ASC";

    $statement = $this->db->prepare($sql);

    // PDO::execute accepts parameter arrays without leading colons.
    $statement->execute($params);

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

    /**
 * ---------------------------------------------------------------------
 * Retrieve a User by ID.
 *
 * Includes department information.
 *
 * @param int $userId
 *
 * @return array|false
 * ---------------------------------------------------------------------
 */
public function findById(int $userId): array|false
{
    $sql = "
        SELECT
            u.*,
            d.department_name
        FROM users u
        LEFT JOIN departments d
            ON d.department_id = u.department_id
        WHERE u.user_id = :user_id
        LIMIT 1
    ";

    $statement = $this->db->prepare($sql);

    $statement->bindValue(
        ':user_id',
        $userId,
        PDO::PARAM_INT
    );

    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

    /**
     * ---------------------------------------------------------------------
     * Find user by Employee ID.
     *
     * @param string $employeeId
     *
     * @return array|false
     * ---------------------------------------------------------------------
     */
    public function findByEmployeeId(
        string $employeeId
    ): array|false {

        $sql = "
            SELECT *
            FROM users
            WHERE employee_id = :employee_id
        ";

        $statement = $this->db->prepare($sql);

        $statement->bindValue(
            ':employee_id',
            strtoupper(trim($employeeId))
        );

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * ---------------------------------------------------------------------
     * Find user by Email.
     *
     * @param string $email
     *
     * @return array|false
     * ---------------------------------------------------------------------
     */
    public function findByEmail(
        string $email
    ): array|false {

        $sql = "
            SELECT *
            FROM users
            WHERE email = :email
        ";

        $statement = $this->db->prepare($sql);

        $statement->bindValue(
            ':email',
            strtolower(trim($email))
        );

        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
 * ---------------------------------------------------------------------
 * Find user by Employee ID excluding a specific user.
 *
 * Used while updating a user to prevent duplicate employee IDs.
 *
 * @param string $employeeId
 * @param int    $userId
 *
 * @return array|false
 * ---------------------------------------------------------------------
 */
public function findByEmployeeIdExcept(
    string $employeeId,
    int $userId
): array|false {

    $sql = "
        SELECT *
        FROM users
        WHERE employee_id = :employee_id
          AND user_id <> :user_id
    ";

    $statement = $this->db->prepare($sql);

    $statement->execute([

        ':employee_id' => strtoupper(trim($employeeId)),

        ':user_id'     => $userId

    ]);

    return $statement->fetch(PDO::FETCH_ASSOC);
}

/**
 * ---------------------------------------------------------------------
 * Find user by Email excluding a specific user.
 *
 * Used while updating a user to prevent duplicate email addresses.
 *
 * @param string $email
 * @param int    $userId
 *
 * @return array|false
 * ---------------------------------------------------------------------
 */
public function findByEmailExcept(
    string $email,
    int $userId
): array|false {

    $sql = "
        SELECT *
        FROM users
        WHERE email = :email
          AND user_id <> :user_id
    ";

    $statement = $this->db->prepare($sql);

    $statement->execute([

        ':email'   => strtolower(trim($email)),

        ':user_id' => $userId

    ]);

    return $statement->fetch(PDO::FETCH_ASSOC);
}

    /**
     * ---------------------------------------------------------------------
     * Create a new user.
     *
     * @param array $data
     *
     * @return bool
     * ---------------------------------------------------------------------
     */
    public function create(array $data): bool
    {
        $sql = "
            INSERT INTO users
            (
                employee_id,
                department_id,
                full_name,
                email,
                phone,
                password_hash,
                role,
                profile_photo,
                signature_path,
                account_status
            )
            VALUES
            (
                :employee_id,
                :department_id,
                :full_name,
                :email,
                :phone,
                :password_hash,
                :role,
                :profile_photo,
                :signature_path,
                :account_status
            )
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([

            ':employee_id'   => strtoupper(trim($data['employee_id'])),

            ':department_id' => $data['department_id'] !== ''
                ? $data['department_id']
                : null,

            ':full_name'     => trim($data['full_name']),

            ':email'         => strtolower(trim($data['email'])),

            ':phone'         => trim($data['phone']),

            ':password_hash' => $data['password_hash'],

            ':role'          => $data['role'],

            ':profile_photo' => $data['profile_photo'] ?? null,

            ':signature_path'=> $data['signature_path'] ?? null,

            ':account_status'=> $data['account_status']

        ]);
    }

    /**
     * ---------------------------------------------------------------------
     * Update an existing user.
     *
     * @param int   $userId
     * @param array $data
     *
     * @return bool
     * ---------------------------------------------------------------------
     */
    public function update(
        int $userId,
        array $data
    ): bool {

        $sql = "
            UPDATE users
            SET
                department_id = :department_id,
                full_name = :full_name,
                email = :email,
                phone = :phone,
                role = :role,
                profile_photo = :profile_photo,
                signature_path = :signature_path,
                account_status = :account_status
            WHERE user_id = :user_id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([

            ':department_id' => $data['department_id'] !== ''
                ? $data['department_id']
                : null,

            ':full_name'      => trim($data['full_name']),

            ':email'          => strtolower(trim($data['email'])),

            ':phone'          => trim($data['phone']),

            ':role'           => $data['role'],

            ':profile_photo'  => $data['profile_photo'] ?? null,

            ':signature_path' => $data['signature_path'] ?? null,

            ':account_status' => $data['account_status'],

            ':user_id'        => $userId

        ]);
    }

    /**
     * ---------------------------------------------------------------------
     * Delete user.
     *
     * @param int $userId
     *
     * @return bool
     * ---------------------------------------------------------------------
     */
    public function delete(int $userId): bool
    {
        $sql = "
            DELETE FROM users
            WHERE user_id = :user_id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            ':user_id' => $userId
        ]);
    }

    /**
     * ---------------------------------------------------------------------
     * Update account status.
     *
     * @param int    $userId
     * @param string $status
     *
     * @return bool
     * ---------------------------------------------------------------------
     */
    public function updateStatus(
        int $userId,
        string $status
    ): bool {

        $sql = "
            UPDATE users
            SET account_status = :account_status
            WHERE user_id = :user_id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            ':account_status' => $status,
            ':user_id'        => $userId
        ]);
    }

    /**
     * ---------------------------------------------------------------------
     * Update password hash.
     *
     * @param int    $userId
     * @param string $passwordHash
     *
     * @return bool
     * ---------------------------------------------------------------------
     */
    public function resetPassword(
        int $userId,
        string $passwordHash
    ): bool {

        $sql = "
            UPDATE users
            SET password_hash = :password_hash
            WHERE user_id = :user_id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            ':password_hash' => $passwordHash,
            ':user_id'       => $userId
        ]);
    }

    /**
 * ---------------------------------------------------------------------
 * Check whether a user account is active.
 *
 * @param int $userId
 *
 * @return bool
 * ---------------------------------------------------------------------
 */
public function isActive(int $userId): bool
{
    $sql = "
        SELECT account_status
        FROM users
        WHERE user_id = :user_id
    ";

    $statement = $this->db->prepare($sql);

    $statement->bindValue(
        ':user_id',
        $userId,
        PDO::PARAM_INT
    );

    $statement->execute();

    $status = $statement->fetchColumn();

    return $status === 'Active';
}

/**
 * ---------------------------------------------------------------------
 * Update User Last Login.
 *
 * @param int $userId
 *
 * @return bool
 * ---------------------------------------------------------------------
 */
public function updateLastLogin(int $userId): bool
{
    $sql = "
        UPDATE users
        SET last_login = NOW()
        WHERE user_id = :user_id
    ";

    $statement = $this->db->prepare($sql);

    return $statement->execute([
        ':user_id' => $userId
    ]);
}
}