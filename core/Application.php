<?php

/**
 * Project: colormvc
 * File: Application.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 9:57 am.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

namespace app\core;

/**
 * app\Core
 * Application
 * @package app\core
 * @version 1.0.0
 * @since 1.0.0
 * @author pennycodes
 * @license MIT
 * @link https://pennycodes.dev
 * This class is the main class of the application
 */


class Application
{
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public static Application $app;

    public function __construct()
    {

        if (!defined('ROOT_DIR')) {
            define("ROOT_DIR", dirname(__DIR__));
        }


        self::$app = $this;

        self::$ROOT_DIR = ROOT_DIR;
        $this->request = new Request();
        $this->response = new Response();

        $this->router = new Router($this->request, $this->response);


    }

    public function setViews(string $path): void
    {
        $this->router->setViewPath($path);
    }

    public function setViewExtension(string $extension): void
    {
        $this->router->setViewExtension($extension);
    }


    public function run(): void
    {
       echo  $this->router->resolve();
    }

}