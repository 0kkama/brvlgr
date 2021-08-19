<?php

    namespace App\traits;

    trait JsonSeializableTrait
    {
        public function jsonSerialize() : array
        {
            return $this->data;
        }
    }
