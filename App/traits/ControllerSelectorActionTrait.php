<?php

    namespace App\traits;

    use App\classes\controllers\Error;
    use App\classes\utility\View;

    trait ControllerSelectorActionTrait
    {
        public function action(): void
        {
            $class = ucfirst($this->params['action']);
            $controller = $this->path . $class;
            if (class_exists($controller)) {
                (new $controller($this->params, new View))();
            } else {
                Error::deadend(400, 'Неизвестное действие');
            }
        }
    }
