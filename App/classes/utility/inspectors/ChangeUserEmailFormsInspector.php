<?php

    namespace App\classes\utility\inspectors;

    use App\classes\models\User;

    class ChangeUserEmailFormsInspector extends FormsInspector
    {
        protected static array $regexp =
            [
                'email' => '~[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}~i',
            ];

        protected static array $errorsAbsence =
            [
                'email' => 'Введите новый email',
            ];

        protected static array $errorsMessages =
            [
                'email' => [
                    0 => 'Некорректное имя для почтового ящика',
                    1 => 'Такой почтовый ящик уже используется',
                    2 => 'Новое имя ящика совпадает со старым',
                ]
            ];

        protected function prepareData(): void
        {
            $this->forms['email'] = mb_strtolower($this->forms->get('email'));
        }

        protected function checkEmail() : string
        {
            $subject = 'email';
            $this->container->add($this->formalCheck($subject));
            $this->container->add($this->duplicateCheck($subject));

            if ($this->forms->get($subject) === $this->model->getEmail()) {
                return static::$errorsMessages[$subject][2];
            }
                return '';
        }
    }
