<?php

    namespace App\classes\controllers\admin;

    use App\classes\abstract\controllers\Controller;
    use App\classes\controllers\Error;
    use App\classes\models\Categories as CatModel;
    use App\classes\utility\containers\CategoriesList;
    use App\classes\utility\containers\ErrorsContainer;
    use App\classes\utility\containers\FormsWithData;
    use App\classes\utility\ErrorsInspector;
    use App\classes\utility\loggers\LoggerSelector;

    class Categories extends Controller
    {
        private CategoriesList $categories;
        private CatModel $category;
        protected ErrorsContainer $errors;
        protected array $errorsList = [
            'title' => 'Введите название категории',
            'url' => 'Введите URL'];

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
            $this->title = 'Список категорий';
            ($this->categories = new CategoriesList())->addArray(CatModel::getAll());
            $this->category = new CatModel();
            $this->sendData('добавил');
//            $this->add();
            $this->content = $this->page->assign('categories', $this->categories)->assign('errors', $this->errors)->assign('cat', $this->category)->render('categories_list');
        }

        public function add()
        {
            $this->category = new CatModel();
            $this->sendData('добавил');
//            $this->content = $this->page->assign('categories', $this->categories)->assign('errors', $this->errors)->assign('cat', $this->category)->render('categories_list');
        }

        public function edit() : void
        {
            $this->title = 'Изменить категорию';
            $this->category = CatModel::findOneBy('id', $this->id);
            if ($this->category->exist()) {
                $this->sendData('изменил');
            }
            $this->content = $this->page->assign('cat', $this->category)->assign('errors', $this->errors)->render('category_edit');
        }

        public function hide()
        {
            $this->modifyCategory(0, 'скрыл');
        }

        public function regain()
        {
            $this->modifyCategory(1, 'открыл');
        }

        protected function modifyCategory(int $status, string $action) : void
        {
            $this->category = CatModel::findOneBy('id', $this->id);
            if ($this->category->exist()) {
                if ($this->category->setStatus($status)->save()) {
                    $this->writeAndGo($action);
                }
            }
        }

        public function delete(string $id) : void
        {
            $this->category = CatModel::findOneBy('id', $id);
            if ($this->category->exist()) {
                if ($this->category->delete()) {
                    $this->writeAndGo('удалил');
                }
            }
        }

        protected function sendData(string $action) : void
        {
            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                $fields = array_keys($this->errorsList);
                ($forms = new FormsWithData())->extractPostForms($fields, $_POST)->validateForms();
                $this->errors = new ErrorsContainer();
                (new ErrorsInspector($forms, $this->errors, $this->errorsList))->conductInspection();
                $this->category->setFields($forms);
                if ($this->errors->isEmpty() && $this->category->save()) {
                    $this->writeAndGo($action);
                }
            }
        }

        protected function writeAndGo(string $action) : void
        {
            $message = 'Администратор: ' . $this->user . " $action категорию " .$this->category->getId() .' ' . $this->category->getTitle();
            LoggerSelector::publication($message);
            header('Location: /overseer/categories');
        }
    }
