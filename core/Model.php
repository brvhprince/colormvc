<?php
/**
 * Project: colormvc
 * File: Model.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 12:48 pm.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

namespace app\core;

abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';
    public const RULE_FILE = 'file';
    public const RULE_FILE_SIZE = 'fileSize';
    public const RULE_FILE_TYPE = 'fileType';
    public const RULE_FILE_EXTENSION = 'fileExtension';
    public const RULE_FILE_MAX = 'fileMax';
    public const RULE_FILE_MIN = 'fileMin';
    public const RULE_FILE_DIMENSIONS = 'fileDimensions';
    public const RULE_FILE_DIMENSIONS_MAX = 'fileDimensionsMax';
    public const RULE_FILE_DIMENSIONS_MIN = 'fileDimensionsMin';
    public const RULE_FILE_DIMENSIONS_WIDTH = 'fileDimensionsWidth';
    public const RULE_FILE_DIMENSIONS_HEIGHT = 'fileDimensionsHeight';
    public const RULE_FILE_DIMENSIONS_WIDTH_MAX = 'fileDimensionsWidthMax';
    public const RULE_FILE_DIMENSIONS_HEIGHT_MAX = 'fileDimensionsHeightMax';
    public const RULE_FILE_DIMENSIONS_WIDTH_MIN = 'fileDimensionsWidthMin';
    public const RULE_FILE_DIMENSIONS_HEIGHT_MIN = 'fileDimensionsHeightMin';

    public array $errors = [];

    public function load($data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function validate(): bool
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addErrorForRule($attribute, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                }
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                }
                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                }
                if ($ruleName === self::RULE_UNIQUE) {
                    $className = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();
                    $statement = Application::$app->db->getDb()->where($uniqueAttr, $value);
                    try {
                        $record = $statement->getOne($tableName);

                    }
                    catch (\Exception $e) {
                        Logger::logException($e);

                       $record = false;
                    }
                    if ($record) {
                        $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field' => $attribute]);
                    }
                }
                if ($ruleName === self::RULE_FILE) {
                    if (!isset($_FILES[$attribute])) {
                        $this->addErrorForRule($attribute, self::RULE_FILE);
                    }
                }
                if ($ruleName === self::RULE_FILE_SIZE) {
                    if ($_FILES[$attribute]['size'] > $rule['size']) {
                        $this->addErrorForRule($attribute, self::RULE_FILE_SIZE, $rule);
                    }
                }
                if ($ruleName === self::RULE_FILE_TYPE) {
                    if ($rule['type'] === 'image') {
                        $fileType = mime_content_type($_FILES[$attribute]['tmp_name']);
                        if ($fileType === false || !in_array($fileType, $this->validImageTypes())) {
                            $this->addErrorForRule($attribute, self::RULE_FILE_TYPE, $rule);
                        }
                    }
                    else if ($rule['type'] === 'document') {
                        $fileType = mime_content_type($_FILES[$attribute]['tmp_name']);
                        if ($fileType === false || !in_array($fileType, $this->validDocumentTypes())) {
                            $this->addErrorForRule($attribute, self::RULE_FILE_TYPE, $rule);
                        }
                    }
                    else if ($rule['type'] === 'audio') {
                        $fileType = mime_content_type($_FILES[$attribute]['tmp_name']);
                        if ($fileType === false || !in_array($fileType, $this->validAudioTypes())) {
                            $this->addErrorForRule($attribute, self::RULE_FILE_TYPE, $rule);
                        }
                    }
                    else if ($rule['type'] === 'video') {
                        $fileType = mime_content_type($_FILES[$attribute]['tmp_name']);
                        if ($fileType === false || !in_array($fileType, $this->validVideoTypes())) {
                            $this->addErrorForRule($attribute, self::RULE_FILE_TYPE, $rule);
                        }
                    }
                    else if (is_string($rule['type'])) {
                        $fileType = mime_content_type($_FILES[$attribute]['tmp_name']);
                        if ($fileType === false || $fileType !== $rule['type']) {
                            $this->addErrorForRule($attribute, self::RULE_FILE_TYPE, $rule);
                        }
                    }
                    else if (is_array($rule['type'])) {
                        $fileType = mime_content_type($_FILES[$attribute]['tmp_name']);
                        if ($fileType === false || !in_array($fileType, $rule['type'])) {
                            $this->addErrorForRule($attribute, self::RULE_FILE_TYPE, $rule);
                        }
                    }
                }
                if ($ruleName === self::RULE_FILE_EXTENSION) {
                    if (is_string($rule['extension'])) {
                        $fileExtension = pathinfo($_FILES[$attribute]['name'], PATHINFO_EXTENSION);
                        if ($fileExtension !== $rule['extension']) {
                            $this->addErrorForRule($attribute, self::RULE_FILE_EXTENSION, $rule);
                        }
                    }
                    else if (is_array($rule['extension'])) {
                        $fileExtension = pathinfo($_FILES[$attribute]['name'], PATHINFO_EXTENSION);
                        if (!in_array($fileExtension, $rule['extension'])) {
                            $this->addErrorForRule($attribute, self::RULE_FILE_EXTENSION, $rule);
                        }
                    }
                }
                if ($ruleName === self::RULE_FILE_DIMENSIONS) {
                    $fileDimensions = getimagesize($_FILES[$attribute]['tmp_name']);
                    if ($fileDimensions === false || $fileDimensions[0] !== $rule['width'] || $fileDimensions[1] !== $rule['height']) {
                        $this->addErrorForRule($attribute, self::RULE_FILE_DIMENSIONS, $rule);
                    }
                }

                if ($ruleName === self::RULE_FILE_DIMENSIONS_MIN) {
                    $fileDimensions = getimagesize($_FILES[$attribute]['tmp_name']);
                    if ($fileDimensions === false || $fileDimensions[0] < $rule['width'] || $fileDimensions[1] < $rule['height']) {
                        $this->addErrorForRule($attribute, self::RULE_FILE_DIMENSIONS_MIN, $rule);
                    }
                }

                if ($ruleName === self::RULE_FILE_DIMENSIONS_MAX) {
                    $fileDimensions = getimagesize($_FILES[$attribute]['tmp_name']);
                    if ($fileDimensions === false || $fileDimensions[0] > $rule['width'] || $fileDimensions[1] > $rule['height']) {
                        $this->addErrorForRule($attribute, self::RULE_FILE_DIMENSIONS_MAX, $rule);
                    }
                }

                if ($ruleName === self::RULE_FILE_DIMENSIONS_WIDTH) {
                    $fileDimensions = getimagesize($_FILES[$attribute]['tmp_name']);
                    if ($fileDimensions === false || $fileDimensions[0] !== $rule['width']) {
                        $this->addErrorForRule($attribute, self::RULE_FILE_DIMENSIONS_WIDTH, $rule);
                    }
                }

                if ($ruleName === self::RULE_FILE_DIMENSIONS_WIDTH_MIN) {
                    $fileDimensions = getimagesize($_FILES[$attribute]['tmp_name']);
                    if ($fileDimensions === false || $fileDimensions[0] < $rule['width']) {
                        $this->addErrorForRule($attribute, self::RULE_FILE_DIMENSIONS_WIDTH_MIN, $rule);
                    }
                }

                if ($ruleName === self::RULE_FILE_DIMENSIONS_WIDTH_MAX) {
                    $fileDimensions = getimagesize($_FILES[$attribute]['tmp_name']);
                    if ($fileDimensions === false || $fileDimensions[0] > $rule['width']) {
                        $this->addErrorForRule($attribute, self::RULE_FILE_DIMENSIONS_WIDTH_MAX, $rule);
                    }
                }

                if ($ruleName === self::RULE_FILE_DIMENSIONS_HEIGHT) {
                    $fileDimensions = getimagesize($_FILES[$attribute]['tmp_name']);
                    if ($fileDimensions === false || $fileDimensions[1] !== $rule['height']) {
                        $this->addErrorForRule($attribute, self::RULE_FILE_DIMENSIONS_HEIGHT, $rule);
                    }
                }

                if ($ruleName === self::RULE_FILE_DIMENSIONS_HEIGHT_MIN) {
                    $fileDimensions = getimagesize($_FILES[$attribute]['tmp_name']);
                    if ($fileDimensions === false || $fileDimensions[1] < $rule['height']) {
                        $this->addErrorForRule($attribute, self::RULE_FILE_DIMENSIONS_HEIGHT_MIN, $rule);
                    }
                }

                if ($ruleName === self::RULE_FILE_DIMENSIONS_HEIGHT_MAX) {
                    $fileDimensions = getimagesize($_FILES[$attribute]['tmp_name']);
                    if ($fileDimensions === false || $fileDimensions[1] > $rule['height']) {
                        $this->addErrorForRule($attribute, self::RULE_FILE_DIMENSIONS_HEIGHT_MAX, $rule);
                    }
                }

            }
        }
        return empty($this->errors);
    }

    public function errorMessages(): array
    {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be a valid email address',
            self::RULE_MIN => 'Min length of this field must be {min}',
            self::RULE_MAX => 'Max length of this field must be {max}',
            self::RULE_MATCH => 'This field must be the same as {match}',
            self::RULE_UNIQUE => 'Record with this {field} already exists',
            self::RULE_FILE => 'This field must be a file',
            self::RULE_FILE_SIZE => 'This file must be less than {size} bytes',
            self::RULE_FILE_TYPE => 'This file must be of type {type}',
            self::RULE_FILE_EXTENSION => 'This file must have the extension {extension}',
            self::RULE_FILE_MAX => 'This file must be less than {max} bytes',
            self::RULE_FILE_MIN => 'This file must be greater than {min} bytes',
            self::RULE_FILE_DIMENSIONS => 'This file must be {width} x {height} pixels',
            self::RULE_FILE_DIMENSIONS_MAX => 'This file must be less than {maxWidth} x {maxHeight} pixels',
            self::RULE_FILE_DIMENSIONS_MIN => 'This file must be greater than {minWidth} x {minHeight} pixels',
            self::RULE_FILE_DIMENSIONS_WIDTH => 'This file must be {width} pixels wide',
            self::RULE_FILE_DIMENSIONS_HEIGHT => 'This file must be {height} pixels high',
            self::RULE_FILE_DIMENSIONS_WIDTH_MAX => 'This file must be less than {maxWidth} pixels wide',
            self::RULE_FILE_DIMENSIONS_HEIGHT_MAX => 'This file must be less than {maxHeight} pixels high',
            self::RULE_FILE_DIMENSIONS_WIDTH_MIN => 'This file must be greater than {minWidth} pixels wide',
            self::RULE_FILE_DIMENSIONS_HEIGHT_MIN => 'This file must be greater than {minHeight} pixels high',

        ];
    }

    public function validDocumentTypes(): array
    {
        return [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ];
    }

    public function validVideoTypes(): array
    {
        return [
            'video/mpeg',
            'video/mp4',
            'video/quicktime',
            'video/x-msvideo',
            'video/x-ms-wmv',
        ];
    }

    public function validImageTypes(): array
    {
        return [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/bmp',
            'image/tiff',
            'image/svg+xml',
        ];
    }

    public function validAudioTypes(): array
    {
        return [
            'audio/mpeg',
            'audio/x-wav',
        ];
    }

   public function addErrorForRule(string $attribute, string $rule, $params = []): void
   {
        $message = $this->errorMessages()[$rule] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function addError(string $attribute, string $message): void
    {
        $this->errors[$attribute][] = $message;
    }


    public function hasError(string $attribute): string | false
    {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError(string $attribute): string | false
    {
        return $this->errors[$attribute][0] ?? false;
    }

    public function getErrors(string $attribute = ''): array
    {
        if ($attribute === '') {
            return $this->errors;
        }
        return $this->errors[$attribute] ?? [];
    }



    abstract public function rules() : array;
}