<?php


    namespace App\classes\controllers\article;


    use App\classes\models\Article;
    use App\classes\Config;
    use App\traits\ValidateArticleTrait;

    class Edit extends Add
    {

        public function __construct($params)
        {
            parent::__construct($params);
            $this->title = 'Редактировать статью';
        }

        protected function execute() : void
        {
            $this->access();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $fields = extractFields(array_keys($_POST),$_POST);
                $this->article->setTitle($fields['title'])->setText($fields['text'])->setCategory($fields['category']);
//                $this->errors = $this->article->save()->errors;
                $this->errors = $this->article->save()->getErrors();

                if (!$this->errors->__invoke()) {
                    header('Location: /article/read/' . $this->article->id);
                }
            }
        }
    }
