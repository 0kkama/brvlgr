<?php

    namespace App\classes\controllers\article;

    use App\classes\abstract\controllers\Controller;
    use App\classes\abstract\models\AbstractView;
    use App\classes\models\ViewAllArticles;
    use App\classes\utility\View;
    use App\classes\utility\ArticleRepresentation as Representation;

    class Read extends Controller
    {
//        protected AbstractView $viewArt;
        protected ViewAllArticles $viewArt;
        protected Representation $representation;


        public function __construct(array $params, View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            $this->id = $this->params['id'];
            $this->representation = new Representation();



            $this->viewArt = ViewAllArticles::findOneBy('id', $this->id);

//          статьи не существует
//            статья сщуествует и открыта
            //  статья есть, но на модерации
//            статья есть, но она в

            if ($this->user->exist()) {
                if ($this->viewArt->getUserId() === $this->user->getId()) {
                    $this->title = $this->viewArt->getTitle();
                    $this->content = $this->page->assign('article', $value)->render('articles/article');
                }
            }




        }


    }
