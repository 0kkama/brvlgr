<?php


    namespace App\classes\testexamples;

    use App\classes\abstract\ControllerActing;
    use App\classes\Config;
    use App\classes\models\Article as Publication;

    class Article extends ControllerActing
    {

        protected string $title = 'PROBLEM!';
        protected string $content = 'BIG PROBLEM!';
        protected Publication $article;


        protected function checkUser() {
            if (!$this->user->exist()) {
                Relocator::deadend(403);
            }
        }

        protected function access() : void
        {
            // проверка наличия и корректности переданного id
            if (!is_numeric($this->params['id']) || empty($this->params['id'])) {
                Relocator::deadend(400);
            }

            // получение данных уже существующей статьи и проверка ее существования
            $this->article = Publication::findOneBy(type: $this->params['id'], subject: 'id');

            if (!$this->article->exist()) {
                Relocator::deadend(404);
            }
        }
        protected function action(string $action) : void
        {
            if (method_exists($this, $action)) {
                $this->$action();
            } else {
                Relocator::deadend(400);
            }
        }

        protected function add() : void
        {
            $this->checkUser();
            $this->title = 'Добавить публикацию';
            $this->article = new Publication();
            $this->sendData();
            $this->content = $this->page->assign('article', $this->article)->assign('errors', $this->errors)->render('add');
        }

        protected function read() : void
        {
            $this->access();
            $this->title = $this->article->title;
            $this->content = $this->page->assign('title', $this->title)->assign('article', $this->article)->assign('author', $this->article->author())->render('article');
        }

        protected function edit() : void
        {
            $this->title = 'Редактировать статью';
            $this->checkUser();
            $this->access();
            $this->sendData();
            $this->content = $this->page->assign('article', $this->article)->assign('errors', $this->errors)->render('add');
        }

        protected function delete() : void
        {
            $this->checkUser();
            $this->access();
            $this->article->delete();
            header('Location: ' . Config::getInstance()->BASE_URL);
        }

        /**
         * Send data for article by POST for methods 'add' and 'edit'
         */
        protected function sendData() : void
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $fields = extractFields(array_keys($_POST),$_POST);
                $this->article->setTitle($fields['title'])->setText($fields['text'])->setCategory($fields['category'])->setAuthor($this->user->login)->setAuthorId($this->user->id);
                //                $this->errors = $this->article->save()->errors;
                $this->errors = $this->article->save()->getErrorsContainer();

                if (!$this->errors->__invoke()) {
                    header('Location: /article/read/' . $this->article->id);
                }
            }
        }

        public function __invoke()
        {
            $this->action($this->params['action']);
            parent::__invoke();
        }

    }

//    TODO допилить или переделать способ парсинга в Роутере и
//    TODO разобраться с проблемой в релокейтере
