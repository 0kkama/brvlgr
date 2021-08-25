<?php

    namespace App\classes\utility;

    use App\classes\controllers\Error;
    use App\classes\exceptions\DbException;
    use App\classes\models\Article;
    use App\classes\models\ArticleCategories;
    use App\classes\models\Categories;
    use App\classes\models\User;
    use App\classes\models\UserArticles;
    use App\classes\models\ViewArticle;
    use App\classes\utility\containers\FormsWithData;
    use App\classes\utility\loggers\LoggerSelector;

    class ArticleRepresentation
    {
        protected Article $article;
        protected User $author;
        protected Categories $categories;
        protected UserArticles $userArticles;
        protected ArticleCategories $articleCategories;
        protected ViewArticle $view;
        protected array $properties = ['id' => '', 'login' => '', 'user_id' => '', 'title' => '', 'text' => '', 'category' => '', 'date' => ''];

        /**
         * @throws DbException
         */
        public function createArticle(FormsWithData $forms, User $user) : void
        {
            $this->articleCategories = new ArticleCategories();
            $this->initialize($user);
            if ($this->article->setFields($forms)->save()) {
                $articleId = $this->article->getId();
                $this->userArticles->setArtId($articleId)->setUserId($this->author->getId())->save();
                $this->articleCategories->setArtId($articleId)->setCatId($forms->get('category'))->save();
                $this->sendMessage($this->author->getLogin(),'добавил', $this->article->getTitle())->relocate('Location: /article/read/' . $articleId);
            }
            else {
                throw new DbException('Не удалось сохранить статью',456);
            }
        }

        public function readArticle(string $id) : ViewArticle
        {
            $this->checkArtID($id);
            $this->view = ViewArticle::findOneBy('id', $id);
            if (!$this->view->exist()) {
                Error::deadend(404, 'Статья не найдена');
            }
            return $this->view;
        }

        public function updateArticle(string $id, FormsWithData $forms, User $user) : void
        {
            $this->author = $user;
            $this->article = new Article();
            $this->userArticles = new UserArticles();

            $this->initialize($user)->checkArtID($id)->article = Article::findOneBy('id', $id);
            if ($this->article->setFields($forms)->save()) {
                $articleId = $this->article->getId();
                $this->articleCategories = ArticleCategories::findOneBy('art_id', $articleId);
                $this->articleCategories->setArtId($articleId)->setCatId($forms->get('category'))->save();
                $this->sendMessage($this->author->getLogin(),'обновил', $this->article->getTitle())->relocate('Location: /article/read/' . $this->article->getId());
            } else {
                throw new DbException('Ошибка при обновлении статьи',456);
            }
        }

        public function deleteArticle(string $id, User $user) : void
        {
            $this->checkArtID($id)->article = Article::findOneBy('id', $id);
            $title = $this->article->getTitle();
            $this->checkArtExist()->article->delete();
            $this->sendMessage($user->getLogin(), 'удалил', $title)->relocate('Location: ' . Config::getInstance()->BASE_URL);
        }

//        todo Заменить на __call() ??
        public function get(string $key) : string
        {
            $method = 'get' . ucfirst($key);
            if (method_exists($this->article, $method)) {
                return $this->article->$method;
            }
            return '';
        }

        protected function checkArtID (string $id) : self
        {
            if (!is_numeric($id) || empty($id)) {
                Error::deadend(400);
            }
            return $this;
        }

        protected function initialize(User $user) : self
        {
            $this->author = $user;
            $this->article = new Article();
            $this->userArticles = new UserArticles();
            return $this;
        }

        protected function checkArtExist() : self
        {
            if (!$this->article->exist()) {
                Error::deadend(404, 'Статья не найдена');
            }
            return $this;
        }

        public static function checkUser(User $user) : bool
        {
            if(!$user->hasUserRights()) {
                Error::deadend(403);
            }
            return true;
        }

        protected function sendMessage( string $subject, string $action, string $object) : self
        {
            $result = "Пользователь $subject $action статью $object";
            LoggerSelector::publication($result);
            return $this;
        }

        protected function relocate(string $destination) : void
        {
            header($destination);
        }
    }
