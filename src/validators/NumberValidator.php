<?php

namespace EasyValidator\validators;

use EasyValidator\BaseValidation;

/**
 * @since 1.0
 */
class NumberValidator extends BaseValidation
{
    public $min = 0;

    public $max = 0;

    public bool $integerOnly = false;

    public string $integerPattern = '/^[+-]?\d+$/';

    public string $numberPattern = '/^[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?$/';


    public function max($value)
    {
        $this->max = $value;

        return $this;
    }

    public function min($value)
    {
        $this->min = $value;

        return $this;
    }

    public function valid($value, $attribute)
    {
        if ($this->isNotNumber($value)) {
            return $this->formatMessage('{attribute} is invalid.', [
                'attribute' => $attribute
            ]);
        }

        $pattern = $this->integerOnly ? $this->integerPattern : $this->numberPattern;

        if (!preg_match($pattern, $this->normalizeNumber($value))) {
            return $this->formatMessage('{attribute} is invalid.', [
                'attribute' => $attribute
            ]);
        }if ($this->min !== 0 && $value < $this->min) {
            return $this->formatMessage('{attribute} must be no less than {min}.', [
                'attribute' => $attribute,
                'mix' => $this->min
            ]);
        } elseif ($this->max !== 0 && $value > $this->max) {
            return $this->formatMessage('{attribute} must be no greater than {max}.', [
                'attribute' => $attribute,
                'max' => $this->max
            ]);
        }

        return null;
    }

    public function normalizeNumber($value)
    {
        $value = (string) $value;

        $localeInfo = localeconv();
        $decimalSeparator = $localeInfo['decimal_point'] ?? null;

        if ($decimalSeparator !== null && $decimalSeparator !== '.') {
            $value = str_replace($decimalSeparator, '.', $value);
        }

        return $value;
    }

    private function isNotNumber($value): bool
    {
        return is_array($value)
            || is_bool($value)
            || (is_object($value) && !method_exists($value, '__toString'))
            || (!is_object($value) && !is_scalar($value) && $value !== null);
    }
}