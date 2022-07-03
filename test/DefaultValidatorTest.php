<?php

use PHPUnit\Framework\TestCase;

class DefaultValidatorTest extends TestCase
{
    public function testDefaultValidator()
    {
        $validator = new \EasyValidator\Validator();

        $validator->default(['age'])->value('888');

        $this->assertSame(false, $validator->isValid());

    }
}