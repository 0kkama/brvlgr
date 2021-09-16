<?php

    namespace App\classes\models;

    use App\classes\abstract\models\AbstractView;
    use App\classes\abstract\models\ViewArticle;
    use App\classes\utility\Db;

    class ViewPublishedArticles extends ViewArticle
    {
        protected const TABLE_NAME = 'view_published_articles';
        protected string $id, $login, $user_id, $title, $text, $category, $moder, $cat_id, $cat_stat, $date;

        public static function getLast(int $quantity) : ?array
        {
            if ($quantity <= 0) {
                $limit = '';
            } else {
                $limit = 'LIMIT ' . $quantity;
            }

            $db = Db::getInstance();
            $sql = 'SELECT * FROM ' . self::TABLE_NAME . ' ORDER BY `id` DESC ' . $limit;
            return $db->queryAll($sql, [], self::class);
        }
    }
