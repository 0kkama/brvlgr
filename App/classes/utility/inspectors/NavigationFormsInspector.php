<?php

    namespace App\classes\utility\inspectors;

    class NavigationFormsInspector extends FormsInspector
    {

        protected static array $regexp =
            [
                'title' => '~^[а-яё]{3,}$~iu',
                'url' => '~^/{1}[a-z/.]{3,}$~',
                'order' => '~^[1-9]{1,1}[0-9]*$~',
            ];

        protected static array $errorsAbsence =
            [
                'title' => 'Введите название элемента навигации',
                'url' => 'Введите URL для элемента навигации',
                'order' => 'Укажите порядковый номер элемента',
                'status' => 'Укажите статус: main, user, noname, admin, forbid',
            ];

        protected static array $errorsMessages =
            [
                'title' => [
                    0 => 'Название должно содержать только кириллические символы',
                    1 => 'Пункт с таким названием уже существует'],
                'url' => [
                    0 => 'URL должен содержать только латинские символы и правые слэши',
                    1 => 'Такой URL уже используется'],
                'order' => [
                    0 => 'Порядок должен быть целым положительным числом',
                    1 => 'Такой порядковый номер уже используется'],
            ];

        protected function prepareData() : void
        {
            $this->forms['title'] = my_mb_ucfirst(mb_strtolower($this->forms->get('title')));
            $this->forms['url'] = mb_strtolower($this->forms->get('url'));
        }



    }

