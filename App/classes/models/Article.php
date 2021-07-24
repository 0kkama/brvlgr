<?php

    namespace App\classes\models;

    use App\classes\Db;
    use App\classes\abstract\AbstractModel;
    use App\classes\utility\UsersErrors;
    use App\interfaces\HasAuthor;
    use App\interfaces\HasTitle;
    use App\traits\SetControlTrait;
    use App\classes\exceptions\DbException;
    use Exception;

    class Article extends AbstractModel implements HasAuthor, HasTitle
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
        //                              TRAIT!!!!!!!!!
        use  SetControlTrait;

        /**
         * @throws Exception
         */
        public static function getLast(int $limit) : ?array
        {
//            if ( $limit < 7 ) {
//                (new DbException('Недостаточное количество айтемов', 404))->setAlert('')->setParam("")->throwIt();
//            }

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
         * @return UsersErrors
         */
        public function getErrors() : UsersErrors
        {
            return $this->errors;
        }

        public function getID() : ?string
        {
            return $this->id;
        }

        public function getAuthor() : User
        {
            return User::findById($this->author_id);
        }

        public function getTitle()
        {
            return $this->title;
        }
    }
