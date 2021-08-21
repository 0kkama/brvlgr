<?php


    namespace App\classes\abstract;


    use App\classes\models\Navigation;
    use App\classes\models\User;
    use App\classes\utility\NavigationBar;
    use App\classes\View;
    use JetBrains\PhpStorm\Pure;

    abstract class ControllerAbstraction
    {
        /** @var View template object for rendering content section and then whole current page
         * @var User|object object of current user or empty object of respective class
         * @var string $title title of page
         * @var string $content content of page for substitution in layout template
         */
        protected View $page;
        protected User $user;
        protected NavigationBar $menu;
        protected array $params;
        protected string $title, $content, $id;

        public function __construct(array $params, View $templateEngine)
        {
            $this->page = $templateEngine;
            $this->params = $params;
            ($this->menu = new NavigationBar())->addArray(Navigation::getAll());
        }

        public function __invoke()
        {
            $this->page->assignArray([
                'title' => $this->title, 'menu' => $this->menu, 'content' => $this->content,'user' => $this->user,]
            )->display('layout');
//            assign('title', $this->title)->assign('content', $this->content)->assign('user', $this->user)->display('layout');
        }

        /**
         * @return string
         */
        public function getId() : string
        {
            return $this->id;
        }
    }
