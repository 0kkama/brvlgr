<?php

    namespace App\classes\controllers;

    use App\classes\abstract\controllers\ControllerSelector;
    use App\traits\ControllerSelectorActionTrait;


    class Article_ extends ControllerSelector
    {
        private string $path = 'App\classes\controllers\article\\';

        use ControllerSelectorActionTrait;
    }
