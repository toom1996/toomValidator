<?php

namespace EasyValidator\validators;

use EasyValidator\BaseValidation;
/**
 * @since 1.0
 */
class FunctionValidator extends BaseValidation
{
    protected $handler = null;

    public function method($method): FunctionValidator
    {
        $this->handler = $method;

        return $this;
    }

    public function isValid(&$value): bool
    {
        foreach ($this->validationAttributes as $validationAttribute) {
            if ($this->handler !== null) {
                if (is_callable($this->handler)) {
                    ($this->handler)($value[$validationAttribute], $validationAttribute);
                } elseif (is_string($this->handler)) {
                    call_user_func($this->handler);
                } elseif (is_array($this->handler)) {
                    call_user_func_array($this->handler, [$value[$validationAttribute], $validationAttribute]);
                }
            }

            if ($this->hasError()) {
                return false;
            }
        }

        return true;
    }
}
