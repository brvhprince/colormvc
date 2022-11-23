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

    public Router $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function run()
    {
        $this->router->resolve();
    }

}