<?php

    namespace App\classes\models;

    use App\classes\abstract\models\AbstractView;
    use App\classes\abstract\models\ViewArticle;

    class ViewAllArticles extends ViewArticle
    {
        protected const TABLE_NAME = 'view_all_articles';
        protected string $id, $login, $user_id, $title, $text, $category, $moder, $cat_id, $cat_stat, $date;

    }
