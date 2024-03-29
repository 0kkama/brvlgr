<?php


    namespace App\classes\controllers\user;


    use App\classes\abstract\controllers\ControllerEntering;
    use App\classes\utility\inspectors\LoginFormsInspector;

    class Login extends ControllerEntering
    {
        protected string $title = "Войти на сайт";
        protected static string $action = 'loginUser';
        protected static array $fields = ['login', 'password'];

        protected function entering() : void
        {
            $this->inspector = new LoginFormsInspector();
            parent::entering();
            $this->content = $this->page->assign('errors', $this->errors)->render('users/login');
        }
    }

