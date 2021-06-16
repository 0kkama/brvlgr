<?php


    namespace App\classes\controllers;

    use App\classes\abstract\Controller;
    use App\classes\models\Article as Art;

    class ArticleRead extends Controller
    {
        protected Art $article;

        public function __construct()
        {
            parent::__construct();

            $id = $_GET['id'] ?? null;

            if (!is_numeric($id) || empty($id)) {
                exit('Некорректный ID');
            }

            $this->article = Art::findById($id);

            if (!$this->article->exist()) {
                exit('NET TAKOY STATYI');
            }

            $this->title = $this->article->title;
            $this->content = $this->page->assign('title', $this->title)->assign('article', $this->article)->assign('author', $this->article->author())->render('article');
        }
    }
