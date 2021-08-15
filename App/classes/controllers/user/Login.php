<?php


    namespace App\classes\controllers\user;
//    namespace App\classes\controllers;


    use App\classes\abstract\Controller;
    use App\classes\Config;
    use App\classes\models\Sessions;
    use App\classes\models\User;
    use App\classes\utility\ErrorsContainer;
    use App\classes\View;
    use Exception;

    class Login extends Controller
    {
        protected ErrorsContainer $error;
        protected string $title = "Войти на сайт";
        protected ?string $relocation;
        protected User $entering;

        public function __construct(array $params, View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            $this->relocation = Config::getInstance()->BASE_URL;
        }

        public function __invoke()
        {
            $this->checkUser();
            $this->error = new ErrorsContainer();
            if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
                //                TODO проверка на пустые поля
                $login = val($_POST['login']);
                $password = val($_POST['password']);
                $this->loginUser($login, $password);
            }

            $this->content = $this->page->assign('loginErr', $this->error)->render('login');
            parent::__invoke();
        }

        private function checkUser() : void
        {
            if ($this->user->exist()) {
                header('Location: ' . $this->relocation); exit();
            }
        }

        public function loginUser(string $login, string $password) : void
        {
            $this->entering = User::checkPassword($login, $password);
            if ($this->entering->exist()) {
                $sessions = new Sessions();
                $sessions->createNewSession($this->entering);
            } else {
                $this->error[] = 'Неверный логин или пароль';
            }
        }
    }
