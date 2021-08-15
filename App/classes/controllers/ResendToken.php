<?php

//    namespace App\classes\controllers\user;
    namespace App\classes\controllers;

    use App\classes\abstract\Controller;

    class ResendToken extends Controller
    {
        protected string $title = 'Получить новый код', $content = 'PAGE NOT FOUND!';

        public function __invoke()
        {
            $this->content = $this->page->assign('user', $this->user)->assign('errors', $this->errors)->render('validation');
            parent::__invoke();
        }
    }
