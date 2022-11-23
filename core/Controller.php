<?php
/**
 * Project: colormvc
 * File: Controller.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 12:12 pm.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

namespace app\core;

class Controller
{
    public function render(string $view, array $params = []): string
    {
        return Application::$app->router->renderView($view, $params);
    }
}