<?php
/**
 * Project: colormvc
 * File: Extensions.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 11:29 am.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

namespace app\core\enums;

abstract class Extensions
{

 public static function isValidViewExtension(string $extension): bool
 {
     $extensions =
         [
            'php',
            'html',
            'htm',
            'phtml',
     ];
        return in_array($extension, $extensions);
 }
}
