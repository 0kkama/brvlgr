<?php

    namespace App\classes\models;

    use App\classes\abstract\exceptions\CustomException;
    use App\classes\abstract\models\Model;
    use App\classes\exceptions\MagickException;
    use App\classes\utility\Db;
    use App\traits\ArticlePresentmentTrait;
    use App\traits\SetControlTrait;
    use Exception;

    class Article extends Model
    {
        protected const TABLE_NAME = 'articles';
        protected string $title, $text, $moder;
        protected ?string $date = null;

        //                              TODO убрать трейт?
        use  SetControlTrait, ArticlePresentmentTrait;

        /**
         * @throws Exception
         */
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

        public function __toString() : string
        {
            return $this->date .'<br>';
        }

        /**
         * @throws MagickException|CustomException
         */
        public function __call($name, $arguments) : object
        {
            if ($name === 'author') {
                return User::findOneBy(type: 'id', subject: $this->author_id);
            }
            throw (new MagickException('Вызов несуществующего метода','456',''))->setParam("Метод: $name Арг: $arguments");
        }

        /**
         * Return TRUE if object has NOT empty fields $id and $date
         * @return bool
         */
        public function exist() : bool
        {
            return (!empty($this->id) && !empty($this->date));
        }

        //<editor-fold desc="setters======================">
        public function setTitle(string $title) : Article
        {
            $this->title = $title;
            return $this;
        }

        public function setText(string $text) : Article
        {
            $this->text = $text;
            return $this;
        }

        public function setStatus(int $moder) : self
        {
            $result = match($moder) {
                1 => '1',
                2 => '2',
                default => '0',
            };

            $this->moder = $result;
            return $this;
        }

//        public function setTags(string $tags) : Article
//        {
//            $this->tags = $tags;
//            return $this;
//        }

        /**
         * @param string $author_id
         */
//        public function setAuthorId(string $author_id) : void
//        {
//            $this->author_id = $author_id;
//        }
        //</editor-fold>

        public function getTitle() : string
        {
            return $this->title;
        }
        //</editor-fold>

    }
