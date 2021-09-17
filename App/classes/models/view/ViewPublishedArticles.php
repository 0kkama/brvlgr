<?php

    namespace App\classes\models\view;

    use App\classes\abstract\models\ViewArticle;
    use App\classes\utility\Db;

    class ViewPublishedArticles extends ViewArticle
    {
        protected const TABLE_NAME = 'view_published_articles';

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
