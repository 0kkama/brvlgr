<?php


    namespace App\classes\controllers;

    use App\classes\abstract\controllers\ControllerSelector;
    use App\classes\utility\View;

    class User extends ControllerSelector
    {

        public function action() : void
        {
            $class = ucfirst($this->params['action']);
            $controller = 'App\classes\controllers\user\\' . $class;
            if (class_exists($controller)) {
                (new $controller([], new View))();
            } else {
                Error::deadend(400, 'Неизвестное действие');
            }
        }
    }
