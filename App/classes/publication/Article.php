<?php

    namespace App\classes\publication;

    use App\classes\Db;
    use App\classes\Govno;
    use App\interfaces\HasAuthor;
    use App\interfaces\HasId;
    use App\interfaces\Readable;
    use App\interfaces\UserInterface;
    use App\traits\DebugTrait;
    use App\traits\GetSetTrait;
    use App\traits\SetControlTrait;

    class Article extends Govno /*implements Readable, HasId, HasAuthor*/
    {
        // TODO поменять имя таблицы на articles в дальнейшем
        protected const TABLE_NAME = 'news';
        protected $id = null,  $date = null;
        protected string $title, $text, $author, $category, $author_id;

        use  SetControlTrait;

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

        /**
         * @param string $author_id
         */
        public function setAuthorId(string $author_id) : void
        {
            $this->author_id = $author_id;
        }

        public function getBriefContent() : ?string
        {
            return mb_substr($this->text, 0, 150) . '...';
        }

        public function __toString() : string
        {
            return "$this->title <br> $this->author <br> $this->date";
        }

        public function __call($name, $arguments) : object
        {
            if ($name === 'author') {
                return User::findById($this->author_id);
            }
        }

        public function __invoke()
        {
            // TODO: Implement __invoke() method.
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
