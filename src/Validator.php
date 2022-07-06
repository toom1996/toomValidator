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

    /**
     * Single instance application.
     * @var Factory|null
     */
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

    /**
     * I18n formatter.
     * @var I18n|null
     */
    private static ?I18n $i18n = null;

    public function __construct(array $options = [])
    {
        $this->serviceContainer = new ServiceContainer();

        $this->providers = array_merge($this->providers, $this->getValidatorProviders());
    }

    public static function app(): ?Factory
    {
        if (self::$app === null) {
            self::$app = new Factory();
        }

        return self::$app;
    }

    /**
     * Load validation values.
     * @param array $values
     */
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

    /**
     * Returns is valid.
     * @return bool
     */
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

    /**
     * @return I18n|null
     */
    public static function getI18n(): I18n
    {
        if (self::$i18n === null) {
            self::$i18n = new I18n();
        }

        return self::$i18n;
    }
}
