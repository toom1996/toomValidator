<?php

namespace EasyValidator\validators;

use EasyValidator\BaseValidation;

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
     * Valid value whether trigger constraint.
     * @param $value
     * @param $attribute
     * @return mixed|StringValidator|null
     */
    public function valid($value, $attribute)
    {
        if (!$this->_isEmpty($value)) {
            return null;
        }

        return $this->formatMessage('{attribute} 不能为空.', [
            'attribute' => $attribute
        ]);
    }

    /**
     * Check value is it empty.
     * @param $value
     * @return bool
     */
    private function _isEmpty($value): bool
    {
        if ($this->isStrict) {
            return $value === null;
        } else {
            return $value === '' || $value === [] || $value === null;
        }
    }
}
