<?php
/**
 * Project: colormvc
 * File: Form.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 6:35 pm.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

namespace pennycodes\colormvc\form;

use app\Model;

class Form
{
    public static function begin(string $action, string $method, string $enctype = 'multipart/form-data'): Form
    {
        echo sprintf('<form action="%s" method="%s" enctype="%s">', $action, $method, $enctype);
        return new Form();
    }

    public static function end(): void
    {
        echo '</form>';
    }

    public function field( Model $model, string $attribute, string $template): Field
    {
        return new Field($model, $attribute, $template);
    }

}