<?php


class SingleInstanceTest extends \PHPUnit\Framework\TestCase
{
    public function testSingleInstance()
    {
        \EasyValidator\Validator::$app->test;
    }

}