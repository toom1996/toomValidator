<?php

namespace EasyValidator\validators;

use EasyValidator\BaseValidation;
/**
 * @since 1.0
 */
class FunctionValidator extends BaseValidation
{
    protected $handler = null;

    public function method($method)
    {
        $this->handler = $method;

        return $this;
    }

    public function isValid(&$value): bool
    {
        foreach ($this->validationAttributes as $validationAttribute) {
            if ($this->handler !== null) {
                var_dump($this->handler);
                if (is_callable($this->handler)) {
                    ($this->handler)($value[$validationAttribute], $validationAttribute);
                } elseif (is_string($this->handler)) {
                    call_user_func($this->handler);
                } elseif (is_array($this->handler)) {
                    call_user_func_array($this->handler, [$value[$validationAttribute], $validationAttribute]);
                }
            }
        }

        return true;
    }
}
