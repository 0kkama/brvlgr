<?php

    namespace App\classes\utility;

    use App\classes\models\Categories;

    class ArticleInspector extends ErrorsInspector
    {
        protected function checkCategory() : string
        {
            $cat_id = $this->forms->get('category');

            if (!is_numeric($cat_id)) {
                return 'Указана не корректная категория';
            }
            $value = Categories::findOneBy('id', $cat_id);
            if(!$value->exist()) {
                return 'Указана не существующая категория';
            }
                return '';
        }
    }
