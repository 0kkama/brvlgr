<?php
    namespace classes\test;
    use App\classes\Config;

    class Article
    {
        protected $id, $title, $content, $elements , $author, $date, $category;

        public function __construct($id)
        {
            $this->id = $id;
            $this->elements = $this->getArticle();
            $this->title = $this->elements['title'];
            $this->content = $this->elements['content'];
            $this->author = $this->elements['author'];
            $this->date = $this->elements['date'];
            $this->category = $this->elements['category'];
            unset($this->elements);
        }

        protected function getArticle() : array
        {
            $subject = Config::getInstance()->PATH_TO_ARTICLES . $this->id . '.json';

            if (!is_readable($subject)) {
                trigger_error('Запрошена не существуюущая статья');
                exit();
            }

            $article  = file_get_contents($subject, true);

            if (false === $article) {
                return [];
            }
                return json_decode($article, true);
        }

        /**
         * @return string
         */
        public function getId() : string
        {
            return (string) $this->id;
        }

        /**
         * @return mixed
         */
        public function getDate() : string
        {
            return $this->date;
        }

        /**
         * @return mixed
         */
        public function getAuthor() : string
        {
            return $this->author;
        }

        /**
         * @return mixed
         */
        public function getCategory() : string
        {
            return $this->category;
        }

        /**
         * @return string
         */
        public function getTitle() : string
        {
            return $this->title;
        }

        /**
         * @return string
         */
        public function getContent() : string
        {
            return $this->content;
        }

        public function getBriefContent() : string
        {
            return mb_substr($this->content, 0, 150);
        }

        /**
         * @return array
         */
        public function getFull(): array
        {
            return
            [
                'id' => $this->getId(),
                'title' => $this->getTitle(),
                'content' => $this->getContent(),
                'author' => $this->getAuthor(),
                'category' => $this->getCategory(),
                'date' => $this->getDate(),
            ];
        }

        public function debugg() : void
        {
            //            var_dump($subject = $this->pathToFiles . $this->id);
        }
    }


