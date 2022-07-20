<?php

use EasyValidator\Validator;

interface ValidatorFactoryInterface
{
    public function rules(Validator $validator);
}