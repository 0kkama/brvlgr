<?php


    namespace App\classes\abstract\controllers;

    use App\classes\controllers\Error;

    abstract class ControllerActing extends Controller
    {
        protected function action(string $action) : void
        {
            if (method_exists($this, $action)) {
                $this->$action();
            } else {
                Error::deadend(400);
            }
        }
    }
