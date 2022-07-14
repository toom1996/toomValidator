<?php

namespace EasyValidator;

class Formatter extends ServiceProvider
{
    public function formatMessage($message, $params = [])
    {
        $placeholders = [];
        foreach ((array) $params as $name => $value) {
            $placeholders['{' . $name . '}'] = $value;
        }

        return ($placeholders === []) ? $message : strtr($message, $placeholders);
    }
}
