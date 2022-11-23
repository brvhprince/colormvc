<?php
/**
 * Project: colormvc
 * File: Field.php
 * Author: pennycodes
 * Organization: Colorbrace LLC
 * Author URI: https://pennycodes.dev
 * Created: 23/11/2022 at 6:35 pm.
 *
 * Copyright (c) 2022 Colorbrace LLC. All rights reserved.
 */

namespace pennycodes\colormvc\form;

use app\Model;

class Field
{

    public Model $model;
    public string $attribute;
    public string $template;
    public function __construct(Model $model, string $attribute, string $template)
    {
        $this->model = $model;
        $this->attribute = $attribute;
        $this->template = $template;
    }

    public function __toString(): string
    {
        return sprintf($this->template,
            $this->attribute,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? 'error' : '',
            $this->model->getFirstError($this->attribute)
        );
    }


}