<?php

    namespace App\classes\controllers\user;

    use App\classes\abstract\controllers\ControllerEntering;
    use App\classes\utility\inspectors\RegistrationFormsInspector;


    class Registration extends ControllerEntering
    {
        protected string $title = 'Регистрация';
        protected static string $action = 'createNewUser';
        protected static array $fields = ['login', 'firstName', 'middleName', 'lastName', 'email', 'password1', 'password2'];

        protected function entering() : void
        {
            $this->inspector = new RegistrationFormsInspector();
            parent::entering();
            $this->content = $this->page->assign('forms', $this->forms)->assign('errors', $this->errors)->render('users/registration');
        }
    }
