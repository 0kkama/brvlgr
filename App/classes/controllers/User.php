<?php


    namespace App\classes\controllers;

    use App\classes\abstract\ControllerAbstraction;
    use App\classes\abstract\ControllerActing;
    use App\classes\abstract\ControllerSelector;
    use App\classes\controllers\Error;
    use App\classes\View;

    class User extends ControllerSelector
    {

        public function action(string $action) : void
        {
            $class = ucfirst($action);
            $controller = 'App\classes\controllers\user\\' . $class;
            if (class_exists($controller)) {
                (new $controller([], new View))();
            } else {
                Error::deadend(400, 'Неизвестное действие');
            }
        }
    }
