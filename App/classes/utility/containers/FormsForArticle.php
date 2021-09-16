<?php

    namespace App\classes\utility\containers;

    use App\classes\models\ViewPublishedArticles;

    class FormsForArticle extends FormsWithData
    {
        protected array $fields = ['id', 'login', 'user_id', 'title', 'text', 'category', 'date'];

        public function  extractViewArticle(ViewPublishedArticles $viewArticle): void
        {
            $reuslt = [];
            foreach ($this->fields as $index => $field) {
                $method = 'get' . ucfirst($field);
                if (method_exists($viewArticle, $method)) {
                    $reuslt[$field] = $viewArticle->$method();
                }
                $this->data = $reuslt;
            }
        }
    }
