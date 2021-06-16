<?php

    namespace App\traits;

    use App\classes\models\Article;

    trait SingletonTrait
    {
        private static $instance = null;

        public static function getInstance() : static
                {
            return self::$instance ?? (self::$instance = new self());
//            return static::$instance ?? (static::$instance = new static());
        }

        private function __construct()
        {
            // блокировка конструктора для возможности создания только через статический метод getInstance
        }

        private function __clone()
        {
            //            запрет клонирования объекта
        }

        public function __wakeup()
        {
            //           запрет десериализации объекта
        }
    }
