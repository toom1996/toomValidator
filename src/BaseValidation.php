<?php

namespace EasyValidator;

class BaseValidation extends ServiceProvider
{
    /**
     * Validation attributes.
     * @var array
     */
    protected array $validationAttributes = [];

    public function setValidationAttributes($validationAttributes)
    {
        if (is_array($validationAttributes)) {
            $this->validationAttributes = $validationAttributes;
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
            $this->valid($value[$validationAttribute], $validationAttribute);

            if ($this->hasError()) {
                return false;
            }
        }

        return true;
    }


    protected function valid($value, $attribute)
    {
        return null;
    }

    public function hasError(): bool
    {
        return (bool)$this->validator->errors;
    }

    public function addError($attribute, $message, $params = [])
    {
        $params['attribute'] = $attribute;

        $this->validator->errors[$attribute] = $this->validator->formatter->formatMessage($message, $params);
    }
}
