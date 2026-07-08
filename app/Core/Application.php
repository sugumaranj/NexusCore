<?php

declare(strict_types=1);

/**
 * ---------------------------------------------------------
 * NexusCore
 * ---------------------------------------------------------
 * File        : Application.php
 * Description : Initializes and manages the application.
 *
 * Responsibilities:
 *  - Load application configuration
 *  - Load database configuration
 *  - Create database connection
 *  - Start PHP session
 *
 * Author      : Sugumaran J
 * Project     : NexusCore
 * ---------------------------------------------------------
 */

namespace App\Core;

use PDO;

final class Application
{
    /**
     * Application configuration.
     *
     * @var array
     */
    private array $appConfig;

    /**
     * Database configuration.
     *
     * @var array
     */
    private array $databaseConfig;

    /**
     * PDO database connection.
     *
     * @var PDO
     */
    private PDO $database;

    /**
     * Constructor.
     *
     * Loads configuration files,
     * starts the session,
     * and creates the database connection.
     */
    public function __construct()
    {
        $this->loadConfigurations();

        Session::start();

        $this->connectDatabase();
    }

    /**
     * Load configuration files.
     *
     * @return void
     */
    private function loadConfigurations(): void
    {
        $this->appConfig = require dirname(__DIR__, 2) . '/config/app.php';

        $this->databaseConfig = require dirname(__DIR__, 2) . '/config/database.php';
    }

    /**
     * Start a secure PHP session.
     *
     * @return void
     */
    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {

            session_start();
        }
    }

    /**
     * Create PDO database connection.
     *
     * @return void
     */
    private function connectDatabase(): void
    {
        $this->database = Database::getConnection(
            $this->databaseConfig
        );
    }

    /**
     * Returns PDO connection.
     *
     * @return PDO
     */
    public function getDatabase(): PDO
    {
        return $this->database;
    }

    /**
     * Returns application configuration.
     *
     * @return array
     */
    public function getAppConfig(): array
    {
        return $this->appConfig;
    }
}