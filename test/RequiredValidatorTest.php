<?php

use EasyValidator\Validator;
use PHPUnit\Framework\TestCase;

class RequiredValidatorTest extends TestCase
{
    public function testRequired()
    {
        $validator = new Validator();

        $validator->loadValidationValues(['name' => 'toom']);

        $validator->required(['name']);

        $this->assertSame(true, $validator->isValid());

        $validator = new Validator();

        $validator->loadValidationValues(['name' => '5']);

        $validator->required(['name', 'age']);

        $this->assertSame(false, $validator->isValid());
    }

    public function testRequiredWithStrict()
    {
        $validator = new Validator();

        $validator->loadValidationValues(['name' => '']);

        $validator->required(['name'])->isStrict(true);

        $this->assertSame(true, $validator->isValid());
    }

}