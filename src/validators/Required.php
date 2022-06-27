<?php

namespace EasyValidator\validators;

use EasyValidator\BaseValidation;

class Required extends BaseValidation
{

    protected bool $isStrict = false;

    /**
     * @param bool $isStrict
     * @return $this
     */
    public function strict(bool $isStrict = false): Required
    {
        $this->isStrict = $isStrict;
        return $this;
    }

    public function isValid(&$value)
    {
        foreach ($this->validationAttributes as $attribute) {
            var_dump($this->valid($value[$attribute]));
        }
    }

    public function valid($value)
    {
        if ($this->isStrict) {
            return $value !== null;
        }else{
            return $value !== '' && $value != [] && $value !== null;
        }
    }

}