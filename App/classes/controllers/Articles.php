<?php


    namespace App\classes\controllers;


    use App\classes\abstract\controllers\Controller;
    use App\classes\models\ViewArticle;
    use App\classes\utility\View;

    class Articles extends Controller
    {
        protected array $articles;

        public function __construct(array $params,View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            $this->title = 'Статьи';
            $this->articles = ViewArticle::getLast(0);
            $this->content = $this->page->assign('news', $this->articles)->render('index');
        }
    }
