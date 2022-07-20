<?php

use EasyValidator\Validator;
use PHPUnit\Framework\TestCase;

class StringValidatorTest extends TestCase
{
    public function testStringValidator()
    {
        $validator = new Validator();

        $validator->loadValidationValues(['name' => []]);

        $validator->string(['name']);

        $this->assertSame(false, $validator->isValid());
    }

    public function testStringValidatorWithLength()
    {
        // min length and max length.
        // e.g: name length >= 1  and  <= 6.
        $validator = new Validator();

        $validator->loadValidationValues(['name' => '小明']);

        $validator->string(['name'])->min(2)->max(6);

        $this->assertSame(true, $validator->isValid());


        // equal and not equal.
        // e.g: captcha must be input 4 length.
        $validator = new Validator();

        $validator->loadValidationValues(['captcha' => 'abcd']);

        $validator->string(['captcha'])->equal(4);

        $this->assertSame(true, $validator->isValid());

        // equal and not equal.
        // e.g: captcha can't input 4 length.
        $validator = new Validator();

        $validator->loadValidationValues(['captcha' => 'abcd']);

        $validator->string(['captcha'])->notEqual(4);

        $this->assertSame(false, $validator->isValid());
    }
}
