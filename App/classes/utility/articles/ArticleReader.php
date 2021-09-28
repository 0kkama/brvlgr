<?php

    namespace App\classes\utility\articles;

    use App\classes\controllers\Error;
    use App\classes\models\User;
    use App\classes\utility\Config;
    use App\interfaces\ViewArticleInterface;

    class ArticleReader
    {
        protected User $user;
        protected ViewArticleInterface $view;

        public function __construct(string $id, ViewArticleInterface $view)
        {
            $this->user = User::getCurrent();
            $this->view = $view::findOneBy('id', $id);
        }

        public function execute() : ViewArticleInterface
        {
//            $this->checkArtID($id);
//            $this->user = User::getCurrent();
//            $this->view = $view::findOneBy('id', $id);
            if (!$this->checkReadRights()) {
                Error::deadend(404, 'Статья не найдена или недоступна');
            }
            return $this->view;
        }

        protected function checkReadRights() : bool
        {
            // article doesn't exist
            if ( !$this->view->exist() ) {
                //                Error::deadend(404);
                return false;
            }
            // user has superrights
            if ( $this->user->getRights() >= Config::getInstance()->getRightsLvl('moderator') ) {
                return true;
            }
            //          статья на модерации, но пользователь не зарегистрирован или не является автором статьи
            if ( $this->view->getModer() === '0' ) {
                if (!$this->user->exist()) {
                    return false;
                }
                if ( $this->view->getUserId() !== $this->user->getId() ) {
                    return false;
                }
            }
            return true;
        }
    }
