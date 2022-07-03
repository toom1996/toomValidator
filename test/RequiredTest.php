<?php

use EasyValidator\Validator;
use PHPUnit\Framework\TestCase;

class RequiredTest extends TestCase
{
    public function testRequired()
    {
        $validator = new Validator(['name' => 'toom']);

        $validator->required(['name']);

        $this->assertSame(true, $validator->isValid());

        $validator = new Validator(['name' => '']);

        $validator->required(['name']);

        $this->assertSame(false, $validator->isValid());
    }

    public function testRequiredWithStrict()
    {
        $validator = new Validator(['name' => '']);

        $validator->required(['name'])->isStrict(true);

        $this->assertSame(true, $validator->isValid());
    }

}