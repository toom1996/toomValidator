<?php

namespace EasyValidator;


class Test implements \ValidatorFactoryInterface
{
    public function rules(Validator $validator)
    {
        $validator->required(['test'])->isStrict(true);
    }
}
