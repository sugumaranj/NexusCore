<?php

declare(strict_types=1);

namespace App\Core;

use Dotenv\Dotenv;

final class Bootstrap
{
    public static function loadEnvironment(string $basePath): void
    {
        $dotenv = Dotenv::createImmutable($basePath);
        $dotenv->safeLoad();
    }
}