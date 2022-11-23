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

namespace pennycodes\colormvc;

use Dotenv\Dotenv;

/**
 * app\Core
 * Application
 * @package pennycodes\colormvc
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
    public Database $db;
    public Session $session;
    public Controller $controller;
    public View $view;
    public static Application $app;
    public static Dotenv $dotenv;

    public function __construct()
    {

        if (!defined('ROOT_DIR')) {
            define("ROOT_DIR", dirname(__DIR__));
        }

        $dotenv = Dotenv::createImmutable(ROOT_DIR);
        $dotenv->safeLoad();


        self::$app = $this;
        self::$dotenv = $dotenv;

        self::$ROOT_DIR = ROOT_DIR;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->controller = new Controller();
        $this->view = new View();
     //   $this->db = new Database();

        $this->router = new Router($this->request, $this->response);


    }

    public function setViews(string $path): void
    {
        $this->view->setViewPath($path);
    }

    public function setViewExtension(string $extension): void
    {
        $this->view->setViewExtension($extension);
    }


    public function run(): void
    {
        try {
            echo  $this->router->resolve();
        }
        catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode());
//            echo $this->view->renderView('_error', [
//                'exception' => $e
//            ]);
        }
    }

}