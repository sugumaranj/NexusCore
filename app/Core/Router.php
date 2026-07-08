<?php

declare(strict_types=1);

/**
 * -------------------------------------------------------------------------
 * NexusCore
 * -------------------------------------------------------------------------
 * File        : Router.php
 * Description : Simple HTTP Router
 * Author       : Sugumaran J
 * -------------------------------------------------------------------------
 */

namespace App\Core;

final class Router
{
    /**
     * Registered routes.
     *
     * @var array
     */
    private array $routes = [];

    /**
     * Register a GET route.
     */
    public function get(string $uri, callable|array $action): void
    {
        $this->routes['GET'][$this->normalizeUri($uri)] = $action;
    }

    /**
     * Register a POST route.
     */
    public function post(string $uri, callable|array $action): void
    {
        $this->routes['POST'][$this->normalizeUri($uri)] = $action;
    }

    /**
     * Dispatch request.
     */
    public function dispatch(string $method, string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);

        // Remove project base path
        $basePath = parse_url(base_url(), PHP_URL_PATH);

        if ($basePath !== '/' && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }

        $uri = $this->normalizeUri($uri);

        if (!isset($this->routes[$method][$uri])) {

            http_response_code(404);

            echo "<h2>404 - Page Not Found</h2>";

            echo "<p>Route: <strong>{$uri}</strong></p>";

            return;
        }

        $action = $this->routes[$method][$uri];

        if (is_array($action)) {

            [$controller, $method] = $action;

            $instance = new $controller();

            $instance->$method();

            return;
        }

        $action();
    }

    /**
     * Normalize URI.
     */
    private function normalizeUri(string $uri): string
    {
        $uri = trim($uri);

        if ($uri === '') {
            return '/';
        }

        $uri = '/' . trim($uri, '/');

        return $uri;
    }
}