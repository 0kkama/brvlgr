<?php

    namespace App\classes\utility\articles;

    use App\classes\controllers\Error;
    use App\classes\models\Article as ArticleModel;
    use App\classes\models\User;
    use App\classes\models\view\ViewAllArticles;
    use App\classes\models\view\ViewNotArchivedArticles;
    use App\classes\models\view\ViewPublishedArticles;
    use App\classes\utility\Config;
    use App\classes\utility\FaceControl;
    use App\classes\utility\loggers\LoggerSelector;
    use App\interfaces\ViewArticleInterface;
    use App\traits\WriteAndGoTrait;

    class ArticleRemover
    {
        private string $id;
        private User $user;
        private ArticleModel $articleModel;

        use WriteAndGoTrait;

        public function __construct($id)
        {
            $this->articleModel = ArticleModel::findOneBy('id', $id);
        }

        public function delete() : void
        {
            $this->articleModel->delete();
            $this->writeAndGo('удалил', '/overseer/articles/');
        }

        public function archive(): void
        {
            $this->articleModel->setStatus(2)->save();
            $this->writeAndGo('заархивировал', Config::getInstance()->BASE_URL);
        }
    }
