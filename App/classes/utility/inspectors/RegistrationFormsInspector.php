<?php

    namespace App\classes\utility\inspectors;

    use App\traits\CheckPassTrait;

    class RegistrationFormsInspector extends FormsInspector
    {
        protected static array $regexp =
            [
                'email' => '~[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}~i',
                'password' => '~[A-z0-9._%+-*]{8,30}~',
                'login' => '~[\d\w._%+\-!&()]{3,20}~',
            ];

        protected static array $errorsAbsence =
            [
                'login' => 'Логин отсутствует или некорректен',
                'firstName' => 'Отсутствует имя',
                'middleName' => 'Отсутствует отчество',
                'lastName' => 'Отсутствует фамилия',
                'email' => 'Не указан почтовый ящик',
                'password1' => 'Пароль отсутствует или некорректен',
                'password2' => 'Необходимо ввести повторный пароль',
            ];

        protected static array $errorsMessages =
            [
                'email' => [
                    0 => 'Некорректное название почтового ящика',
                    1 => 'Такой почтовый ящик уже используется'],
                'login' => [
                    0 => 'Некорректный логин',
                    1 => 'Подобный логин уже используется'],
                'passwords' => [
                    0 => 'Пароли не совпадают',
                    1 => 'Длина пароля должна быть от 8 до 30 символов'],
            ];

        protected function prepareData() : void
        {
            $this->forms['email'] = mb_strtolower($this->forms->get('email'));

            foreach (['login', 'firstName', 'middleName', 'lastName'] as $item) {
                $this->forms[$item] = my_mb_ucfirst(mb_strtolower($this->forms->get($item)));
            }
        }

        use CheckPassTrait;
    }
