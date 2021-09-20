<?php

    namespace App\classes\controllers\article;

    use App\classes\abstract\controllers\Controller;
    use App\classes\abstract\models\ViewArticle;
    use App\classes\models\Article as ArticleModel;
    use App\classes\models\Categories;
    use App\classes\models\view\ViewModerArticles;
    use App\classes\utility\ArticleRepresentationzzz as Representation;
    use App\classes\utility\containers\CategoriesList;
    use App\classes\utility\containers\FormsForArticle;
    use App\classes\utility\inspectors\ArticleInspector;
    use App\classes\utility\View;
    use App\interfaces\InspectorInterface;

    class Update extends Controller
    {
        protected Representation $representation;
        protected FormsForArticle $forms;
        protected InspectorInterface $inspector;
        protected CategoriesList $categories;
        protected ViewArticle $viewArticle;
        protected static array $fields = ['title', 'text', 'category'];

        public function __construct(array $params, View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            ($this->categories = new CategoriesList())->addArray(Categories::getAllBy('status','1'));
            $this->viewArticle = $this->representation->readArticle($this->id, new ViewModerArticles());
            $this->representation->checkEditRights($this->user, $this->viewArticle);
            $this->makeContent();

            $this->title = 'Редактировать статью';
            $this->content = $this->page->assign('forms', $this->forms)->assign('categories', $this->categories)->assign('errors', $this->errors)->render('articles/add');
        }

        protected function makeContent()
        {
            ($this->forms = new FormsForArticle())->extractViewArticle($this->viewArticle);
            ($this->inspector = new ArticleInspector())->setModel(ArticleModel::findOneBy('id', $this->id));
            if ($this->prepareData() && $this->errors->isEmpty()) {
                $this->representation->updateArticle($this->getId(), $this->forms, $this->user);
            }
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
