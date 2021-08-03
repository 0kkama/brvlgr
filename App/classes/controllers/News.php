<?php


    namespace App\classes\controllers;


    use App\classes\abstract\Controller;
    use App\classes\models\Article;
    use App\classes\View;

    class News extends Controller
    {
        protected array $articles;

        public function __construct(array $params,View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            $this->title = 'Статьи';
            $this->articles = Article::getAll();
            $this->content = $this->page->assign('news', $this->articles)->render('index');
        }
    }
