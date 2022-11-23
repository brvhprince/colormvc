<?php
/**
 * Project: colormvc
 * File: View.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 9:02 pm.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

namespace app;

use app\enums\Extensions;

class View
{
    public string $title = '';

    private string $viewPath = 'views';
    private string $viewExtension = '.php';

    public function renderView(string $view, array $params = []): string
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        if (file_exists(Application::$ROOT_DIR . "/{$this->viewPath}/{$view}.{$this->viewExtension}")) {
            ob_start();
            include_once Application::$ROOT_DIR . "/{$this->viewPath}/{$view}.{$this->viewExtension}";
            return ob_get_clean();
        }

        else {
            throw new \RuntimeException("View {$view} does not exist");
        }

    }


    public function setViewPath(string $path): void
    {
        $this->viewPath = $path;
    }

    public function setViewExtension(string $extension): void
    {
        if (Extensions::isValidViewExtension($extension)) {
            $this->viewExtension = $extension;
        }
        else {
            throw new \RuntimeException('Invalid view extension');
        }

    }


}