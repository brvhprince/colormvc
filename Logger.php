<?php
/**
 * Project: colormvc
 * File: Logger.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 7:42 pm.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

namespace pennycodes\colormvc;

class Logger
{


    public const LOG_FILE = __DIR__ . '/../logs/app.log';

    public static function log(string $message): void
    {
        $date = date('Y-m-d H:i:s');
        $message = "[$date] $message" . PHP_EOL;
        file_put_contents(self::LOG_FILE, $message, FILE_APPEND);
    }

    public static function logException(\Exception $e): void
    {
        $date = date('Y-m-d H:i:s');
        $message = "[$date] {$e->getMessage()} in {$e->getFile()} on line {$e->getLine()}" . PHP_EOL;
        file_put_contents(self::LOG_FILE, $message, FILE_APPEND);
    }

    public static function logError(string $message): void
    {
        $date = date('Y-m-d H:i:s');
        $message = "[$date] $message" . PHP_EOL;
        file_put_contents(self::LOG_FILE, $message, FILE_APPEND);
    }

    public static function logWarning(string $message): void
    {
        $date = date('Y-m-d H:i:s');
        $message = "[$date] $message" . PHP_EOL;
        file_put_contents(self::LOG_FILE, $message, FILE_APPEND);
    }

    public static function logInfo(string $message): void
    {
        $date = date('Y-m-d H:i:s');
        $message = "[$date] $message" . PHP_EOL;
        file_put_contents(self::LOG_FILE, $message, FILE_APPEND);
    }

    public static function logDebug(string $message): void
    {
        $date = date('Y-m-d H:i:s');
        $message = "[$date] $message" . PHP_EOL;
        file_put_contents(self::LOG_FILE, $message, FILE_APPEND);
    }

    public static function logNotice(string $message): void
    {
        $date = date('Y-m-d H:i:s');
        $message = "[$date] $message" . PHP_EOL;
        file_put_contents(self::LOG_FILE, $message, FILE_APPEND);
    }

    public static function logCritical(string $message): void
    {
        $date = date('Y-m-d H:i:s');
        $message = "[$date] $message" . PHP_EOL;
        file_put_contents(self::LOG_FILE, $message, FILE_APPEND);
    }

    public static function logAlert(string $message): void
    {
        $date = date('Y-m-d H:i:s');
        $message = "[$date] $message" . PHP_EOL;
        file_put_contents(self::LOG_FILE, $message, FILE_APPEND);
    }

    public static function logEmergency(string $message): void
    {
        $date = date('Y-m-d H:i:s');
        $message = "[$date] $message" . PHP_EOL;
        file_put_contents(self::LOG_FILE, $message, FILE_APPEND);
    }



}