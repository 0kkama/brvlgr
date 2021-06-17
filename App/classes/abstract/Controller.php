<?php


    namespace App\classes\abstract;

    use App\classes\MyErrors;
    use App\classes\View;
    use App\classes\models\User;
    use App\classes\Config;
    use App\traits\ValidateArticleTrait;

    abstract class Controller
    {
        /** @var View
        * @var User|object
        * @var string $title
        * @var string $content */
        protected View $page;
        protected User $user;
        protected MyErrors $errors;
        protected string $title = 'PROBLEM!';
        protected string $content = 'BIG PROBLEM!';

        use ValidateArticleTrait;

        /** Controller constructor */
        public function __construct()
        {
            $this->page = new View();
            $this->user = User::getCurrent(Config::getInstance()->SESSIONS);
        }

        /**
         * invocation
         */
        public function __invoke()
        {
            $this->page->assign('title', $this->title)->assign('content', $this->content)->assign('user', $this->user)->display('layout');
//            var_dump($this->user);
        }
    }
