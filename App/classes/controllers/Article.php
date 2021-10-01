<?php

    namespace App\classes\controllers;

    use App\classes\abstract\controllers\ControllerSelector;
    use App\traits\ControllerSelectorActionTrait;


    /**
     * This controller selects and invoke the appropriate action controller with the article,
     * depending on the array $params
     * @param array $params
     */
    class Article extends ControllerSelector
    {
        private string $path = 'App\classes\controllers\article\\';

        use ControllerSelectorActionTrait;
    }
