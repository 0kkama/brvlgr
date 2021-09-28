<?php


    namespace App\classes\controllers;


    use App\classes\abstract\controllers\Controller;
    use App\classes\models\view\ViewPublishedArticles;
    use App\classes\utility\View;
// todo Think about merge this controller and controller Index
    class Articles extends Controller
    {
        protected array $articles;

        public function __construct(array $params,View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            $this->title = 'Статьи';
            $this->articles = ViewPublishedArticles::getLast(0);
            $this->content = $this->page->assign('news', $this->articles)->render('index');
        }
    }
