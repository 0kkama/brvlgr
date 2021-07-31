<?php

    namespace App\classes\controllers\article;

    use App\classes\controllers\Error;
    use App\classes\models\Article;
    use App\classes\controllers\article\Article as Essence;

    class Read extends Essence
    {
        protected Article $article;

        public function __construct($params)
        {
            parent::__construct($params);

            $id = $params['id'];

            if (!is_numeric($id) || empty($id)) {
                Error::deadend(400);
            }

            $this->article = Article::findOneBy(type: 'id', subject: $id);

            if (!$this->article->exist()) {
                Error::deadend(404);
            }

            $this->title = $this->article->title;
            $this->content = $this->page->assign('title', $this->title)->assign('article', $this->article)->assign('author', $this->article->author())->render('article');
        }
    }
