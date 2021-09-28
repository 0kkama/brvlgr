<?php

    namespace App\classes\utility\articles;

    use App\classes\exceptions\DbException;
    use App\classes\models\Article as ArticleModel;
    use App\classes\models\ArticleCategories;
    use App\classes\models\User;
    use App\classes\models\UserArticles;
    use App\classes\utility\containers\ErrorsContainer;
    use App\classes\utility\containers\FormsForArticleData;
    use App\classes\utility\inspectors\ArticleFormsInspector;
    use App\classes\utility\loggers\LoggerSelector;
    use App\traits\WriteAndGoTrait;

    class ArticleCreator
    {
        private User $user;
        private FormsForArticleData $forms;
        private ErrorsContainer $errors;
        private ArticleModel $articleModel; // don't touch for trait
        private ArticleFormsInspector $inspector;
        private UserArticles $userArticles;
        private ArticleCategories $articleCategories;

        use WriteAndGoTrait;

        public function __construct(FormsForArticleData $forms, ErrorsContainer $errors)
        {
            $this->user = User::getCurrent();
            $this->forms = $forms;
            $this->errors = $errors;
        }

        public function execute(array $fields) : void
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                ($inspector = new ArticleFormsInspector())->setModel(new ArticleModel());
                $this->articleModel = new ArticleModel();
                $userArticles = new UserArticles();
                $articleCategories = new ArticleCategories();
                $this->forms->extractPostForms($fields, $_POST)->validateForms(false);
                $inspector->setForms($this->forms)->setContainer($this->errors)->conductInspection();

                if ($this->errors->isEmpty()) {
                    if ($this->articleModel->setFields($this->forms)->save()) {
                        $articleId = $this->articleModel->getId();
                        $userArticles->setArtId($articleId)->setUserId($this->user->getId())->save();
                        $articleCategories->setArtId($articleId)->setCatId($this->forms->get('category'))->save();
                        $this->writeAndGo('добавил', ' /article/read/' . $articleId);
                    } else {
                        throw new DbException('Не удалось сохранить статью',456);
                    }
                }
            }
        }
    }
