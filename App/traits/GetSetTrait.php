<?php

    namespace App\traits;

    trait GetSetTrait
    {
        public function __get($name)
        {
            return $this->$name;
        }

        public function __set($name, $value)
        {
            $this->$name = $value;
            return $this;
        }

        public function __isset($name)
        {
            return isset($name);
        }
    }
