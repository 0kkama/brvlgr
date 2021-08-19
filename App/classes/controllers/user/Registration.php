<?php

    namespace App\classes\controllers\user;

    use App\classes\abstract\Controller;
    use App\classes\Config;
    use App\classes\models\Sessions;
    use App\classes\models\User;
    use App\classes\utility\FormsWithData;
    use App\classes\utility\LoggerForAuth;
    use App\classes\utility\Registrator;
    use App\classes\utility\UserErrorsInspector;
    use phpDocumentor\Reflection\DocBlock\Tags\Formatter\AlignFormatter;

    //    class Registration extends ControllerActing
    class Registration extends Controller
    {

        protected string $title = 'Регистрация';
        protected User $candidate;
        protected Registrator $registrator;
        protected UserErrorsInspector $inspector;
        private static array $checkList = ['checkEmail', 'checkLogin', 'checkPasswords'];
        private static array $errorsList =
            [
                'login' => 'Логин отсутствует или некорректен',
                'firstName' => 'Отсутствует имя',
                'middleName' => 'Отсутствует отчество',
                'lastName' => 'Отсутствует фамилия',
                'email' => 'Не указан почтовый ящик',
                'password1' => 'Пароль отсутствует или некорректен',
                'password2' => 'Необходимо ввести повторный пароль',
            ];

        public function __invoke()
        {
            ($this->registrator = new Registrator())::checkUserAbsent($this->user);
            $this->candidate = new User();
            $forms = new FormsWithData();

            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                $this->registrator->
                $forms->extractPostForms(array_keys(self::$errorsList), $_POST);
                $checkbox = (bool)$_POST['remember'];
                $this->inspector = new UserErrorsInspector($forms, $this->errors, self::$errorsList);
                $this->inspector->conductInspection(self::$checkList);
                $this->candidate->setFields($forms);

                if ($this->errors->isEmpty()) {
                    $this->candidate->makeHash($forms->get('password1'));
                    $this->candidate->save();
                    (new Sessions($this->candidate))->createNewSession($checkbox);
                    (new LoggerForAuth('Зарегистрирован пользователь '. $this->candidate))->write();
                    header('Location: '. Config::getInstance()->BASE_URL);
                }
            }

            $this->content = $this->page->assign('candidate', $this->candidate)->assign('errors', $this->errors)->render('registration');
            parent::__invoke();
        }
    }
