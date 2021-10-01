<?php

    namespace App\classes\controllers\article;

    use App\classes\abstract\controllers\Controller;
    use App\classes\models\Categories;
    use App\classes\utility\articles\ArticleRepresentation as Representation;
    use App\classes\utility\containers\CategoriesList;
    use App\classes\utility\containers\FormsForArticleData;
    use App\classes\utility\View;

    class Create extends Controller
    {
        protected FormsForArticleData $forms;
        protected CategoriesList $categories;
        protected static array $fields = ['title', 'text', 'category'];

        public function __construct(array $params, View $templateEngine)
        {
            parent::__construct($params, $templateEngine);

            $this->forms = new FormsForArticleData();
            Representation::checkCreateRights($this->user);
            ($this->categories = new CategoriesList())->addArray(Categories::getAllBy('status','1'));
            Representation::createArticle($this->forms, self::$fields, $this->errors);

            $this->title = 'Добавить публикацию';
            $this->content = $this->page->assign('forms', $this->forms)->assign('categories', $this->categories)->assign('errors', $this->errors)->render('articles/add');
        }
    }
