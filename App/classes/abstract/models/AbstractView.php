<?php

    namespace App\classes\abstract\models;

    use App\interfaces\FindInterface;
    use App\traits\FindTrait;

    abstract class AbstractView implements FindInterface
    {
        protected const TABLE_NAME = 'abstract_view';

        use FindTrait;
    }
