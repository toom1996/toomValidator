<?php

namespace EasyValidator;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class I18n extends ServiceProvider
{
    public string $language = 'en-us';


    public function translate(string $message): string
    {
        return $this->handleMessage($message);
    }

    private function handleMessage($message): string
    {
        if ($this->language === 'en-us') {
            return $message;
        }

        $messageFile = $this->loadMessageFile();

        return $messageFile[$message] ?? $message;

    }

    private function loadMessageFile()
    {
        return include __DIR__ . "./i18n/$this->language/messages.php";

    }
}
