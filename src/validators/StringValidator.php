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

    public function max(int $value)
    {
        $this->max = $value;

        return $this;
    }

    public function equal(int $value)
    {
        $this->equal = $value;

        return $this;
    }

    public function notEqual(int $value)
    {
        $this->notEqual = $value;

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
        if (!$this->isStrict && is_scalar($value) && !is_string($value)) {
            $value = (string)$value;
        }

        if (!is_string($value)) {
            return $this->formatMessage('{attribute} 必须是一个字符串.', [
                'attribute' => $attribute
            ]);
        }

        $length = mb_strlen($value, $this->encoding);

        if ($this->min !== 0 && $length < $this->min) {
            return $this->formatMessage('{attribute} 至少包含 {min} 个字符.', [
                'min' => $this->min
            ]);
        }

        if ($this->max !== 0 && $length > $this->max) {
            return $this->formatMessage('{attribute} 至多包含 {max} 个字符.', [
                'max' => $this->max
            ]);
        }

        if ($this->notEqual !== 0 && $length === $this->notEqual) {
            return $this->formatMessage('{attribute} 不能为 {length} 个字符.', [
                'length' => $this->notEqual
            ]);
        }

        if ($this->equal !== 0 && $length !== $this->equal) {
            return $this->formatMessage('{attribute} 必须包含 {length} 个字符.', [
                'length' => $this->equal
            ]);
        }

        return null;
    }
}
