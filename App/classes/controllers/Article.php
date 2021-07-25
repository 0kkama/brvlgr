<?php


    namespace App\classes\controllers;


    use App\classes\abstract\Controller;
    use App\classes\Config;
    use App\classes\models\Article as Publication;

    class Article extends Controller
    {

        protected string $title = 'PROBLEM!', $content = 'BIG PROBLEM!';
        protected Publication $article;

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
            $this->content = $this->page->assign('title', $this->title)->assign('article', $this->article)->assign('author', $this->article->author())->render('article');
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
                $this->article->setTitle($fields['title'])->setText($fields['text'])->setCategory($fields['category'])->setAuthor($this->user->login)->setAuthorId($this->user->id);
                $this->errors = $this->article->save()->getErrors();

                if (!$this->errors->__invoke()) {
                    header('Location: /article/read/' . $this->article->getID());
                }
            }
        }

        protected function checkUser() : self
        {
            if (!$this->user->exist()) {
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
            $this->article = Publication::findBy('id', $this->params['id']);
            return $this;
        }

        public function __invoke()
        {
            $this->action($this->params['action']);
            parent::__invoke();
        }
    }

