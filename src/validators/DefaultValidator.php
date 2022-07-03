<?php

namespace EasyValidator\validators;

use EasyValidator\BaseValidation;

/**
 * @since 1.0
 */
class DefaultValidator extends BaseValidation
{
    protected $defaultValue;

    public function value($value): DefaultValidator
    {
        $this->defaultValue = $value;

        return $this;
    }

    public function isValid(&$value): bool
    {
        foreach ($this->validationAttributes as $validationAttribute) {
            if (!isset($value[$validationAttribute])) {
                $value[$validationAttribute] = $this->defaultValue;
            }
        }

        return true;
    }
}
