<?php


    namespace App\traits;


    trait DebugTrait
    {
        public function __debugInfo()
        {
            return get_object_vars($this);
        }
    }
