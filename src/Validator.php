<?php

namespace EasyValidator;

use EasyValidator\exceptions\InvalidValidatorException;
use EasyValidator\validators\DefaultValidator;
use EasyValidator\validators\FunctionValidator;
use EasyValidator\validators\NumberValidator;
use EasyValidator\validators\Required;
use EasyValidator\validators\StringValidator;

/**
 * @method Required required(array $attributes = [])
 * @method StringValidator string(array $attributes = [])
 * @method NumberValidator number(array $attributes = [])
 * @method DefaultValidator default(array $attributes = [])
 * @method FunctionValidator function(array $attributes = [])
 */
class Validator
{
    protected ?ServiceContainer $serviceContainer;

    public static $app;

    public array $formData = [];

    public array $errors;

    public array $providers = [
        'required' => Required::class
    ];

    public function __construct(array $formData = [])
    {
        $this->serviceContainer = new ServiceContainer();

        $this->providers = array_merge($this->providers, $this->getValidatorProviders());

        $this->formData = $formData;
    }

    protected function getValidatorProviders(): array
    {
        return [
            'required' => Required::class,
            'string' => StringValidator::class,
            'number' => NumberValidator::class,
            'default' => DefaultValidator::class,
            'function' => FunctionValidator::class,
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

    /**
     * @throws InvalidValidatorException
     */
    public function __call($name, $arguments)
    {
        if (!$this->serviceContainer->offsetExists($name)) {
            if (!isset($this->providers[$name])) {
                throw new InvalidValidatorException("Unknown validator name `$name`, are you config it?");
            }
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
