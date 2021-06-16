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
                Relocator::deadEnd('403', 'Для данного действия необходима авторизация!');
                exit();
            }

            $id = $_GET['id'];
            if (!is_numeric($id) || empty($id)) {
                Relocator::deadEnd('400', 'Некорректный ID');
                exit();
            }

            //    получение данных уже существующей статьи
            $this->article = Article::findById($id);
            if (!$this->article->exist()) {
                Relocator::deadEnd('404', 'Запрошенная статья не существует');
//                header(Config::getInstance()->PROTOCOL . ' 404 Not Found');
                exit();
            }
        }
    }
