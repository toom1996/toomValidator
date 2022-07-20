<?php

namespace EasyValidator\validators;

use EasyValidator\BaseValidation;

/**
 * String validator.
 * @since v1.0
 */
class StringValidator extends BaseValidation
{
    /**
     * @var bool
     */
    protected bool $isStrict = false;

    protected int $min = 0;

    public int $max = 0;

    public int $notEqual = 0;

    public int $equal = 0;

    public string $encoding = 'UTF-8';

    /**
     * @param bool $isStrict
     * @return $this
     */
    public function isStrict(bool $isStrict = false): StringValidator
    {
        $this->isStrict = $isStrict;

        return $this;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function min(int $value): StringValidator
    {
        $this->min = $value;

        return $this;
    }

    public function max(int $value): StringValidator
    {
        $this->max = $value;

        return $this;
    }

    public function equal(int $value): StringValidator
    {
        $this->equal = $value;

        return $this;
    }

    public function notEqual(int $value): StringValidator
    {
        $this->notEqual = $value;

        return $this;
    }

    /**
     * Valid value whether trigger constraint.
     * @param $value
     * @param $attribute
     * @return mixed|StringValidator|null
     */
    public function valid($value, $attribute)
    {
        if (!$this->isStrict && is_scalar($value) && !is_string($value)) {
            $value = (string)$value;
        }

        if (!is_string($value)) {
             $this->addError($attribute, '{attribute} 必须是一个字符串.');
        }

        $length = mb_strlen($value, $this->encoding);

        if ($this->min !== 0 && $length < $this->min) {
             $this->addError($attribute, '{attribute} 至少包含 {min} 个字符.', [
                'min' => $this->min
            ]);
        }

        if ($this->max !== 0 && $length > $this->max) {
             $this->addError($attribute, '{attribute} 至多包含 {max} 个字符.', [
                'max' => $this->max
            ]);
        }

        if ($this->notEqual !== 0 && $length === $this->notEqual) {
             $this->addError($attribute, '{attribute} 不能为 {length} 个字符.', [
                'length' => $this->notEqual
            ]);
        }

        if ($this->equal !== 0 && $length !== $this->equal) {
             $this->addError($attribute, '{attribute} 必须包含 {length} 个字符.', [
                'length' => $this->equal
            ]);
        }

        return null;
    }
}
