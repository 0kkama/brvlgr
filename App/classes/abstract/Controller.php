<?php


    namespace App\classes\abstract;

    use App\classes\controllers\Error;
    use App\classes\models\Article;
    use App\classes\utility\UsersErrors;
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
        protected UsersErrors $errors;
        protected array $params;
        protected string $title, $content, $id;

        public function __construct($params)
        {
            $this->page = new View();
            $this->user = User::getCurrent(Config::getInstance()->SESSIONS);
            $this->errors = new UsersErrors();
            $this->params = $params;
        }

        protected function action(string $action) : void
        {
            if (method_exists($this, $action)) {
                $this->$action();
            } else {
                Error::deadend(400);
            }
        }

        public function __invoke()
        {
            $this->page->assign('title', $this->title)->assign('content', $this->content)->assign('user', $this->user)->display('layout');
        }

        /**
         * @return string
         */
        public function getId() : string
        {
            return $this->id;
        }
    }

    // TODO 1. Напишите класс базового контроллера. Вынесите в него метод action($action) и примените его.
    //  Этот метод должен делать следующее. Вызвать метод access() контроллера.
    //  Если получен результат false - вывести сообщение "Доступ закрыт" и прекратить работу.
    //  Вызвать соответствующий экшн по имени.
