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

    /**
     * The Facade
     */
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
        public static function createArticle(FormsForArticleData $forms, array $fields, ErrorsContainer $errors) : void
        {
            $creator = new ArticleCreator($forms, $errors);
            $creator->execute($fields);
        }

         public static function readArticle(string $id, ViewArticleInterface $view) : ViewArticleInterface
        {
            self::checkArtID($id);
            $reader = new ArticleReader($id , $view);
            return $reader->execute();
        }

        public static function updateArticle(string $id, FormsForArticleData $forms, array $fields, ErrorsContainer $errors) : void
        {
            self::checkArtID($id);
            $updater = new ArticleUpdater($forms, $errors);
            $updater->execute($id, $fields);
        }

        public static function deleteArticle(string $id) : void
        {
            self::checkArtID($id);
            $remover = new ArticleRemover($id);
            $remover->delete();
        }

        public static function archiveArticle(string $id): void
        {
            self::checkArtID($id);
            $remover = new ArticleRemover($id);
            $remover->archive();
        }

        public static function checkEditRights(User $user, ViewArticleInterface $view) : void
        {
            if (!$view->exist()) {
                Error::deadend(404);
            }

            $rights = FaceControl::checkUserRights($user, 'moderator');
            if(!$rights && !($user->getId() === $view->getUserId())) {
                Error::deadend(403, 'Действие доступно только автору или модератору');
            }
        }

        public static function checkCreateRights(User $user): void
        {
            if(!$user->exist() || !FaceControl::checkUserRights($user, 'author')){
                Error::deadend(403, 'Для добавления новой статьи необходимы права автора');
            }
        }

        /**
         * @param string $id
         * @return void
         */
        protected static function checkArtID (string $id) : void
        {
            if (!is_numeric($id) || empty($id)) {
                Error::deadend(400);
            }
        }
    }
