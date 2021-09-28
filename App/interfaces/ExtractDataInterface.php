<?php

    namespace App\interfaces;

    interface ExtractDataInterface
    {
        public function extractDataFrom($object, array $fields): void;
    }
