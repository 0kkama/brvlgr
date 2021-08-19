<?php

    namespace App\traits;

    trait CountableTrait
    {
        public function count() : int
        {
            return count($this->data);
        }
    }
