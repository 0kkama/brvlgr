<?php


    namespace App\classes\abstract\models;


    abstract class Model extends AbstractModel
    {
        protected ?string $date = null;
        protected static array $errorsList;
        protected static array $checkList;

        public function getErrorsList() : array
        {
            return static::$errorsList;
        }

        public function getCheckList() : array
        {
            return $this::$checkList;
        }
    }
