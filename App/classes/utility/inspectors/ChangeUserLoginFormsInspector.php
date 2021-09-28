<?php

    namespace App\classes\utility\inspectors;

    class ChangeUserLoginFormsInspector extends FormsInspector
    {
        protected static array $regexp =
            [
                'login' => '~[0-9A-z._%+\-!&()]{3,20}~',
            ];

        protected static array $errorsAbsence =
            [
                'login' => 'Введите новый логин',
            ];

        protected static array $errorsMessages =
            [
                'login' => [
                    0 => 'Логин должен быть от 3 до 20 символов. <br> Допустимы латинские символы, цифры и символы . _ % + - ! & ( )',
                    1 => 'Такой логин уже существует',
                    2 => 'Новый логин совпадает со старым',
                ]
            ];

        protected function prepareData(): void
        {
            $this->forms['login'] = my_mb_ucfirst(mb_strtolower($this->forms->get('login')));
        }

        protected function checkLogin() : string
        {
            $subject = 'login';
            $this->container->add($this->formalCheck($subject));
            $this->container->add($this->duplicateCheck($subject));

            if ($this->forms->get($subject) === $this->model->getLogin()) {
                return static::$errorsMessages[$subject][2];
            }
            return '';
        }
    }
