<?php

    namespace App\classes\abstract\models;

    use App\interfaces\ExistenceInterface;
    use App\interfaces\FindInterface;
    use App\interfaces\HasIdInterface;
    use App\interfaces\HasTableInterface;
    use App\traits\FindTrait;

    abstract class AbstractView implements FindInterface, HasIdInterface, HasTableInterface, ExistenceInterface
    {
        protected const TABLE_NAME = 'abstract_view';

        use FindTrait;

        public static function getTableName() : string
        {
            return static::TABLE_NAME;
        }
    }
