<?php


    namespace App\classes\utility;


    use App\classes\models\User;

    /**
     * Extend for inspection model/User class
     */
    class UserErrorsInspector extends ErrorsInspector
    {
        protected static array $regexp =
            [
                'email' => '~[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}~i',
                'password' => '~[A-z0-9._%+-*]{8,30}~',
                'login' => '~[\d\w._%+\-!&()]{3,20}~',
            ];
        /**
         * Check correctness of email and it's unique
         * @return string
         */
        protected function checkEmail() : string
        {
//            $mail = $this->object->getEmail();
            $mail = $this->forms->getElement('email');
            if (!(preg_match(self::$regexp['email'], $mail))) {
                return 'Некорректное название почтового ящика';
            }
//            if (($this->object::findOneBy('email', $mail))->exist()) {
            if ((User::findOneBy('email', $mail))->exist()) {
                return 'Такой почтовый ящик уже используется';
            }
            return '';
        }

        protected function checkLogin() : string
        {
            $login = $this->forms->getElement('login');
            if (!(preg_match(self::$regexp['login'], $login))) {
                return 'Некорректный логин';
            }
//            if (($this->object::findOneBy('login', $login))->exist()) {
            if ((User::findOneBy('login', $login))->exist()) {
                return 'Подобный логин уже используется';
            }
            return '';
        }

        protected function checkPasswords() : string
        {
            $password1 = $this->forms->getElement('password1');
            $password2 = $this->forms->getElement('password2');

            if ($password1 !== $password2) {
                return 'Пароли не совпадают';
            }

            $length1 = mb_strlen($password1);
            if ($length1 < 8 || $length1 > 30) {
                return 'Длина пароля должна быть от 8 до 30 символов';
            }
            return '';
        }
    }
