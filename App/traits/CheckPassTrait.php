<?php

    namespace App\traits;

    trait CheckPassTrait
    {
        protected function checkPasswords() : string
        {
            $password1 = $this->forms->get('password1');
            $password2 = $this->forms->get('password2');

            if(empty($password1) || empty($password2)) {
                return '';
            }
            if ($password1 !== $password2) {
                return self::$errorsMessages['passwords'][0];
            }
            $length1 = mb_strlen($password1);
            if ($length1 < 8 || $length1 > 30) {
                return self::$errorsMessages['passwords'][1];
            }
            return '';
        }
    }
