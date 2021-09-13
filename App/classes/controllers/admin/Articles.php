<?php

    namespace App\classes\controllers\admin;

    use App\classes\abstract\controllers\Controller;
    use App\classes\controllers\Error;
    use App\classes\models\Article as ArtModel;
    use App\classes\models\ViewArticleForAdmin;
    use App\classes\utility\ArticleRepresentation;
    use App\classes\utility\loggers\LoggerSelector;

    class Articles extends Controller
    {
        protected array $articles;
        protected ArtModel $art;
        protected ArticleRepresentation $representation;
        protected ViewArticleForAdmin $artView;

        //        вывести список статей
        //        1) список видимых и 2) список закрытых
        //        закрыть статью
        //        открыть статью
        //        удалить статью (есть)
        //        отредактировать статью (есть)

        //        добавить к таблице статей поле статуса (0 или 1)
        //        переделать вью с учётом видимости статьи

        public function __invoke()
        {
            $this->id = $this->params['id'] ?: '';
            $action = $this->params['action'] ?: 'show';

            if (method_exists($this, $action)) {
                $this->$action();
            } else {
                Error::deadend(400);
            }
            parent::__invoke();
        }

        public function show()
        {
            $this->title = 'Список статей';
            $this->articles = ViewArticleForAdmin::getAllBy();
            $this->content = $this->page->assign('articles', $this->articles)->assign('errors', $this->errors)->render('admin/articles_list');
        }

        public function hide() : void
        {
            $this->modifyArticle(0, 'скрыл');
        }

        public function regain() : void
        {
            $this->modifyArticle(1, 'открыл');
        }

        public function archive() : void
        {
            $this->modifyArticle(2, 'заархивировал');
        }

        protected function modifyArticle(int $status, string $action) : void
        {
            $this->art = ArtModel::findOneBy('id', $this->id);
            if ($this->art->exist()) {
                if ($this->art->setStatus($status)->save()) {
                    $this->writeAndGo($action);
                }
            }
        }

        public function delete() : void
        {
            $this->representation = new ArticleRepresentation();
            $this->representation->deleteArticle($this->getId());
        }

        protected function writeAndGo(string $action) : void
        {
            $message = 'Администратор: ' . $this->user->getId() . ' ' . $this->user->getLogin() . " $action статью " . $this->art->getId() . ' ' . $this->art->getTitle();
            LoggerSelector::publication($message);
            header('Location: /overseer/articles');
        }
    }
