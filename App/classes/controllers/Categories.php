<?php

    namespace App\classes\controllers;

    use App\classes\abstract\controllers\Controller;
    use App\classes\models\view\ViewPublishedArticles;
    use App\classes\utility\containers\CategoriesList;
    use App\classes\models\Categories as CatModel;
    use App\classes\utility\View;

    class Categories extends Controller
    {
        protected CategoriesList $list;
        protected string $title = 'Категории статей';
        protected string $action;
        protected array $articles;
        protected CatModel $chosenCat;

        public function __construct(array $params, View $templateEngine)
        {
            ($this->list = new CategoriesList())->addArray(CatModel::getAllBy('status', '1'));
            parent::__construct($params, $templateEngine);
            $wrap = 'categories';
            $object = $this->list;
            $pattern = 'articles/categories';

            if ( !empty($this->params['action']) ) {
                if ($this->chooseCategory()) {
                    $this->title = $this->chosenCat->getTitle();
                    $object = $this->articles;
                    $wrap = 'news';
                    $pattern = 'index';
                } else {
                    Error::deadend(404, 'Такой категории не существует');
                }
            }
                $this->content = $this->page->assign($wrap, $object)->render($pattern);
        }

        protected function chooseCategory(): bool
        {
            if ($this->list->checkCategoryInBy('url', $this->params['action'])) {
                $this->chosenCat = $this->list->getCategoryByIndex();
                if(isset($this->chosenCat)) {
                    $this->articles = ViewPublishedArticles::getAllBy('category', $this->chosenCat->getTitle());
                    return true;
                }
            }
            return false;
        }
    }
