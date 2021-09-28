<?php

    namespace App\classes\controllers\user;

    use App\classes\abstract\controllers\Controller;
    use App\classes\controllers\Error;
    use App\classes\models\view\ViewModerArticles;
    use App\classes\models\view\ViewNotArchivedArticles;
    use App\classes\models\view\ViewPublishedArticles;
    use App\classes\utility\View;
    use App\interfaces\ViewArticleInterface;

    class Articles extends Controller
    {
        protected ViewArticleInterface $viewArticle;

        public function __construct(array $params, View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            $this->chooseView();
            $articles = $this->viewArticle::getAllBy('user_id', $this->user->getId());
            $this->content = $this->page->assign('articles', $articles)->render('users/articles');
        }

        protected function chooseView(): void
        {
            $key = $this->params['params']['q'] ?: 'all';

            switch ($key) {
                case 'all':
                    $this->title = 'Все статьи';
                    $this->viewArticle = new ViewNotArchivedArticles();
                break;
                case 'publ':
                    $this->title = 'Опубликованные статьи';
                    $this->viewArticle = new ViewPublishedArticles();
                break;
                case 'unpubl':
                    $this->title = 'Неопубликованные статьи';
                    $this->viewArticle = new ViewModerArticles();
                break;
                default:
                    Error::deadend(404);
            }
        }

    }
