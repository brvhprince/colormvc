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

namespace app;

use app\middlewares\BaseMiddleware;

class Controller
{

    public string $action = '';

    protected array $middlewares = [];

    public function render(string $view, array $params = []): string
    {
        return Application::$app->view->renderView($view, $params);
    }


    public function registerMiddleware(BaseMiddleware $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

}