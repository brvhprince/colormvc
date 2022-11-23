<?php
/**
 * Project: colormvc
 * File: DBVariableException.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 10:01 pm.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

namespace pennycodes\colormvc\exception;

use Throwable;

class DBVariableException extends \Exception
{
    protected $message = "Database variable not set";
    protected $code = 500;
    
    public function __construct($message = "", Throwable $previous = null)
    {
        parent::__construct($message, 500, $previous);
    }
}