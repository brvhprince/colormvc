<?php
/**
 * Project: colormvc
 * File: Database.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 7:00 pm.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

namespace app\core;

use Exception;

class Database
{
    private  \MysqliDb $db;

    public function __construct()
    {

        $dotenv = Application::$dotenv;

        $dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_PORT']);
        $dotenv->required('DB_HOST')->notEmpty();
        $dotenv->required('DB_NAME')->notEmpty();
        $dotenv->required('DB_USER')->notEmpty();
        $dotenv->required('DB_PORT')->notEmpty();
        $dotenv->required('DB_PORT')->isInteger();

        $mysqli = mysqli_connect($_ENV['DB_HOST'],$_ENV['DB_USER'],$_ENV['DB_PASS'],$_ENV['DB_NAME'],$_ENV['DB_PORT']);
        $mysqli->set_charset('utf8mb4');
        $this->db = new \MysqliDb($mysqli);

    }

    public function getDb(): \MysqliDb
    {
        return $this->db;
    }


}