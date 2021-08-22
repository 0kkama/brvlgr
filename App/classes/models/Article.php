<?php

    namespace App\classes\models;

    use App\classes\abstract\models\Model;
    use App\classes\utility\Db;
    use App\classes\abstract\exceptions\CustomException;
    use App\classes\exceptions\MagickException;
    use App\classes\utility\containers\ErrorsContainer;
    use App\interfaces\HasAuthorInterface;
    use App\interfaces\HasTableInterface;
    use App\interfaces\HasTitleInterface;
    use App\interfaces\PaginatedInterface;
    use App\traits\SetControlTrait;
    use Exception;

    class Article extends Model implements HasAuthorInterface, HasTitleInterface, PaginatedInterface, HasTableInterface
    {
        protected const TABLE_NAME = 'articles';
        protected string $title, $text, $author, $category, $author_id;
        protected static array $checkList = [];
        protected static array $errorsList =
            [
                'title' => 'Отсутствует заголовок',
                'text' => 'Отсутствует текст статьи',
                'category' => 'Не указана категория',
            ];
        //                              TODO убрать трейт?
        use  SetControlTrait;

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
            return $this->date .'<br>'. 'Автор: ' . $this->author . '<br>' .' Категория: ' . $this->category;
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
        //</editor-fold>

        //<editor-fold desc="getters======================">
        public function getBriefContent() : string
        {
            return (mb_substr($this->text, 0, 150) . '...') ?? '';
        }

        public function getFormattedContent() : string
        {
            $string = $this->text;
            $paragraphs = [];
            $arr = explode(PHP_EOL, $string);

            foreach ($arr as $row) {
                $paragraphs[] = '<p>' . $row . '</p>';
            }

            return implode(PHP_EOL, $paragraphs);
        }

        /**
         * @return ErrorsContainer
         */
        public function getErrorsContainer() : ErrorsContainer
        {
            return $this->errors;
        }

        public function getAuthor() : User
        {
            return User::findOneBy(type: 'id', subject: $this->author_id);
        }

        public function getTitle() : string
        {
            return $this->title;
        }
        //</editor-fold>

    }
