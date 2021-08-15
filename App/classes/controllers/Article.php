<?php


    namespace App\classes\controllers;


    use App\classes\abstract\ControllerActing;
    use App\classes\Config;
    use App\classes\models\Article as Publication;
    use App\classes\utility\ErrorsInspector;
    use App\classes\utility\UserErrorsInspector;

    class Article extends ControllerActing
    {

        protected string $title = 'PAGE NOT FOUND!', $content = 'PAGE NOT FOUND!';
        protected Publication $article;
        protected UserErrorsInspector $inspector;

        protected function add() : void
        {
            $this->title = 'Добавить публикацию';
            $this->article = new Publication();
            $this->checkUser()->sendData();
            $this->content = $this->page->assign('article', $this->article)->assign('errors', $this->errors)->render('add');
        }

        protected function read() : void
        {
            $this->checkArtID()->getArt()->checkArtExist();
            $this->title = $this->article->getTitle();
            $this->content = $this->page->assign('article', $this->article)->assign('author', $this->article->author())->render('article');
        }

        protected function edit() : void
        {
            $this->title = 'Редактировать статью';
            $this->checkUser()->checkArtID()->getArt()->checkArtExist()->sendData();
            $this->content = $this->page->assign('article', $this->article)->assign('errors', $this->errors)->render('add');
        }

        protected function delete() : void
        {
            $this->checkUser()->checkArtID()->getArt()->checkArtExist()->article->delete();
            header('Location: ' . Config::getInstance()->BASE_URL);
        }

        private function sendData() : void
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $fields = extractFields(array_keys($_POST),$_POST);
                $this->article->setTitle($fields['title'])->setText($fields['text'])->setCategory($fields['category'])->setAuthor($this->user->getLogin())->setAuthorId($this->user->getId());
                ($this->inspector = new UserErrorsInspector($this->article, $this->errors))->conductInspection();

                if ($this->errors->isEmpty()) {
                    $this->article->save();
                    header('Location: /article/read/' . $this->article->getId());
                }
            }
        }

        protected function checkUser() : self
        {
            if (!$this->user->hasUserRights()) {
                Error::deadend(403);
            }
            return $this;
        }

        protected function checkArtID () : self
        {
            if (!is_numeric($this->params['id']) || empty($this->params['id'])) {
                Error::deadend(400);
            }
            return $this;
        }

        protected function checkArtExist() : self
        {
            if (!$this->article->exist()) {
                Error::deadend(404, 'Статья не найдена');
            }
            return $this;
        }

        protected function getArt() : self
        {
            $this->article = Publication::findOneBy('id', $this->params['id']);
            return $this;
        }

        public function __invoke()
        {
            $this->action($this->params['action']);
            parent::__invoke();
        }
    }

