<?php


    namespace App\classes\controllers;


    use App\classes\models\Article;
    use App\classes\Config;
    use App\traits\ValidateArticleTrait;

    class ArticleEdit extends ArticleAdd
    {

        public function __construct()
        {
            parent::__construct();
            $this->title = 'Редактировать статью';
        }

        protected function execute() : void
        {
            $this->validate();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $fields = extractFields(array_keys($_POST),$_POST);
                $this->article->setTitle($fields['title'])->setText($fields['text'])->setCategory($fields['category']);
//                $this->errors = $this->article->save()->errors;
                $this->errors = $this->article->save()->getErrors();

                if (!$this->errors->__invoke()) {
                    header('Location: /?cntrl=articleRead&id=' . $this->article->id);
                }
            }
        }
    }
