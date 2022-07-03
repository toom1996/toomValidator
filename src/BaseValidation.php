<?php

namespace EasyValidator;

use Pimple\ServiceProviderInterface;

abstract class BaseValidation
{
    public ?array $errors = [];

    /**
     * Validation attributes.
     * @var array
     */
    protected array $validationAttributes = [];

    public function __construct(array $validationAttributes)
    {
        $this->addAttributes($validationAttributes);
    }

    public function addAttributes($validationAttributes)
    {
        if (is_array($validationAttributes)) {
            $this->validationAttributes = array_merge($this->validationAttributes, $validationAttributes);
        } elseif (is_string($validationAttributes)) {
            $this->validationAttributes[] = $validationAttributes;
        }
    }

    /**
     * @param $value
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


    protected function valid($value, $attribute)
    {
        return null;
    }


    public function formatMessage($message, $params)
    {
        $placeholders = [];
        foreach ((array) $params as $name => $value) {
            $placeholders['{' . $name . '}'] = $value;
        }

        return ($placeholders === []) ? $message : strtr($message, $placeholders);
    }

    public function addErrors($error, $field)
    {
        $this->errors[$field] = $error;
    }

    public function message()
    {

    }
}