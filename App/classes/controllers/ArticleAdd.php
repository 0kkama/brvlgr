<?php


    namespace App\classes\controllers;

    use App\classes\abstract\Controller;
    use App\classes\MyErrors;
    use App\classes\models\Article;


    class ArticleAdd extends Controller
    {
        protected Article $article;

        public function __construct()
        {
            parent::__construct();
            $this->article = new Article();
            $this->errors = new MyErrors();
            $this->title = 'Добавить публикацию';
        }

        protected function execute() : void
        {
            if (!$this->user->__invoke()) {
                exit('No homo!');
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $fields = extractFields(array_keys($_POST),$_POST);
                $this->article->setTitle($fields['title'])->setText($fields['text'])->setCategory($fields['category'])->setAuthor($this->user->login)->setAuthorId($this->user->id);
                $this->errors = $this->article->save()->errors;
                if (!$this->errors->__invoke()) {
                    header('Location: /?cntrl=articleRead&id=' . $this->article->id);
                }
            }
        }

        public function __invoke()
        {
            $this->execute();
            $this->content = $this->page->assign('article', $this->article)->assign('errors', $this->errors)->render('add');
            parent::__invoke();
        }
    }
