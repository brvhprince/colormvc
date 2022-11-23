<?php
/**
 * Project: colormvc
 * File: BaseMiddleware.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 8:43 pm.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

namespace app\middlewares;

abstract class BaseMiddleware
{
    abstract public function execute();

}