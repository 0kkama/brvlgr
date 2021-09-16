<?php


    namespace App\classes\controllers;

    use App\classes\abstract\controllers\ControllerSelector;
    use App\traits\ControllerSelectorActionTrait;

    class User extends ControllerSelector
    {
        private string $path = 'App\classes\controllers\user\\';

        use ControllerSelectorActionTrait;
    }
