<?php

    namespace App\classes\utility\articles;

    use App\classes\controllers\Error;
    use App\classes\exceptions\DbException;
    use App\classes\models\Article as ArticleModel;
    use App\classes\models\ArticleCategories;
    use App\classes\models\User;
    use App\classes\models\UserArticles;
    use App\classes\utility\containers\ErrorsContainer;
    use App\classes\utility\containers\FormsForArticleData;
    use App\classes\utility\inspectors\ArticleFormsInspector;
    use App\classes\utility\loggers\LoggerSelector;
    use App\interfaces\ViewArticleInterface;
    use App\traits\WriteAndGoTrait;

    class ArticleUpdater
    {
        private User $user;
        private FormsForArticleData $forms;
        private ErrorsContainer $errors;
        private ArticleModel $articleModel;

        use WriteAndGoTrait;

        public function __construct(FormsForArticleData $forms, ErrorsContainer $errors)
        {
            $this->user = User::getCurrent();
            $this->forms = $forms;
            $this->errors = $errors;
        }

        public function execute(string $id, array $fields) : void
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->articleModel = ArticleModel::findOneBy('id', $id);
                ($inspector = new ArticleFormsInspector())->setModel($this->articleModel);
                $this->forms->extractPostForms($fields, $_POST)->validateForms(false);
                $inspector->setForms($this->forms)->setContainer($this->errors)->conductInspection();
                if ($this->errors->isEmpty()) {
                    if ($this->articleModel->setFields($this->forms)->save()) {
                        $articleId = $this->articleModel->getId();
                        $articleCategories = ArticleCategories::findOneBy('art_id', $articleId);
                        $articleCategories->setArtId($articleId)->setCatId($this->forms->get('category'))->save();
                        $this->writeAndGo('обновил', ' /article/read/' . $articleId);
                    } else {
                        throw new DbException('Ошибка при обновлении статьи', 456);
                    }
                }
            }
        }

        public function checkEditRights(User $user, ViewArticleInterface $view) : void
        {
            if(!$user->hasAdminRights() && !($user->getId() === $view->getUserId())) {
                Error::deadend(403, 'Действие доступно только автору или модератору');
            }
        }
    }
