<?php

    namespace App\classes\models;

    use App\classes\abstract\models\AbstractView;
    use App\classes\utility\Db;

    class ViewArticle extends AbstractView
    {
        protected const TABLE_NAME = 'view_articles';
        protected string $id, $login, $user_id, $title, $text, $category, $cat_id, $date;

        public function exist() : bool
        {
            return (!empty($this->id) && !empty($this->login));
        }

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

        /**
         * @return string
         */
        public function getTitle(): string
        {
            return $this->title;
        }

        /**
         * @return string
         */
        public function getLogin(): string
        {
            return $this->login;
        }

        /**
         * @return string
         */
        public function getText(): string
        {
            return $this->text;
        }

        /**
         * @return string
         */
        public function getCategory(): string
        {
            return $this->category;
        }

        /**
         * @return string
         */
        public function getCatId(): string
        {
            return $this->cat_id;
        }

        /**
         * @return string
         */
        public function getId(): string
        {
            return $this->id;
        }

        /**
         * @return string
         */
        public function getDate(): string
        {
            return $this->date;
        }

        /**
         * @return string
         */
        public function getUserId(): string
        {
            return $this->user_id;
        }

        public function  getFormattedContent() : string
        {
            $string = $this->text;
            $paragraphs = [];
            $arr = explode(PHP_EOL, $string);

            foreach ($arr as $row) {
                $paragraphs[] = '<p>' . $row . '</p>';
            }

            return implode(PHP_EOL, $paragraphs);
        }

        public function getBriefContent() : string
        {
            return (mb_substr($this->text, 0, 150) . '...') ?? '';
        }

        public function __toString() : string
        {
            return 'Дата: ' . $this->date . '<br>' . 'Автор: ' . $this->login . '<br>' . 'Категория: '. $this->category . '<br>';
        }
    }
