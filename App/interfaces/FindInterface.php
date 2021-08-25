<?php

    namespace App\interfaces;

    interface FindInterface
    {
        public static function getAll() : array;
        public static function findOneBy(string $type, string $subject) : static;
    }
