<?php


    namespace App\classes\controllers\user;


    use App\classes\abstract\Controller;
    use App\classes\models\Sessions;
    use App\classes\models\User;
    use App\classes\utility\ErrorsContainer;
    use App\classes\utility\FormsWithData;
    use App\classes\utility\LoggerForAuth;
    use App\classes\utility\Registrator;
    use App\classes\utility\UserErrorsInspector;

    class Login extends Controller
    {
        protected ErrorsContainer $error;
        protected string $title = "Войти на сайт";
        protected User $candidate;
        protected Registrator $registrator;
        protected UserErrorsInspector $inspector;
        private static array $checkList = ['checkEnter'];
        protected static array $errorsList = [
            'login' => 'Введите логин',
            'password' => 'Введите пароль',
        ];

        public function __invoke()
        {
            ($this->registrator = new Registrator())::checkUserAbsent($this->user);
            $this->candidate = new User();
            $forms = new FormsWithData();

            if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
                $forms->extractPostForms(array_keys(self::$errorsList), $_POST);
                $checkbox = (bool)$_POST['remember'];
                $this->inspector = new UserErrorsInspector($forms, $this->errors, self::$errorsList);
                $this->inspector->conductInspection(self::$checkList);
                $this->candidate->setFields($forms);

                if ($this->errors->isEmpty()) {
                    $this->candidate = User::findOneBy('login', $forms->get('login'));
                    (new Sessions($this->candidate))->createNewSession($checkbox);
                    (new LoggerForAuth('Пользователь ' . $this->candidate->getLogin() . 'вошёл в систему'))->write();
                }
            }
            $this->content = $this->page->assign('loginErr', $this->errors)->assign('candidate',$this->candidate)->render('login');
            parent::__invoke();
        }
    }
