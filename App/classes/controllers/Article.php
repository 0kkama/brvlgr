<?php


    namespace App\classes\controllers;


    use App\classes\abstract\controllers\ControllerActing;
    use App\classes\utility\Config;
    use App\classes\models\Article as Publication;
    use App\classes\utility\containers\FormsWithData;
    use App\classes\utility\loggers\LoggerSelector;
    use App\classes\utility\UserErrorsInspector;

    class Article extends ControllerActing
    {

        protected string $title = 'PAGE NOT FOUND!', $content = 'PAGE NOT FOUND!';
        protected Publication $article;
        protected UserErrorsInspector $inspector;
        protected FormsWithData $forms;
        protected static array $errorsList = [
            'title' => 'Отсутствует заголовок',
            'text' => 'Отсутствует текст статьи',
            'category' => 'Не указана категория',
        ];

        public function __invoke()
        {
            $this->action($this->params['action']);
            parent::__invoke();
        }

        protected function create() : void
        {
            $this->title = 'Добавить публикацию';
            $this->article = new Publication();
            if ($this->checkUser()->prepareData()) {
                $this->sendDataAndMessage('Пользователь ' . $this->user->getLogin() . ' добавил статью ' . $this->article->getTitle());
            }
            $this->content = $this->page->assign('article', $this->article)->assign('errors', $this->errors)->render('add');
        }

        protected function read() : void
        {
            $this->checkArtID()->getArt()->checkArtExist();
            $this->title = $this->article->getTitle();
            $this->content = $this->page->assign('article', $this->article)->assign('author', $this->article->author())->render('article');
        }

        protected function update() : void
        {
            $this->title = 'Редактировать статью';
            $this->checkUser()->checkArtID()->getArt()->checkArtExist();
            if ($this->prepareData()) {
                $this->sendDataAndMessage('Пользователь ' . $this->user->getLogin() . ' обновил статью ' . $this->article->getTitle());
            }
            $this->content = $this->page->assign('article', $this->article)->assign('errors', $this->errors)->render('add');
        }

        protected function delete() : void
        {
            $this->checkUser()->checkArtID()->getArt()->checkArtExist()->article->delete();
            header('Location: ' . Config::getInstance()->BASE_URL);
        }

        protected function prepareData() : bool
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $fields = array_keys(self::$errorsList);
                ($this->forms = new FormsWithData())->extractPostForms($fields, $_POST)->validateForms(false);
                ($this->inspector = new UserErrorsInspector($this->forms, $this->errors, self::$errorsList))->conductInspection();
                $this->article->setFields($this->forms)->setAuthor($this->user->getLogin())->setAuthorId($this->user->getId());
                return true;
            }
            return false;
        }

        protected function sendDataAndMessage(string $message) : void
        {
            if ($this->sendData()) {
                LoggerSelector::publication($message);
                $this->relocate();
            }
        }

        protected function sendData() : bool
        {
            return $this->errors->isEmpty() && $this->article->save();
        }

        protected function relocate() : void
        {
            header('Location: /article/read/' . $this->article->getId());
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
    }

