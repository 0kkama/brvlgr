<?php

    namespace App\classes\abstract\controllers;

    abstract class ControllerSelector
    {
        protected array $params;

        public function __construct(array $params)
        {
            $this->params = $params;
        }

        public function __invoke()
        {
            $this->action();
        }

        abstract public function action() : void;
    }
