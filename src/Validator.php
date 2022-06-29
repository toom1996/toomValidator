<?php

namespace EasyValidator;

use EasyValidator\validators\Required;

/**
 * @method Required required(array $attributes = [])
 */
class Validator
{
    protected ?ServiceContainer $serviceContainer;

    public array $formData = [];

    public array $errors;

    public array $providers = [
        'required' => Required::class
    ];

    public function __construct(array $formData = [])
    {
        $this->serviceContainer = new ServiceContainer();

        $this->providers = array_merge($this->providers, $this->_getValidatorProviders());

        $this->formData = $formData;
    }

    private function _getValidatorProviders(): array
    {
        return [
            'required' => Required::class,
        ];
    }

    public function isValid(): bool
    {
        foreach ($this->serviceContainer->keys() as $validator) {
            $validatorContainer = $this->serviceContainer->offsetGet($validator);
            if (!$validatorContainer->isValid($this->formData)) {
                $this->errors = $validatorContainer->errors;
                return false;
            }
        }

        return true;
    }

    public function __call($name, $arguments)
    {
        if (!$this->serviceContainer->offsetExists($name)) {
            $validation = new $this->providers[$name]($arguments[0]);
            $name = spl_object_hash($validation);
            $this->serviceContainer->offsetSet($name, $validation);
        }

        return $this->serviceContainer->offsetGet($name);
    }


    public function getErrors()
    {

    }

    public function getFirstErrorString()
    {
        return current($this->errors);
    }
}