<?php


    namespace App\traits;


    trait SetControlTrait
    {
        public function __get($name)
        {
            return $this->$name;
        }

        public function __set($name, $value)
        {
            $haystack = array_keys(get_class_vars(static::class));
            $key = in_array($name, $haystack, true);
                if ($key) {
                    $this->$name = $value;
                    return $this;
                }

//            if(isset($this->$name)) {
//                $this->$name = $value;
//                return $this;
//            }
        }

        public function __isset($name)
        {
            return isset($name);
        }
    }
