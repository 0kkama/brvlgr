<?php

    namespace App\classes\models;

    use App\classes\Db;
    use App\classes\abstract\Govno;
    use App\interfaces\HasAuthor;
    use App\interfaces\HasId;
    use App\interfaces\HasTitle;


    class News extends Govno implements HasAuthor, HasTitle
    {
        protected string $title, $text, $author, $category;
        protected $id = null, $date = null;
        protected const TABLE_NAME = 'news';

        public function getId() : string
        {
            return $this->id;
        }

        public function getTitle() : string
        {
            return $this->title;
        }

        public function getBriefContent() : string
        {
            return mb_substr($this->text, 0, 150) . '...';
        }

        public function getDate() : string
        {
            return $this->date;
        }

        public function getAuthor() : string
        {
            return $this->author;
        }

        public function getCategory() : string
        {
            return $this->category;
        }

        public function debugg() : void
        {
            //            var_dump($this->list);
        }

    }

    //        public function __construct(...$id)
    //        {
    //            parent::__construct();
    //            $this->id = $id;
    //            $this->articles = $this->getArtsArray();
    //        }
    //
    //        protected function getArtsArray() : array
    //        {
    //            $sql = 'SELECT * FROM news ORDER BY `date` DESC LIMIT 10';
    //            $dbh = new Db;
    //            $class = static::class;
    //            $result = $dbh->queryAll($sql, [], $class);
    ////
    //            foreach ($result as $index => &$item) {
    //                $item['brief'] = mb_substr($item['text'], 0, 150) . '...';
    //            }
    //
    //            return $result;
    //        }

    //        public function getArticles() : array
    //        {
    //            return $this->articles;
    //        }
