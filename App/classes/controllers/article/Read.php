<?php

    namespace App\classes\controllers\article;

    use App\classes\abstract\controllers\Controller;
    use App\classes\abstract\models\ViewArticle;
    use App\classes\models\view\ViewNotArchivedArticles;
    use App\classes\utility\ArticleRepresentationzzz as Representation;
    use App\classes\utility\View;

    class Read extends Controller
    {
        protected ViewArticle $viewArt;
        protected Representation $representation;

        public function __construct(array $params, View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            $this->id = $this->params['id'];
            $this->representation = new Representation();
            $this->viewArt = $this->representation->readArticle($this->id, new ViewNotArchivedArticles());

            $this->title = $this->viewArt->getTitle();
            $this->content = $this->page->assign('article', $this->viewArt)->render('articles/article');
        }

    }
