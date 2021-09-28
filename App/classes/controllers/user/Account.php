<?php

    namespace App\classes\controllers\user;

    use App\classes\abstract\controllers\Controller;
    use App\classes\controllers\Error;
    use App\classes\models\User;
    use App\classes\utility\FaceControl;

    class Account extends Controller
    {
        public function __invoke()
        {
            if (!FaceControl::checkUserRights(User::getCurrent(), 'user')) {
                Error::deadend(403, 'Необходима авторизация');
            }
            $this->title = 'Личный кабинет';
            $this->content = $this->page->assign('user', $this->user)->render('users/account');
            parent::__invoke();
        }
    }
