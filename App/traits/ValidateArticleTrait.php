<?php


    namespace App\traits;


    use App\classes\Config;
    use App\classes\controllers\Error;
    use App\classes\models\Article;

    trait ValidateArticleTrait
    {
        public function validate() : void
        {

            //            проверка наличия пользователя и корректности id
            if (!$this->user->exist()) {
                Error::deadend(403);
            }

            $id = $_GET['id'];
            if (!is_numeric($id) || empty($id)) {
                Error::deadend(400);
            }

            //    получение данных уже существующей статьи
            $this->article = Article::findOneBy(type: 'id', subject: $id);
            if (!$this->article->exist()) {
                Error::deadend(404);
            }
        }
    }
