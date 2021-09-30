<?php

    namespace App\classes\utility\articles;

    use App\classes\controllers\Error;
    use App\classes\exceptions\DbException;
    use App\classes\models\Article as ArticleModel;
    use App\classes\models\ArticleCategories;
    use App\classes\models\Categories;
    use App\classes\models\User;
    use App\classes\models\UserArticles;
    use App\classes\models\view\ViewPublishedArticles;
    use App\classes\utility\Config;
    use App\classes\utility\containers\ErrorsContainer;
    use App\classes\utility\containers\FormsForArticleData;
    use App\classes\utility\FaceControl;
    use App\classes\utility\inspectors\ArticleFormsInspector;
    use App\classes\utility\loggers\LoggerSelector;
    use App\interfaces\ViewArticleInterface;

    class ArticleRepresentation
    {
        protected ArticleModel $article;
        protected User $author;
        protected User $user;
        protected Categories $categories;
        protected ViewArticleInterface $view;
        protected FormsForArticleData $forms;
        protected ArticleFormsInspector $inspector;
        protected ErrorsContainer $errors;

        /**
         * @throws DbException
         */
        public function createArticle(FormsForArticleData $forms, array $fields, ErrorsContainer $errors) : void
        {
            $creator = new ArticleCreator($forms, $errors);
            $creator->execute($fields);
        }

         public function readArticle(string $id, ViewArticleInterface $view) : ViewArticleInterface
        {
            $this->checkArtID($id);
            $reader = new ArticleReader($id , $view);
            return $reader->execute();
        }

        public function updateArticle(string $id, FormsForArticleData $forms, array $fields, ErrorsContainer $errors) : void
        {
            $this->checkArtID($id);
            $updater = new ArticleUpdater($forms, $errors);
            $updater->execute($id, $fields);
        }

        public function deleteArticle(string $id) : void
        {
            $this->checkArtID($id);
            $remover = new ArticleRemover($id);
            $remover->delete();
        }

        public function archiveArticle(string $id): void
        {
            $this->checkArtID($id);
            $remover = new ArticleRemover($id);
            $remover->archive();
        }

        public function checkEditRights(User $user, ViewArticleInterface $view) : void
        {
            if (!$view->exist()) {
                Error::deadend(404);
            }

            $rights = FaceControl::checkUserRights($user, 'moderator');
            if(!$rights && !($user->getId() === $view->getUserId())) {
                Error::deadend(403, 'Действие доступно только автору или модератору');
            }
        }

        public function checkCreateRights(User $user): void
        {
            if(!$user->exist() || !FaceControl::checkUserRights($user, 'author')){
                Error::deadend(403, 'Для добавления новой статьи необходимы права автора');
            }
        }

        protected function checkArtID (string $id) : self
        {
            if (!is_numeric($id) || empty($id)) {
                Error::deadend(400);
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
