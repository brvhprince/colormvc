<?php
/**
 * Project: colormvc
 * File: ForbiddenException.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 8:49 pm.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

namespace app\exception;

class ForbiddenException extends \Exception
{
    protected $message = "You don't have permission to access this page";
    protected $code = 403;
}
