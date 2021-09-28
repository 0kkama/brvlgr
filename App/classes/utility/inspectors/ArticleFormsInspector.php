<?php

    namespace App\classes\utility\inspectors;

    use App\classes\models\Categories;

    class ArticleFormsInspector extends FormsInspector
    {
        protected static array $regexp = ['category' => '~^[1-9]{1}[0-9]*$~'];

        protected static array $errorsAbsence =
            [
                'title' => 'Отсутствует заголовок',
                'text' => 'Отсутствует текст статьи',
                'category' => 'Не указана категория',
            ];

        protected static array $errorsMessages =
            [
                'category' => [
                    0 => 'Указана не корректная категория',
                    1 => 'Указана не существующая категория',
                ],
            ];

        protected function prepareData(): void
        {
            // TODO: Implement prepareData() method.
        }

        protected function checkCategory(): string
        {
            $subject = 'category';
            $err = $this->formalCheck($subject);
            if (empty($err)) {
                return $err;
            }
            $category = Categories::findOneBy($subject, $this->forms->get($subject));
            if (!$category->exist()) {
                return self::$errorsMessages['category'][1];
            }
            return '';
        }
    }
