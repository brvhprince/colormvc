<?php
/**
 * Project: colormvc
 * File: Router.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 10:08 am.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

namespace app\core;

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback): void
    {
        $this->routes['get'][$path] = $callback;
    }

    /**
     * This method is used to resolve the request
     * resolve
     * @return void
     */
    public function resolve()
    {
        /**
         * Get the path from the request
         */
        $path = $this->request->getPath();

        /**
         * Get the method from the request
         */
        $method = $this->request->getMethod();

        /**
         * Get the callback from the routes
         */

        $callback = $this->routes[$method][$path] ?? false;

        /**
         * If the callback is false, then we return a 404 page
         */
        if ($callback === false) {
            $this->response->setStatusCode(404);
            echo $this->renderView('_404');
            exit;
        }
    }

}