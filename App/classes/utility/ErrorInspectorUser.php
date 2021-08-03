<?php

    namespace App\classes\utility;

    class ErrorInspectorUser extends ErrorsInspector
    {
        protected static array $regexp =
            [
                'email' => '~[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}~i',
                //                'email' => '~[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}~',
                'password' => '~[A-z0-9._%+-*]{8,30}~',
                'login' => '~[\d\w._%+\-!&()]{3,20}~',
            ];
        /**
         * Check correctness of email and it's unique
         * @return string
         */
        protected function checkEmail() : string
        {
            $mail = $this->object->getEmail();
            if (!(preg_match(self::$regexp['email'], $mail))) {
                return 'Некорректное название почтового ящика';
            }
            if (($this->object::findOneBy('email', $mail))->exist()) {
                return 'Такой почтовый ящик уже используется';
            }
            return '';
        }

        protected function checkLogin() : string
        {
            $login = $this->object->getLogin();
            if (!(preg_match(self::$regexp['login'], $login))) {
                return 'Логин некорректной длины или включает в себя недопустимые символы';
            }
            if (($this->object::findOneBy('login', $login))->exist()) {
                return 'Подобный логин уже используется';
            }
            return '';
        }

        protected function checkHash() : string
        {
            $password = $this->object->getPass();
            $length = mb_strlen($password);
            if ($length < 8 || $length > 30) {
                return 'Длина пароля должна быть от 8 до 30 символов';
            }
            return '';
        }
    }