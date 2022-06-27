<?php

namespace EasyValidator;

use EasyValidator\validators\Required;

/**
 * @method Required required(array $attributes = [])
 */
class Validator
{
    protected ?ServiceContainer $serviceContainer;

    public array $attributes = [];

    public array $providers = [
        'required' => Required::class
    ];

    public function __construct(array $attributes = [])
    {
        $this->serviceContainer = new ServiceContainer();

        $this->providers = array_merge($this->providers, $this->_getValidatorProviders());

        $this->attributes = $attributes;
    }

    private function _getValidatorProviders(): array
    {
        return [
            'required' => Required::class
        ];
    }

    public function isValid()
    {
        foreach ($this->serviceContainer->keys() as $validator) {
            $this->serviceContainer->offsetGet($validator)->isValid($this->attributes);
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


}