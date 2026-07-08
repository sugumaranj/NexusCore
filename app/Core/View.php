<?php

declare(strict_types=1);

/**
 * -------------------------------------------------------------------------
 * NexusCore
 * -------------------------------------------------------------------------
 * File        : View.php
 * Location    : app/Core/
 * Description : Renders application views using layouts.
 *
 * Author      : Sugumaran J
 * Project     : NexusCore
 * -------------------------------------------------------------------------
 */

namespace App\Core;

use RuntimeException;

final class View
{
    /**
     * Render a view.
     *
     * @param string $view
     * @param array  $data
     * @param string $layout
     *
     * @return void
     */
    public static function render(
        string $view,
        array $data = [],
        string $layout = 'master'
    ): void {

        extract($data, EXTR_SKIP);

        $viewFile = dirname(__DIR__, 2)
            . '/templates/'
            . str_replace('.', '/', $view)
            . '.php';

        if (!file_exists($viewFile)) {
            throw new RuntimeException(
                "View '{$view}' not found."
            );
        }

        $contentFile = $viewFile;

        $layoutFile = dirname(__DIR__, 2)
            . "/templates/layouts/{$layout}.php";

        if (!file_exists($layoutFile)) {
            throw new RuntimeException(
                "Layout '{$layout}' not found."
            );
        }

        require $layoutFile;
    }
}