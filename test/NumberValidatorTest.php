<?php

use PHPUnit\Framework\TestCase;

class NumberValidatorTest extends TestCase
{
    public function testNumberValidator()
    {
        $validator = new \EasyValidator\Validator(['age' => '2.5']);

        $validator->number(['age'])->min(2.0)->max(2.4);

        $this->assertSame(false, $validator->isValid());

        var_dump($validator->getFirstErrorString());

    }
}
