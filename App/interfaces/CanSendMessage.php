<?php


    namespace App\interfaces;


    interface CanSendMessage
    {
        public static function sendMessage(string $subject, string $message);
    }
