<?php


    namespace App\traits;


    use App\classes\Config;
    use App\classes\controllers\Relocator;
    use App\classes\models\Article;

    trait ValidateArticleTrait
    {
        public function validate() : void
        {

            //            проверка наличия пользователя и корректности id
            if (!$this->user->__invoke()) {
                Relocator::deadend(403); exit();
            }

            $id = $_GET['id'];
            if (!is_numeric($id) || empty($id)) {
                Relocator::deadend(400); exit();
            }

            //    получение данных уже существующей статьи
            $this->article = Article::findById($id);
            if (!$this->article->exist()) {
                Relocator::deadend(404); exit();
            }
        }
    }
