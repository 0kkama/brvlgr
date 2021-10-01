<?php

    namespace App\classes\controllers\admin;

    use App\classes\abstract\controllers\Controller;
    use App\classes\controllers\Error;
    use App\classes\models\Article as ArtModel;
    use App\classes\models\view\ViewAllArticles;
    use App\classes\utility\articles\ArticleRepresentation as Representation;
    use App\classes\utility\loggers\LoggerSelector;

    class Articles extends Controller
    {
        protected array $articles;
        protected ArtModel $art;
        protected static array $actions =
            [
                'hide' => ['status' => 0, 'message' => 'отправил в модерацию'],
                'public' => ['status' => 1, 'message' => 'опубликовал'],
                'archive' => ['status' => 2, 'message' => 'заархивировал'],
            ];

        public function __invoke()
        {
            $this->id = $this->params['id'] ?: '';
            $action = $this->params['action'] ?: 'show';

            if (method_exists($this, $action)) {
                $this->$action();
            } elseif (isset(self::$actions[$action])) {
                $this->modifyArticle(self::$actions[$action]['status'], self::$actions[$action]['message']);
            } else {
                Error::deadend(400);
            }
            parent::__invoke();
        }

        public function show()
        {
            $this->title = 'Список статей';
            $this->articles = ViewAllArticles::getAllBy();
            $this->content = $this->page->assign('articles', $this->articles)->assign('errors', $this->errors)->render('admin/articles');
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
            $view = ViewAllArticles::findOneBy('id', $this->id);
            Representation::checkEditRights($this->user, $view);
            Representation::deleteArticle($this->getId());
        }

        protected function writeAndGo(string $action) : void
        {
            $message = 'Администратор: ' . $this->user->getId() . ' ' . $this->user->getLogin() . " $action статью " . $this->art->getId() . ' ' . $this->art->getTitle();
            LoggerSelector::publication($message);
            header('Location: /overseer/articles');
        }
    }
