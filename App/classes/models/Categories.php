<?php

    namespace App\classes\models;

    use App\classes\abstract\models\AbstractModel;
    use App\classes\utility\Db;

    class Categories extends AbstractModel
    {
        protected const TABLE_NAME = 'categories';
        protected string $title;
        protected ?string $date;

        public function __toString() : string
        {
            return '<option value=' . (string)$this->id . '>' . $this->title . '</option>';
        }

        public function __invoke(string $cat_mark) : string
        {
            if ($this->title === $cat_mark || $cat_mark === $this->id) {
                return '<option value=' . "$this->id selected>"  . $this->title . '</option>';
            }
            return (string) $this;
        }

        public function exist() : bool
        {
            return (!empty($this->id) && !empty($this->title));
        }

        public static function getAllPart($what = 'id') : array
        {
            $db = Db::getInstance();
            $sql = 'SELECT '. $what .' FROM ' . static::TABLE_NAME;
            return $db->queryAll($sql, [], static::class);
        }


    }
