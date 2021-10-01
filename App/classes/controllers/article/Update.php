<?php

    namespace App\classes\controllers\article;

    use App\classes\abstract\controllers\Controller;
    use App\classes\models\Categories;
    use App\classes\models\view\ViewModerArticles;
    use App\classes\utility\articles\ArticleRepresentation as Representation;
    use App\classes\utility\containers\CategoriesList;
    use App\classes\utility\containers\FormsForArticleData;
    use App\classes\utility\View;
    use App\interfaces\InspectorInterface;
    use App\interfaces\ViewArticleInterface;

    class Update extends Controller
    {
        protected FormsForArticleData $forms;
        protected InspectorInterface $inspector;
        protected CategoriesList $categories;
        protected ViewArticleInterface $viewArticle;
        protected static array $fields = ['title', 'text', 'category'];

        public function __construct(array $params, View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            $this->id = $this->params['id'];
            ($this->categories = new CategoriesList())->addArray(Categories::getAllBy('status','1'));
            $this->viewArticle = Representation::readArticle($this->id, new ViewModerArticles());
            Representation::checkEditRights($this->user, $this->viewArticle);
            ($this->forms = new FormsForArticleData())->extractDataFrom($this->viewArticle, self::$fields);
            Representation::updateArticle($this->getId(), $this->forms, self::$fields, $this->errors);

            $this->title = 'Редактировать статью';
            $this->content = $this->page->assign('forms', $this->forms)->assign('categories', $this->categories)->assign('errors', $this->errors)->render('articles/add');
        }
    }
