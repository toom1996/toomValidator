<?php

namespace EasyValidator;

use Pimple\ServiceProviderInterface;

class BaseValidation
{
    protected array $error = [];

    protected array $validationAttributes = [];

    public function __construct(array $validationAttributes)
    {
        $this->addAttributes($validationAttributes);
    }

    public function addAttributes($attributes)
    {
        if (is_array($attributes)) {

            $this->validationAttributes = array_merge($this->validationAttributes, $attributes);

        }elseif (is_string($attributes)) {

            $this->validationAttributes[] = $attributes;

        }
    }

    public function isValid(&$value)
    {
        foreach ($this->validationAttributes as $attribute) {
            var_dump($value[$attribute]);
        }
    }
}