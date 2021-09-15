<?php

    namespace App\classes\controllers\user;

    use App\classes\abstract\controllers\Controller;

    class Account extends Controller
    {
        public function __invoke()
        {
            $this->title = 'Личный кабинет';
            $this->content = $this->page->assign('user', $this->user)->render('users/account');
            parent::__invoke();
        }
    }
