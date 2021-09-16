<?php

    namespace App\classes\utility;

    use App\classes\controllers\Error;
    use App\classes\exceptions\DbException;
    use App\classes\models\Article;
    use App\classes\models\Article as ArticleModel;
    use App\classes\models\ArticleCategories;
    use App\classes\models\Categories;
    use App\classes\models\User;
    use App\classes\models\UserArticles;
    use App\classes\models\ViewPublishedArticles;
    use App\classes\utility\containers\ErrorsContainer;
    use App\classes\utility\containers\FormsForArticle;
    use App\classes\utility\containers\FormsWithData;
    use App\classes\utility\loggers\LoggerSelector;
    use App\classes\utility\inspectors\ArticleInspector;

    class ArticleRepresentationzzz
    {
        protected Article $article;
        protected User $author;
        protected Categories $categories;
        protected UserArticles $userArticles;
        protected ArticleCategories $articleCategories;
        protected ViewPublishedArticles $view;
        protected FormsForArticle $forms;
        protected ArticleInspector $inspector;
        protected ErrorsContainer $errors;


        public function setData(array $fields, ErrorsContainer $errors, FormsForArticle $forms)
        {
            $this->forms = $forms;
            $this->forms->extractPostForms($fields, $_POST)->validateForms(false);
            $this->inspector = new ArticleInspector();
            $this->errors = $errors;
            $this->inspector->setModel(new ArticleModel())->setForms($this->forms)->setContainer($this->errors);
            $this->inspector->setForms($this->forms)->setContainer($errors);
        }
        /**
         * @throws DbException
         */
        public function createArticle(User $user) : void
        {
            $this->articleCategories = new ArticleCategories();
            $this->initialize($user);
            $this->inspector->conductInspection();
            if ($this->errors->isEmpty()) {
                if ($this->article->setFields($this->forms)->save()) {
                    $articleId = $this->article->getId();
                    $this->userArticles->setArtId($articleId)->setUserId($this->author->getId())->save();
                    $this->articleCategories->setArtId($articleId)->setCatId($this->forms->get('category'))->save();
                    $this->writeAndGo('добавил', ' /article/read/' . $articleId);
                }
                else {
                    throw new DbException('Не удалось сохранить статью',456);
                }
            }
        }

//        todo static?
        public function readArticle(string $id) : ViewPublishedArticles
        {
            $this->checkArtID($id);
            $this->view = ViewPublishedArticles::findOneBy('id', $id);
            if (!$this->view->exist()) {
                Error::deadend(404, 'Статья не найдена');
            }
            return $this->view;
        }

        public function updateArticle(string $id, FormsWithData $forms, User $user) : void
        {
            $this->initialize($user)->checkArtID($id)->article = Article::findOneBy('id', $id);
            if ($this->article->setFields($forms)->save()) {
                $articleId = $this->article->getId();
                $this->articleCategories = ArticleCategories::findOneBy('art_id', $articleId);
                $this->articleCategories->setArtId($articleId)->setCatId($forms->get('category'))->save();
                $this->writeAndGo('обновил', ' /article/read/' . $this->article->getId());
            } else {
                throw new DbException('Ошибка при обновлении статьи',456);
            }
        }

        public function deleteArticle(string $id) : void
        {
            $this->author = User::getCurrent();
            $this->checkArtID($id)->article = Article::findOneBy('id', $id);
            $this->checkArtExist()->article->delete();
            $this->writeAndGo('удалил', '/overseer/articles/');
        }

        public function archiveArticle(string $id): void
        {
            $this->author = User::getCurrent();
            $this->view = ViewPublishedArticles::findOneBy('id', $id);
            $this->checkEditRights($this->author, $this->view);
            $this->checkArtID($id)->article = Article::findOneBy('id', $id);
            $this->checkArtExist()->article->setStatus(2)->save();
            $this->writeAndGo('заархивировал', Config::getInstance()->BASE_URL);
        }

        public function prepareUpdate (string $id, User $user)
        {
            $this->view = $this->readArticle($id);
            $this->checkEditRights($user, $this->view);
            ($this->forms = new FormsForArticle())->extractViewArticle($this->view);
            return $this->forms;
        }

        public function get(string $key) : string
        {
            $method = 'get' . ucfirst($key);
            if (method_exists($this->article, $method)) {
                return $this->article->$method;
            }
            return '';
        }

        public static function checkUser(User $user) : bool
        {
            if(!$user->hasUserRights()) {
                Error::deadend(403);
            }
            return true;
        }

        public function checkEditRights(User $user, ViewPublishedArticles $view) : void
        {
            if(!$user->hasAdminRights() && !($user->getId() === $view->getUserId())) {
                Error::deadend(403, 'Действие доступно только автору или модератору');
            }
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

        protected function writeAndGo(string $action, string $destination) : void
        {
            $message = 'Пользователь ' . $this->author->getLogin() . " $action статью " .$this->article->getId() .' ' . $this->article->getTitle();
            LoggerSelector::publication($message);
            header("Location: $destination");
        }
    }
