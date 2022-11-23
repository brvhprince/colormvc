<?php
/**
 * Project: colormvc
 * File: NotFoundException.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 8:59 pm.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

namespace app\core\exception;

class NotFoundException extends \Exception
{
    protected $message = "Page not found";
    protected $code = 404;
}
