<?php


    namespace App\traits;


    use App\classes\exceptions\CustomException;
    use App\classes\exceptions\MagickException;

    trait SetControlTrait
    {
        /**
         * @throws MagickException|CustomException
         */
        public function __get($property)
        {
            if (property_exists($this, $property)) {
                return $this->$property;
            }
            (new MagickException('Запрос несуществующего метода через SetControlTrait'))->setAlert('Некорректный запрос')->throwIt();
        }

        public function __set($name, $value)
        {
            $haystack = array_keys(get_class_vars(static::class));
            $key = in_array($name, $haystack, true);
                if ($key) {
                    $this->$name = $value;
                    return $this;
                }
        }

        public function __isset($name)
        {
            return isset($this->$name);
        }
    }
