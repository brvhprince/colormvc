<?php
/**
 * Project: colormvc
 * File: DBModel.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 7:49 pm.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

namespace pennycodes\colormvc;

abstract class DBModel extends Model
{

    abstract public function tableName(): string;

    abstract public function attributes(): array;

    abstract public function primaryKey(): string;

    public function save(): bool
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();

        $db = Application::$app->db->getDb();

        $data = [];
        foreach ($attributes as $attribute) {
            $data[$attribute] = $this->{$attribute};
        }
        try {

            if ($this->isNewRecord()) {
                $db->insert($tableName, $data);
            } else {
                $db->where($this->primaryKey(), $this->{$this->primaryKey()});
                $db->update($tableName, $data);
            }
            return true;
        }
        catch (\Exception $e) {
            Logger::logException($e);
            return false;
        }
    }

    public function isNewRecord(): bool
    {
        return empty($this->{$this->primaryKey()});
    }


    public function findOne(array $where): ?self
    {

        $tableName = $this->tableName();
        $db = Application::$app->db->getDb();

        foreach ($where as $column => $value) {
            $db->where($column, $value);
        }


        try {
            $record = $db->getOne($tableName);
            if (!$record) {
                return null;
            }
            $model = new static();
            foreach ($record as $key => $value) {
                $model->{$key} = $value;
            }
            return $model;
        } catch (\Exception $e) {
            Logger::logException($e);
            return null;
        }

    }


    public function rules(): array
    {
        return [];
    }
}