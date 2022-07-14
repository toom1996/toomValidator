<?php

namespace EasyValidator;

abstract class ServiceProvider
{
    /**
     * @var Validator
     */
    public Validator $validator;

    public function __construct(Validator $validator, array $config = [])
    {
        $this->validator = $validator;

        $this->loadConfig($config);
    }

    /**
     * Load config.
     * @param $config
     */
    public function loadConfig($config)
    {
        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
