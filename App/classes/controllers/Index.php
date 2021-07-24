<?php


    namespace App\classes\controllers;


    use App\classes\abstract\Controller;
    use App\classes\models\Article as News;
    use App\classes\View;
    use Exception;


    class Index extends Controller
    {
        protected News $news;

        /**
         * @throws Exception
         */
        public function __construct(array $params,View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            $this->title = 'Главная';
            $news = News::getLast(5);
            $this->content = $this->page->assign('news', $news)->render('news');
        }
    }
