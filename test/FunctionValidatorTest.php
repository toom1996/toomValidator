<?php

use PHPUnit\Framework\TestCase;

class FunctionValidatorTest extends TestCase
{
    public function testFunctionValidator()
    {
        $validator = new \EasyValidator\Validator();

        $validator->loadValidationValues(['name' => 'toom']);

        $validator->function(['name'])->method(function(&$value, $attr) use ($validator) {
            $validator->errors[] = '123123';
            echo '123';
        });

        var_dump($validator->isValid());
        var_dump($validator->getFirstErrorString());
        die;
    }
}
