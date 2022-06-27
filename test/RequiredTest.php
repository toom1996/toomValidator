<?php

use PHPUnit\Framework\TestCase;
use EasyValidator\Validator;

class RequiredTest extends TestCase
{
    public function testRequired()
    {
        $validator = new Validator([
            'name' => 'tom',
            'age' => ''
        ]);
        $validator->required(['name', 'age']);
        $validator->required(['address'])->strict(true);

        $this->assertSame(true, $validator->isValid());
    }
}