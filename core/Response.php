<?php
/**
 * Project: colormvc
 * File: Response.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 10:29 am.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

namespace pennycodes\colormvc;

class Response
{
    public function setStatusCode(int $code): void
    {
        http_response_code($code);
    }

    public function redirect(string $url): void
    {
        header('Location: ' . $url);

    }

}