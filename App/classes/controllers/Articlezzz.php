<?php

    namespace App\classes\controllers;

    use App\classes\abstract\controllers\ControllerActing;
    use App\classes\models\Categories;
    use App\classes\models\ViewPublishedArticles;
    use App\classes\utility\ArticleRepresentationzzz as Representation;
    use App\classes\utility\containers\CategoriesList;
    use App\classes\utility\containers\FormsForArticle;
    use App\interfaces\InspectorInterface;

    class Articlezzz extends ControllerActing
    {
        protected string $title = 'PAGE NOT FOUND!', $content = 'PAGE NOT FOUND!';
        protected Representation $representation;
        protected CategoriesList $categories;
        protected InspectorInterface $inspector;
        protected ViewPublishedArticles $viewArticle;
        protected FormsForArticle $forms;
        protected static array $fields = ['title', 'text', 'category'];

        public function __invoke()
        {
            $this->id = $this->params['id'];
            if ($this->params['action'] !== 'read') {
                Representation::checkUser($this->user);
                $this->forms = new FormsForArticle();
                ($this->categories = new CategoriesList())->addArray(Categories::getAllBy('status','1'));
            }
            $this->representation = new Representation();
            $this->action($this->params['action']);
            parent::__invoke();
        }

        protected function create() : void
        {
            $this->title = 'Добавить публикацию';
            if ($this->prepareData()) {
                $this->representation->createArticle($this->user);
            }
            $this->content = $this->page->assign('forms', $this->forms)->assign('categories', $this->categories)->assign('errors', $this->errors)->render('articles/add');
        }

        protected function read() : void
        {
            $this->viewArticle = $this->representation->readArticle($this->id);
            $this->title = $this->viewArticle->getTitle();
            $this->content = $this->page->assign('article', $this->viewArticle)->assign('author', $this->viewArticle->getLogin())->render('articles/article');
        }

        protected function update() : void
        {
            $this->title = 'Редактировать статью';
            $this->forms = $this->representation->prepareUpdate($this->id, $this->user);
            if ($this->prepareData()) {
                $this->representation->updateArticle($this->getId(), $this->forms, $this->user);
            }
            $this->content = $this->page->assign('forms', $this->forms)->assign('categories', $this->categories)->assign('errors', $this->errors)->render('articles/add');
        }

        protected function delete() : void
        {
            $this->representation->archiveArticle($this->getId());
        }

        protected function prepareData() : bool
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->representation->setData(self::$fields, $this->errors, $this->forms);
                return true;
            }
            return false;
        }
    }

