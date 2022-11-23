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

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];
    private string $viewPath = 'views';
    private string $viewExtension = '.php';

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
            echo $this->renderView('_404');

        }

        if (is_string($callback)) {
            return $this->renderView($callback);
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

        return call_user_func($callback, $this->request);
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

}