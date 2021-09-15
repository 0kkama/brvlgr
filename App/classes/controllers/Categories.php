<?php

    namespace App\classes\controllers;

    use App\classes\abstract\controllers\Controller;
    use App\classes\models\ViewArticle;
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
            $pattern = 'admin/categories';

            if ( !empty($this->params['action']) ) {
//                $this->action = $params['action'];
                if ($this->choose()) {
                    $this->title = $this->chosenCat->getTitle();
                    $wrap = 'news';
                    $object = $this->articles;
                    $pattern = 'index';
                } else {
                    Error::deadend(404);
                }
//                $this->content = $this->page->assign('categories', $this->list)->render('admin/categories');
            }
                $this->content = $this->page->assign($wrap, $object)->render($pattern);
        }

        protected function choose(): bool
        {
            if ($this->list->checkCategoryInBy('url', $this->params['action'])) {
                $this->chosenCat = $this->list->getCategoryByIndex();
                if(isset($this->chosenCat)) {
                    $this->articles = ViewArticle::getAllBy('category', $this->chosenCat->getTitle());
                    return true;
                }
            }
            return false;
        }
//        protected function makeContent()
//        {
//            $this->content = $this->page->assign($wrap, $object)->render($pattern);
//
//        }


    }
