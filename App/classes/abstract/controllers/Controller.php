<?php


    namespace App\classes\abstract\controllers;


    use App\classes\models\Navigation;
    use App\classes\models\User;
    use App\classes\utility\ComplicatedFetchQuery;
    use App\classes\utility\containers\ErrorsContainer;
    use App\classes\utility\containers\NavigationBar;
    use App\classes\utility\Db;
    use App\classes\utility\MenuAssembler;
    use App\classes\utility\View;

    abstract class Controller
    {
        /** @var View template object for rendering content section and then whole current page
         * @var User|object object of current user or empty object of respective class
         * @var string $title title of page
         * @var string $content content of page for substitution in layout template
         */
        protected View $page;
        protected User $user;
        protected NavigationBar $menu;
        protected ErrorsContainer $errors;
        protected array $params;
        protected string $title, $content, $id;

        public function __construct(array $params, View $templateEngine)
        {
            $this->page = $templateEngine;
            $this->params = $params;
            $this->errors = new ErrorsContainer();
            $this->user = User::getCurrent();
            ($this->menu = new NavigationBar())->addArray(MenuAssembler::assemblyMenu($this->user));
        }

        public function __invoke()
        {
            $this->page->assignArray([
                'title' => $this->title, 'menu' => $this->menu,
                'content' => $this->content,'user' => $this->user,]
            )->display('layout');
        }

        /**
         * @return string
         */
        public function getId() : string
        {
            return $this->id;
        }
    }
