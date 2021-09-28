<?php

    namespace App\classes\utility\inspectors;

    use App\traits\CheckPassTrait;

    class ChangeUserPassFormsInspector extends FormsInspector
    {
        protected static array $regexp =
            [
                'password' => '~[A-z0-9._%+-*]{8,30}~',
            ];

        protected static array $errorsAbsence =
            [
                'password1' => 'Пароль отсутствует или некорректен',
                'password2' => 'Необходимо ввести повторный пароль',
            ];

        protected static array $errorsMessages =
            [
                'passwords' => [
                    0 => 'Пароли не совпадают',
                    1 => 'Длина пароля должна быть от 8 до 30 символов'
                ],
            ];

        protected function prepareData(): void
        {
            // TODO: Implement prepareData() method.
        }

        use CheckPassTrait;
    }
