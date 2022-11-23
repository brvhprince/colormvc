<?php
/**
 * Project: colormvc
 * File: Migrations.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 7:35 pm.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

namespace pennycodes\colormvc;
use Exception;


class Migrations
{
    private  \MysqliDb $db;

    public function __construct()
    {
        $this->db = Application::$app->db->getDb();
    }

    /**
     * @throws Exception
     */
    public function createMigrationTable(): void
    {
        try {
            $this->db->rawQuery('CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function getAppliedMigrations(): array
    {
        try {
            $statement = $this->db->get('migrations');
            $migrations = [];
            foreach ($statement as $migration) {
                $migrations[] = $migration->migration;
            }
            return $migrations;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    /**
     * @throws Exception
     */
    public function saveMigrations(array $migrations): void
    {
        foreach ($migrations as $migration) {
            $this->db->insert('migrations', ['migration' => $migration]);
        }
    }

    /**
     * @throws Exception
     */
    public function applyMigrations(): void
    {
        $this->createMigrationTable();
        $appliedMigrations = $this->getAppliedMigrations();
        $newMigrations = [];
        $files = scandir(Application::$ROOT_DIR.'/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }
            require_once Application::$ROOT_DIR.'/migrations/'.$migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            $this->log("Applying migration $migration");
            $instance->up();
            $this->log("Applied migration $migration");
            $newMigrations[] = $migration;
        }
        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("All migrations are applied");
        }
    }


    public function generateMigration(string $tableName, array $columns): void
    {
        $migrationName = 'm' . date('ymd_His');
        $fileContent = $this->generateMigrationFileContent($tableName, $columns);
        $path = Application::$ROOT_DIR . '/migrations/' . $migrationName . '.php';

        if (!file_exists($path)) {
            $file = fopen($path, 'w');
            fwrite($file, $fileContent);
            fclose($file);
            $this->log("Migration $migrationName created successfully");
        } else {
            $this->log("Migration $migrationName already exists");
        }

    }


    private function generateMigrationFileContent(string $tableName, array $columns): string
    {
        $fileContent = '<?php' . PHP_EOL;
        $fileContent .= 'use pennycodes\colormvc\Application;' . PHP_EOL;
        $fileContent .= 'use pennycodes\colormvc\Database;' . PHP_EOL;
        $fileContent .= 'use pennycodes\colormvc\Migration;' . PHP_EOL;

        $fileContent .= 'class ' . $tableName . ' extends Migration' . PHP_EOL;
        $fileContent .= '{' . PHP_EOL;
        $fileContent .= 'public function up()' . PHP_EOL;
        $fileContent .= '{' . PHP_EOL;
        $fileContent .= '$db = Application::$app->db->getDb();' . PHP_EOL;
        $fileContent .= '$db->rawQuery("CREATE TABLE IF NOT EXISTS ' . $tableName . ' (' . PHP_EOL;
        $fileContent .= 'id INT AUTO_INCREMENT PRIMARY KEY,' . PHP_EOL;
        foreach ($columns as $column) {
            $fileContent .= $column . ',' . PHP_EOL;
        }
        $fileContent .= 'createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP' . PHP_EOL;
        $fileContent .= ' updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP' . PHP_EOL;
        $fileContent .= ') ENGINE=INNODB;");' . PHP_EOL;
        $fileContent .= '}' . PHP_EOL;
        $fileContent .= '}' . PHP_EOL;
        return $fileContent;
    }

    public function log(string $message): void
    {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }

}