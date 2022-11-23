<?php
/**
 * Project: colormvc
 * File: DBConnectionException.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 10:00 pm.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

namespace pennycodes\colormvc\exception;

class DBConnectionException extends \Exception
{
    
    protected $message = "Database connection failed";
    protected $code = 500;
    
}
