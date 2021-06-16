<?php


    namespace App\classes\controllers;


    use App\classes\abstract\Controller;
    use App\classes\Config;
    use App\classes\models\Article;
    use App\traits\ValidateArticleTrait;

    class ArticleDelete extends Controller
    {
        use ValidateArticleTrait;

        protected Article $article;

        public function __invoke()
        {
            $this->validate();
            $this->article->delete();
            header('Location: ' . Config::getInstance()->BASE_URL);
        }
    }

