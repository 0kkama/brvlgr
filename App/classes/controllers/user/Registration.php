<?php

    namespace App\classes\controllers\user;

    use App\classes\abstract\Controller;
    use App\classes\models\User;
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
        private array $checkList = ['checkEmail', 'checkLogin', 'checkPasswords'];

        public function __invoke()
        {
            $this->registrator = new Registrator();
            $this->registrator->checkUserAbsent($this->user);
            $this->candidate = new User();

            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                $this->inspector = new UserErrorsInspector($this->candidate, $this->errors);
                $this->registrator->setFields($_POST, $this->candidate)->checkFields($this->inspector, $this->checkList);

                if ($this->errors->isEmpty()) {
                    $this->registrator->createNewUser($this->candidate);
                }
            }

            $this->content = $this->page->assign('candidate', $this->candidate)->assign('errors', $this->errors)->render('registration');
            parent::__invoke();
        }
    }
