<?php


    namespace App\classes\abstract;

    use App\classes\controllers\Error;
    use App\classes\exceptions\CustomException;
    use App\classes\exceptions\ExceptionWrapper;
    use App\classes\models\Article;
    use App\classes\utility\UsersErrors;
    use App\classes\View;
    use App\classes\models\User;
    use App\classes\Config;

    abstract class Controller extends AbstractController
    {
        /** @var View template object for rendering content section and then whole current page
        * @var User|object object of current user or empty object of respective class
        * @var string $title title of page
        * @var string $content content of page for substitution in layout template
         */
        protected UsersErrors $errors;

        /**
         * @throws ExceptionWrapper
         */
        public function __construct(array $params, View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            $this->user = User::getCurrent(Config::getInstance()->SESSIONS);
            $this->errors = new UsersErrors();
        }

        protected function action(string $action) : void
        {
            if (method_exists($this, $action)) {
                $this->$action();
            } else {

                Error::deadend(400);
            }
        }

        /**
         * @return string
         */
        public function getId() : string
        {
            return $this->id;
        }
    }
