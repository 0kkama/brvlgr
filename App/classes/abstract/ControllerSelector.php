<?php

    namespace App\classes\abstract;

    abstract class ControllerSelector
    {
        protected array $params;

        public function __construct(array $params)
        {
            $this->params = $params;
        }

        public function __invoke()
        {
            $this->action($this->params['action']);
        }

        abstract public function action(string $action) : void;
    }
