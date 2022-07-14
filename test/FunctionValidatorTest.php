<?php

use PHPUnit\Framework\TestCase;

class FunctionValidatorTest extends TestCase
{
    public function testFunctionValidator()
    {
        $validator = new \EasyValidator\Validator();

        $validator->function(['age'])->method([\EasyValidator\Factory::class, 'test']);

        $validator->isValid();
        die;
    }
}
