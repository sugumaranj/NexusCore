<?php

declare(strict_types=1);

/**
 * ---------------------------------------------------------
 * NexusCore
 * URL Helper
 * ---------------------------------------------------------
 * Contains helper functions used across the application.
 * ---------------------------------------------------------
 */

if (!function_exists('config')) {

    /**
     * Read configuration values.
     *
     * Example:
     * config('base_url')
     *
     * @param string $key
     * @return mixed
     */
    function config(string $key): mixed
    {
        static $config = null;

        if ($config === null) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
        }

        return $config[$key] ?? null;
    }
}

if (!function_exists('base_url')) {

    /**
     * Returns the application base URL.
     *
     * @return string
     */
    function base_url(): string
    {
        return rtrim(config('base_url'), '/');
    }
}

if (!function_exists('asset')) {

    /**
     * Generate an asset URL.
     *
     * Example:
     * asset('assets/css/app.css')
     *
     * @param string $path
     * @return string
     */
    function asset(string $path): string
    {
        return base_url() . '/' . ltrim($path, '/');
    }
}