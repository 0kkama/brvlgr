<?php

    namespace App\classes\utility;

    use App\interfaces\CanSendMessageInterface;
    use Exception;

    class LoggerSelector
    {
        public static function authentication($message) : void
        {
            (new LoggerForAuth($message . PHP_EOL))->write();
        }

        public static function exception(Exception $ex, CanSendMessageInterface $sender) : void
        {
            (new LoggerForExceptions($ex, $sender))();
        }

    }
