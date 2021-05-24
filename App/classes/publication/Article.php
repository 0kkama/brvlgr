<?php

    namespace App\classes\publication;

    use App\classes\Db;
    use App\classes\Govno;
    use App\interfaces\HasId;
    use App\interfaces\Readable;

    class Article extends Govno implements Readable, HasId
    {
        // TODO поменять имя таблицы на articles в дальнейшем
        protected const TABLE_NAME = 'news';
        protected $id = null,  $date = null;
        protected $title = '', $text = '', $author = '', $category = '';

        public function debugg() : void
        {
            $fields = get_object_vars($this);
            $value = array_filter($fields, static function ($var) { return isset($var); });
            var_dump($value);
            var_dump($this);
        }

        public static function getLast(int $limit) : ?array
        {
            $db = new Db;
            $sql = 'SELECT * FROM ' . self::TABLE_NAME . ' ORDER BY `date` DESC LIMIT ' . $limit;
            return $db->queryAll($sql, [], self::class);
        }

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

        public function setCategory(string $category) : Article
        {
            $this->category = $category;
            return $this;
        }

        public function setAuthor(string $author) : Article
        {
            $this->author = $author;
            return $this;
        }

        public function getId() : ?string
        {
            return $this->id;
        }

        public function getDate() : ?string
        {
            return $this->date;
        }

        public function getAuthor() : ?string
        {
            return $this->author;
        }

        public function getCategory() : ?string
        {
            return $this->category;
        }

        public function getTitle() : ?string
        {
            return $this->title;
        }

        public function getText() : ?string
        {
            return $this->text;
        }

        public function getBriefContent() : ?string
        {
            return mb_substr($this->text, 0, 150) . '...';
        }

        public function __toString() : string
        {
            // TODO: Implement __toString() method.
        }

        public function __invoke()
        {

        }

        public function modify(string $id) : ?Article
        {
            if (static::findById($id)) {
                $this->id = $id;
                return $this;
            }
            return null;
        }

        public function create(array $fields) : Article
        {
            $this->title = /*$title*/ $fields['title'];
            $this->text = /*$text*/ $fields['text'];
            $this->author = /*$author*/ $fields['author'];
            $this->category = /*$category*/ $fields['category'];
            return $this;
        }

    }
