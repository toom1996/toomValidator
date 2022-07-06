<?php

namespace EasyValidator\validators;

use EasyValidator\BaseValidation;
use EasyValidator\Validator;

/**
 * @since v1.0
 */
class RequiredValidator extends BaseValidation
{
    /**
     * @var bool
     */
    protected bool $isStrict = false;

    /**
     * Set strict mode.
     * If set to true, only null values are trigger a constraint violation.
     * @param bool $isStrict
     * @return $this
     */
    public function isStrict(bool $isStrict = false): RequiredValidator
    {
        $this->isStrict = $isStrict;

        return $this;
    }

    /**
     * Return has error.
     * @param mixed $value
     * @return bool
     */
    public function isValid(&$value): bool
    {
        foreach ($this->validationAttributes as $validationAttribute) {
            if ($v = $this->valid($value[$validationAttribute], $validationAttribute)) {
                $this->addErrors($v, $validationAttribute);
                return false;
            }
        }

        return true;
    }

    /**
     * @param $value
     * @param $attribute
     * @return array|null
     */
    public function valid($value, $attribute)
    {
        if (!$this->isEmpty($value)) {
            return null;
        }

        return [Validator::getI18n()->translate('{attribute} cannot be blank.'), []];
    }

    /**
     * Check value is it empty.
     * @param $value
     * @return bool
     */
    private function isEmpty($value): bool
    {
        if ($this->isStrict) {
            return $value === null;
        } else {
            return $value === '' || $value === [] || $value === null;
        }
    }
}
