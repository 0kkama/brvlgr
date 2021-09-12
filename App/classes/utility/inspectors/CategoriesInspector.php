<?php

    namespace App\classes\utility\inspectors;

    use App\classes\utility\ComplicatedFetchQuery;
    use App\classes\utility\Db;

    class CategoriesInspector extends Inspector
    {
        protected static array $regexp =
            [
                'url' => '~^[a-z/]{3,}$~',
                'title' => '~^[а-яё]{3,}$~iu',
            ];

        protected static array $errorsAbsence =
            [
                'title' => 'Введите название категории',
                'url' => 'Введите URL для категории',
            ];

        protected static array $errorsMessages =
            [
                'title' => [0 => 'В названии должны быть только кириллические символы',
                            1 => 'Такое название уже используется'],
                'url' => [0 => 'URL должен состоять только из прописных латинских букв',
                          1 => 'Такой URL уже используется'],
            ];

        protected function prepareData() : void
        {
            $this->forms['title'] = my_mb_ucfirst(mb_strtolower($this->forms->get('title')));
            $this->forms['url'] = mb_strtolower($this->forms->get('url'));
        }
    }
