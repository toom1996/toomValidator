<?php

namespace EasyValidator;

use EasyValidator\exceptions\InvalidValidatorException;
use EasyValidator\exceptions\UnknownComponentException;
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
 * @property Formatter $formatter
 */
class Validator
{
    /**
     * Provider container.
     * @var ServiceContainer|null
     */
    public ?ServiceContainer $serviceContainer;

    /**
     * Validation values.
     * @var array
     */
    public array $validationValues = [];

    public array $config = [
        'i18n' => [
            'language' => 'zh-cn'
        ]
    ];

    public array $errors = [];

    /**
     * You can customize validation rules by configuring this property.
     * @var array
     */
    public array $providers = [
        'i18n' => I18n::class,
        'formatter' => Formatter::class,
        'required' => RequiredValidator::class,
        'string' => StringValidator::class,
        'number' => NumberValidator::class,
        'default' => DefaultValidator::class,
        'function' => FunctionValidator::class,
    ];

    /**
     * Defined validator language.
     * @var string
     */
    public string $language = 'en-us';

    public function __construct(array $config = [])
    {
        $this->serviceContainer = new ServiceContainer();

        $this->registerProviders();
    }

    /**
     * Load validation values.
     * @param array $values
     */
    public function loadValidationValues(array $values = [])
    {
        $this->validationValues = $values;
    }

    protected function registerProviders()
    {

    }

    /**
     * Returns is valid.
     * @return bool
     */
    public function isValid(): bool
    {
        foreach ($this->serviceContainer->keys() as $key) {
            $validatorContainer = $this->serviceContainer->offsetGet($key);
            if (!$validatorContainer->isValid($this->validationValues)) {
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

            /** @var BaseValidation $validation */
            $validation = new $this->providers[$name]($this, []);

            // set validation attributes.
            $validation->setValidationAttributes($arguments[0]);

            $name = spl_object_hash($validation);

            $this->serviceContainer->offsetSet($name, $validation);
        }

        return $this->serviceContainer->offsetGet($name);
    }

    /**
     * Returns component.
     * @param string $name
     * @return mixed
     * @throws UnknownComponentException
     */
    public function __get(string $name)
    {
        if (!$this->serviceContainer->offsetExists($name)) {
            if (!isset($this->providers[$name])) {
                throw new UnknownComponentException("Unknown component name `$name`, are you config it?");
            }

            $component = new $this->providers[$name]($this, $this->config[$name] ?? []);

            if (!$component instanceof ServiceProvider) {
                throw new UnknownComponentException("Invalid component name `$name`, it must be instance of " . ServiceProvider::class);
            }

            $this->serviceContainer->offsetSet($name, $component);
        }

        return $this->serviceContainer->offsetGet($name);
    }

    public function getFirstErrorString()
    {
        return current($this->errors);
    }
}
