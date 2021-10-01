<?php

    namespace App\classes\controllers\article;

    use App\classes\abstract\controllers\Controller;
    use App\classes\models\view\ViewNotArchivedArticles;
    use App\classes\utility\articles\ArticleRepresentation as Representation;
    use App\classes\utility\View;
    use App\interfaces\ViewArticleInterface;

    class Read extends Controller
    {
        protected ViewArticleInterface $viewArt;

        public function __construct(array $params, View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            $this->id = $this->params['id'];
            $this->viewArt = Representation::readArticle($this->id, new ViewNotArchivedArticles());

            $this->title = $this->viewArt->getTitle();
            $this->content = $this->page->assign('article', $this->viewArt)->render('articles/article');
        }
    }
