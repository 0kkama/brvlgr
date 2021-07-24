<?php


    namespace App\classes\controllers;


    use App\classes\abstract\Controller;
    use App\classes\Config;
    use App\classes\exceptions\ExceptionWrapper;
    use App\classes\utility\UsersErrors;
    use App\classes\View;
    use Exception;
    use JsonException;

    class Login extends Controller
    {
        protected UsersErrors $error;
        protected ?string $relocation;

        public function __construct(array $params, View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            $this->title = "Войти на сайт";
            $this->relocation = Config::getInstance()->BASE_URL;
        }

        private function checkUser() : void
        {
            if ($this->user->__invoke()) {
                header('Location: ' . $this->relocation); exit();
            }
        }

        /**
         * @throws ExceptionWrapper|Exception
         */
        protected function loginUser() : void
        {
            $this->checkUser();
            $this->error = new UsersErrors();

            if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
                $this->user->setLogin(val($_POST['login']));
//                $this->user->pass = val($_POST['password']);

                if ($this->user->checkPassword(val($_POST['password']))) {
//                if ($this->user->checkPassword($this->user->pass)) {
                    $token = makeToken();
                    $fileName = Config::getInstance()->SESSIONS;
                    try {
                        $data = json_encode(['user' => $this->user->login, 'token' => $token, 'date' => time()], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
                    }
                    catch (JsonException $ex) {
                        (new ExceptionWrapper('Ошибка при запросе к базе данных', 500, $ex))->throwIt();
                    }
                    // помещаем данные в файл сессий, в куки и в массив сессий
                    setcookie('token', $token, time() + 86400, $this->relocation);
                    $_SESSION['user'] = $this->user->login;
                    $_SESSION['token'] = $token;

                    file_put_contents($fileName, $data . "\n", FILE_APPEND);
                    header('Location: ' . $this->relocation);
                }
                else {
                    // TODO решить проблему с неисчезающим при пустом обновлении всплывающим алертом о неверности логина или пароля
                    $this->error->add('Неверный логин или пароль');
                    $this->user->setLogin('');
//                    использовать сессию для передачи ошибки и переадресацию на страницу по новой
                }
            }
        }

        public function __invoke()
        {
            $this->loginUser();
            $this->content = $this->page->assign('loginErr', $this->error)->render('login');
            parent::__invoke();
//            $this->error->reset();
        }
}
