<?php


    namespace App\classes\controllers\article;


    use App\classes\abstract\Controller;
    use App\classes\Config;
    use App\classes\models\Article;
    use App\classes\controllers\article\Article as Essence;
    use App\traits\ValidateArticleTrait;

    class Delete extends Essence
    {
//        use ValidateArticleTrait;

        protected Article $article;

        public function __invoke()
        {
            $this->access();
            $this->article->delete();
            header('Location: ' . Config::getInstance()->BASE_URL);
        }
    }

