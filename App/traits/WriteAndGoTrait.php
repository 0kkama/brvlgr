<?php

    namespace App\traits;

    use App\classes\utility\loggers\LoggerSelector;

    trait WriteAndGoTrait
    {
        protected function writeAndGo(string $action, string $destination) : void
        {
            $message = 'Пользователь ' . $this->user->getLogin() . " $action статью " .$this->article->getId() .' ' . $this->article->getTitle();
            LoggerSelector::publication($message);
            header("Location: $destination");
        }
    }
