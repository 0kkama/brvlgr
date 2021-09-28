<?php

    namespace App\classes\utility\inspectors;

    class LoginFormsInspector extends FormsInspector
    {
        protected static array $errorsAbsence = ['login' => 'Введите логин', 'password' => 'Введите пароль'];
        protected static array $errorsMessages = ['enter' => [0 => 'Неверный логин или пароль'],];

        protected function checkEnter() : string
        {
            $password = $this->forms->get('password');
            $login = $this->forms->get('login');

            if (empty($login) || empty($password)) {
                return '';
            }

            $candidate = $this->model::checkPassword($login, $password);
            if (!($candidate->exist())) {
                return self::$errorsMessages['enter'][0];
            }
            return '';
        }

        protected function prepareData(): void
        {
            // TODO: Implement prepareData() method.
        }
    }
