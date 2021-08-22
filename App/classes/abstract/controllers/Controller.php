<?php

    namespace App\classes\abstract\controllers;

    use App\classes\exceptions\ExceptionWrapper;
    use App\classes\utility\Config;
    use App\classes\models\User;
    use App\classes\utility\containers\ErrorsContainer;
    use App\classes\utility\View;

    abstract class Controller extends ControllerAbstraction
    {
        /** @var View template object for rendering content section and then whole current page
         * @var User|object object of current user or empty object of respective class
         * @var string $title title of page
         * @var string $content content of page for substitution in layout template
         */
        protected ErrorsContainer $errors;
        /**
         * @throws ExceptionWrapper
         */
        public function __construct(array $params, View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            $this->user = User::getCurrent(Config::getInstance()->SESSIONS);
            $this->errors = new ErrorsContainer();
        }
    }
