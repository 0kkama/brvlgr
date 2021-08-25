<?php

    namespace App\classes\controllers;

    use App\classes\abstract\controllers\ControllerActing;
    use App\classes\exceptions\DbException;
    use App\classes\exceptions\ExceptionWrapper;
    use App\classes\models\Categories;
    use App\classes\models\ViewArticle;
    use App\classes\utility\ArticleInspector;
    use App\classes\utility\ArticleRepresentation as Representation;
    use App\classes\utility\containers\CategoriesList;
    use App\classes\utility\containers\FormsForArticle;

    class Article extends ControllerActing
    {
        protected string $title = 'PAGE NOT FOUND!', $content = 'PAGE NOT FOUND!';
        protected Representation $representation;
        protected CategoriesList $categories;
        protected ArticleInspector $inspector;
        protected ViewArticle $viewArticle;
        protected FormsForArticle $forms;
        protected static array $checkList = ['checkCategory'];
        protected static array $errorsList =
            [
                'title' => 'Отсутствует заголовок',
                'text' => 'Отсутствует текст статьи',
                'category' => 'Не указана категория',
            ];

        public function __invoke()
        {
            $this->id = $this->params['id'];
            if ($this->params['action'] !== 'read') {
                Representation::checkUser($this->user);
                $this->forms = new FormsForArticle();
            }
            $this->representation = new Representation();
            $this->action($this->params['action']);
            parent::__invoke();
        }

        /**
         * @throws DbException
         * @throws ExceptionWrapper
         */
        protected function create() : void
        {
            $this->title = 'Добавить публикацию';
            ($this->categories = new CategoriesList())->addArray(Categories::getAll());
            if ($this->prepareData() && $this->errors->isEmpty()) {
                $this->representation->createArticle($this->forms, $this->user);
            }
            $this->content = $this->page->assign('forms', $this->forms)->assign('categories', $this->categories)->assign('errors', $this->errors)->render('add');
        }

        protected function read() : void
        {
            $this->viewArticle = $this->representation->readArticle($this->getId());
            $this->title = $this->viewArticle->getTitle();
            $this->content = $this->page->assign('article', $this->viewArticle)->assign('author', $this->viewArticle->getLogin())->render('article');
        }

        protected function update() : void
        {
            $this->title = 'Редактировать статью';
            ($this->categories = new CategoriesList())->addArray(Categories::getAll());
            $this->viewArticle = $this->representation->readArticle($this->getId());
            ($this->forms = new FormsForArticle())->extractViewArticle($this->viewArticle);
            if ($this->prepareData() && $this->errors->isEmpty()) {
                $this->representation->updateArticle($this->getId(), $this->forms, $this->user);
            }
            $this->content = $this->page->assign('forms', $this->forms)->assign('categories', $this->categories)->assign('errors', $this->errors)->render('add');
        }

        protected function delete() : void
        {
            $this->representation->deleteArticle($this->getId(), $this->user);
        }

        protected function prepareData() : bool
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $fields = array_keys(self::$errorsList);
                $this->forms->extractPostForms($fields, $_POST)->validateForms(false);
                ($this->inspector = new ArticleInspector($this->forms, $this->errors, self::$errorsList))->conductInspection(self::$checkList);
                return true;
            }
            return false;
        }
    }

