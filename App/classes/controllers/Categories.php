<?php

    namespace App\classes\controllers;

    use App\classes\abstract\controllers\Controller;
    use App\classes\utility\containers\CategoriesList;
    use App\classes\models\Categories as CatModel;
    use App\classes\utility\View;

    class Categories extends Controller
    {
        protected CategoriesList $list;
        protected string $title = 'Категории статей';

        public function __construct(array $params, View $templateEngine)
        {
            ($this->list = new CategoriesList())->addArray(CatModel::getAllBy('status', '1'));
            parent::__construct($params, $templateEngine);
            $this->content = $this->page->assign('categories', $this->list)->render('admin/categories');
        }


    }
