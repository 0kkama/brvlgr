<?php

//    namespace App\classes\controllers\user;
    namespace App\classes\controllers;

    use App\classes\abstract\Controller;

    class Validation extends Controller
    {
        protected string $title = 'Подтверждение почтового ящика', $content = 'PAGE NOT FOUND!';

        public function __invoke()
        {
//            $this->checkUser();
//            $this->candidate = new User(new ErrorInspectorUser());
//            if ('POST' === $_SERVER['REQUEST_METHOD']) {
//                $this->setFields();
//            }

            $this->content = $this->page->assign('user', $this->user)->assign('errors', $this->errors)->render('validation');
            parent::__invoke();
        }
    }
