<?php
/**
 * Project: colormvc
 * File: index.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 9:47 pm.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

require_once __DIR__ . '/vendor/autoload.php';

use pennycodes\colormvc\Application;

$app = new Application();

$app->router->get('/', function () {
    return 'Hello World';
});

$app->run();

