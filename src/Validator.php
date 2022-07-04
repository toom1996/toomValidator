<?php

namespace EasyValidator;

use EasyValidator\exceptions\InvalidValidatorException;
use EasyValidator\validators\DefaultValidator;
use EasyValidator\validators\FunctionValidator;
use EasyValidator\validators\NumberValidator;
use EasyValidator\validators\RequiredValidator;
use EasyValidator\validators\StringValidator;

/**
 * @method RequiredValidator required(array $attributes = [])
 * @method StringValidator string(array $attributes = [])
 * @method NumberValidator number(array $attributes = [])
 * @method DefaultValidator default(array $attributes = [])
 * @method FunctionValidator function(array $attributes = [])
 */
class Validator
{
    /**
     * Provider container.
     * @var ServiceContainer|null
     */
    protected ?ServiceContainer $serviceContainer;

    public static ?Factory $app = null;

    /**
     * Validation values.
     * @var array
     */
    public array $validationValues = [];

    public array $errors;

    /**
     * You can customize validation rules by configuring this property.
     * @var array
     */
    public array $providers = [];

    public function __construct(array $formData = [], $options = [])
    {
        $this->serviceContainer = new ServiceContainer();

        $this->providers = array_merge($this->providers, $this->getValidatorProviders());

        $this->validationValues = $formData;
    }

    public static function app(): ?Factory
    {
        if (self::$app === null) {
            self::$app = new Factory();
        }

        return self::$app;
    }

    public function loadValidationValues(array $values = [])
    {
        $this->validationValues = $values;
    }

    /**
     * Predefined validators.
     * @return string[]
     */
    protected function getValidatorProviders(): array
    {
        return [
            'string' => StringValidator::class,
            'number' => NumberValidator::class,
            'default' => DefaultValidator::class,
            'required' => RequiredValidator::class,
            'function' => FunctionValidator::class,
        ];
    }

    public function isValid(): bool
    {
        foreach ($this->serviceContainer->keys() as $validator) {
            $validatorContainer = $this->serviceContainer->offsetGet($validator);
            if (!$validatorContainer->isValid($this->validationValues)) {
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
