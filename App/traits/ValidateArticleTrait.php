<?php


    namespace App\traits;


    use App\classes\Config;
    use App\classes\models\Article;

    trait ValidateArticleTrait
    {
        public function validate() : void
        {

            //            проверка наличия пользователя и корректности id
            if (!$this->user->__invoke()) { exit('No homo!');            }

            $id = $_GET['id'];
            if (!is_numeric($id) || empty($id)) { exit('Некорректный ID');             }

            //    получение данных уже существующей статьи
            $this->article = Article::findById($id);
            if (!$this->article->exist()) {
                header(Config::getInstance()->PROTOCOL . ' 404 Not Found');
                exit('This article doesn\'t exist');
            }
        }
    }
