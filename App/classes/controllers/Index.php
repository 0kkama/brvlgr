<?php


    namespace App\classes\controllers;


    use App\classes\abstract\Controller;
    use App\classes\models\Article as News;


    class Index extends Controller
    {
        protected News $news;

        public function __construct($params)
        {
            parent::__construct($params);
            $this->title = 'Главная';
            $news = News::getLast(5);
            $this->content = $this->page->assign('news', $news)->render('news');
        }
    }
