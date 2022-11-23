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

use app\core\enums\Extensions;
use app\core\exception\NotFoundException;

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

    /**
     * Handles the get request
     * get
     * @param $path
     * @param $callback
     * @return void
     */
    public function get($path, $callback): void
    {
        $this->routes['get'][$path] = $callback;
    }

    /**
     * Handles the post request
     * post
     * @param $path
     * @param $callback
     * @return void
     */
    public function post($path, $callback): void
    {
        $this->routes['post'][$path] = $callback;
    }


    /**
     * This method is used to resolve the request
     * resolve
     * @return mixed
     * @throws NotFoundException
     */
    public function resolve(): mixed
    {
        /**
         * Get the path from the request
         */
        $path = $this->request->getPath();

        /**
         * Get the method from the request
         */
         $method = $this->request->method();

        /**
         * Get the callback from the routes
         */

        $callback = $this->routes[$method][$path] ?? false;

        /**
         * If the callback is false, then we return a 404 page
         */
        if ($callback === false) {
            $this->response->setStatusCode(404);
           throw new NotFoundException();

        }

        if (is_string($callback)) {
            return Application::$app->view->renderView($callback);
        }

        if (is_array($callback)) {
            /**
             * @var $controller Controller
             */
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;
            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }
        }

        return call_user_func($callback, $this->request, $this->response);
    }



}