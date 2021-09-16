<?php


    namespace App\classes\controllers;


    use App\classes\abstract\controllers\Controller;
//    use App\classes\models\Article as News;
    use App\classes\models\ViewPublishedArticles as News;
    use Exception;


    class Index extends Controller
    {
        protected News $news;

        /**
         * @throws Exception
         */
        public function __invoke()
        {
            $this->title = 'Главная';
            $news = News::getLast(5);
            $this->content = $this->page->assign('news', $news)->render('index');
            parent::__invoke();
        }
    }
