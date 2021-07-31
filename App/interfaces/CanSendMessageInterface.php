<?php


    namespace App\interfaces;


    interface CanSendMessageInterface
    {
        public static function sendMessage(string $subject, string $message);
    }
