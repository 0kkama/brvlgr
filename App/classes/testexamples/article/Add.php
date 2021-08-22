<?php


    namespace App\classes\controllers\article;

    use App\classes\controllers\Error;
    use App\classes\utility\containers\ErrorsContainer;
    use App\classes\controllers\article\Article as Essence;
    use App\classes\models\Article;


    class Add extends Essence
    {
        protected Article $article;

        public function __construct($params)
        {
            parent::__construct($params);
            $this->article = new Article();
            $this->errors = new ErrorsContainer();
            $this->title = 'Добавить публикацию';
        }

        protected function execute() : void
        {
            if (!$this->user->__invoke()) {
                Error::deadend(403);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $fields = extractFields(array_keys($_POST),$_POST);
                $this->article->setTitle($fields['title'])->setText($fields['text'])->setCategory($fields['category'])->setAuthor($this->user->login)->setAuthorId($this->user->id);
//                $this->errors = $this->article->save()->errors;
                $this->errors = $this->article->save()->getErrorsContainer();
//                var_dump($this->article);
                if (!$this->errors->__invoke()) {
                    header('Location: /article/read/' . $this->article->id);
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
