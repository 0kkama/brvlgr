<?php

    namespace App\classes\controllers;

    use App\classes\abstract\controllers\ControllerActing;
    use App\classes\exceptions\DbException;
    use App\classes\exceptions\ExceptionWrapper;
    use App\classes\models\Article as ArticleModel;
    use App\classes\models\Categories;
    use App\classes\models\view\ViewPublishedArticles;
    use App\classes\utility\ArticleRepresentation as Representation;
    use App\classes\utility\containers\CategoriesList;
    use App\classes\utility\containers\FormsForArticle;
    use App\classes\utility\inspectors\ArticleInspector;
    use App\interfaces\InspectorInterface;

    class Article extends ControllerActing
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
            }
            $this->representation = new Representation();
            $this->action($this->params['action']);
            parent::__invoke();
        }

        protected function create() : void
        {
            $this->title = 'Добавить публикацию';
            ($this->categories = new CategoriesList())->addArray(Categories::getAllBy('status','1'));
            ($this->inspector = new ArticleInspector())->setModel(new ArticleModel());
            if ($this->prepareData() && $this->errors->isEmpty()) {
                $this->representation->createArticle($this->forms, $this->user);
            }
            $this->content = $this->page->assign('forms', $this->forms)->assign('categories', $this->categories)->assign('errors', $this->errors)->render('articles/add');
        }

        protected function read() : void
        {
            $this->viewArticle = $this->representation->readArticle($this->getId());
            $this->title = $this->viewArticle->getTitle();
            $this->content = $this->page->assign('article', $this->viewArticle)->assign('author', $this->viewArticle->getLogin())->render('articles/article');
        }

        protected function update() : void
        {
            $this->title = 'Редактировать статью';
            ($this->categories = new CategoriesList())->addArray(Categories::getAllBy('status','1'));
            $this->viewArticle = $this->representation->readArticle($this->getId());
            $this->representation->checkEditRights($this->user, $this->viewArticle);
            ($this->forms = new FormsForArticle())->extractViewArticle($this->viewArticle);
            ($this->inspector = new ArticleInspector())->setModel(ArticleModel::findOneBy('id', $this->id));

            if ($this->prepareData() && $this->errors->isEmpty()) {
                $this->representation->updateArticle($this->getId(), $this->forms, $this->user);
            }
            $this->content = $this->page->assign('forms', $this->forms)->assign('categories', $this->categories)->assign('errors', $this->errors)->render('articles/add');
        }

        protected function delete() : void
        {
            $this->representation->archiveArticle($this->id);
        }

        protected function prepareData() : bool
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->forms->extractPostForms(self::$fields, $_POST)->validateForms(false);
                $this->inspector->setForms($this->forms)->setContainer($this->errors)->conductInspection();
                return true;
            }
            return false;
        }
    }

