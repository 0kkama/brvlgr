<?php

    namespace App\classes\utility\loggers;

    use App\interfaces\CanSendMessageInterface;
    use Exception;
    use App\classes\utility\Config;

    class LoggerSelector
    {
        public static function authentication($message) : void
        {
            $path = Config::getInstance()->AUTH_LOG;
            (new LoggerForAuth($message . PHP_EOL, $path))->write();
        }

        public static function publication($message) : void
        {
            $path = Config::getInstance()->AUTH_LOG;
            (new LoggerForAuth($message . PHP_EOL, $path))->write();
        }

        public static function exception(Exception $ex, CanSendMessageInterface $sender) : void
        {
            (new LoggerForExceptions($ex, $sender))();
        }

    }
