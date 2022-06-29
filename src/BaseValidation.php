<?php

namespace EasyValidator;

use Pimple\ServiceProviderInterface;

class BaseValidation
{
    public array $errors = [];

    /**
     * Validation attributes.
     * @var array
     */
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