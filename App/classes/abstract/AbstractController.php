<?php


    namespace App\classes\abstract;


    use App\classes\models\User;
    use App\classes\utility\UsersErrors;
    use App\classes\View;
    use JetBrains\PhpStorm\Pure;

    abstract class AbstractController
    {
        /** @var View template object for rendering content section and then whole current page
         * @var User|object object of current user or empty object of respective class
         * @var string $title title of page
         * @var string $content content of page for substitution in layout template
         */
        protected View $page;
        protected User $user;
        protected array $params;
        protected string $title, $content, $id;

        #[Pure] public function __construct(array $params, View $templateEngine)
        {
            $this->page = $templateEngine;
            $this->params = $params;
        }

        public function __invoke()
        {
            $this->page->assign('title', $this->title)->assign('content', $this->content)->assign('user', $this->user)->display('layout');
        }

    }
