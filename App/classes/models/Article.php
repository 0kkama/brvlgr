<?php

    namespace App\classes\models;

    use App\classes\Db;
    use App\classes\abstract\Govno;
    use App\classes\MyErrors;
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
        protected const TABLE_NAME = 'articles';
        protected ?string $id = null,  $date = null;
        protected string $title, $text, $author, $category, $author_id;
        protected array $replacements =
            [
                ':title' => 'Отсутствует заголовок',
                ':text' => 'Отсутствует текст статьи',
                ':category'=> 'Не указана категория',
            ];

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

        public function getBriefContent() : string
        {
            return (mb_substr($this->text, 0, 150) . '...') ?? '';
        }

        public function __toString() : string
        {
            return "$this->title <br> $this->author <br> $this->date" ?? '';
        }

        public function __call($name, $arguments) : object
        {
            if ($name === 'author') {
                return User::findById($this->author_id);
            }
        }

        /**
         * Return TRUE if object has NOT empty fields $id and $date
         * @return bool
         */
        public function exist () : bool
        {
            return (!empty($this->id) && !empty($this->date));
        }

        /**
         * @return MyErrors
         */
        public function getErrors() : MyErrors
        {
            return $this->errors;
        }
    }
