<?php

    namespace App\classes\controllers\user;
//    namespace App\classes\controllers;

    use App\classes\abstract\ControllerActing;
    use App\classes\Config;
    use App\classes\models\User;
    use App\classes\utility\ErrorsInspector;
use App\classes\utility\Registrator;
use App\classes\utility\UserErrorsInspector;
    use App\classes\View;

    class Registration extends ControllerActing
    {

        protected string $title = 'Регистрация', $content = 'PAGE NOT FOUND!';
        protected User $candidate;
        protected UserErrorsInspector $inspector;
        protected Registrator $registrator;

//        public function checkUser() : void
//        {
//            if ($this->user->exist()) {
//                header('Location: '. Config::getInstance()->BASE_URL);
//                die();
//            }
//        }
//
//        public function setFields() : void
//        {
//            $keys = array_keys($_POST);
//            $fields = extractFields($keys,$_POST);
//
//            foreach ($fields as $index => &$field) {
//                if ($index !== 'password1' || $index !== 'password2') {
//                    $fields[$index] = val($field);
//                    $userMethod = 'set' . ucfirst($index);
//                    if (method_exists($this->candidate, $userMethod)) {
//                        $this->candidate->$userMethod($fields[$index]);
//                    }
//                }
//            }
//            unset($field);
//
//            $this->candidate->setPasswords($fields['password1'], $fields['password2']);
//            ($this->inspector = new UserErrorsInspector($this->candidate, $this->errors))->conductInspection();
//
//            if ($this->errors->isEmpty()) {
//                $this->candidate->makeHash();
//                $this->candidate->save();
//                header('Location: '. Config::getInstance()->BASE_URL);
//            }
//        }
//        public function __invoke()
//        {
//            $this->checkUser();
//            $this->candidate = new User();
//            if ('POST' === $_SERVER['REQUEST_METHOD']) {
//                $this->setFields();
//            }
//
//            $this->content = $this->page->assign('candidate', $this->candidate)->assign('errors', $this->errors)->render('registration');
//            parent::__invoke();
//        }

        public function __invoke()
        {
            $this->registrator = new Registrator();
            $this->registrator->checkUserAbsent($this->user);
            $this->candidate = new User();

            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                $this->inspector = new UserErrorsInspector($this->candidate, $this->errors);
                $this->registrator->setFields($_POST, $this->candidate)->checkFields($this->inspector);

                if ($this->errors->isEmpty()) {
                    $this->registrator->createNewUser($this->candidate);
                }
            }

//            var_dump($this->errors);
            $this->content = $this->page->assign('candidate', $this->candidate)->assign('errors', $this->errors)->render('registration');
            parent::__invoke();
        }
    }
