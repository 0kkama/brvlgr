<?php

    namespace App\classes\controllers\admin;

    use App\classes\abstract\controllers\Controller;
    use App\classes\controllers\Error;
    use App\classes\models\Categories as CatModel;
    use App\classes\utility\containers\CategoriesList;
    use App\classes\utility\containers\FormsWithData;
    use App\classes\utility\inspectors\CategoriesFormsInspector;
    use App\classes\utility\loggers\LoggerSelector;
    use App\interfaces\InspectorInterface;

    class Categories extends Controller
    {
        protected CategoriesList $categories;
        protected CatModel $category;
        protected InspectorInterface $inspector;
        protected static array $fields = ['title', 'url'];

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

        public function show() : void
        {
            $this->title = 'Список категорий';
            ($this->categories = new CategoriesList())->addArray(CatModel::getAll());
            $this->add();
            $this->content = $this->page->assign('categories', $this->categories)->assign('errors', $this->errors)->assign('cat', $this->category)->render('admin/categories');
        }

        public function add() : void
        {
            $this->category = new CatModel();
            ($this->inspector = new CategoriesFormsInspector())->setModel($this->category);
            $this->sendData('добавил');
        }

        public function edit() : void
        {
            $this->title = 'Изменить категорию';
            $this->category = CatModel::findOneBy('id', $this->id);
//            ($this->inspector = new CategoriesInspector())->setModel($this->category)->setObjectId($this->id);
            ($this->inspector = new CategoriesFormsInspector())->setModel($this->category);
            if ($this->category->exist()) {
                $this->sendData('изменил');
            }
            $this->content = $this->page->assign('cat', $this->category)->assign('errors', $this->errors)->render('admin/category_edit');
        }

        public function hide() : void
        {
            $this->modifyCategory(0, 'скрыл');
        }

        public function regain() : void
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

        public function delete() : void
        {
            $this->category = CatModel::findOneBy('id', $this->id);
            if ($this->category->exist() && $this->category->delete()) {
                $this->writeAndGo('удалил');
            }
        }

        protected function sendData(string $action) : void
        {
            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                $fields = self::$fields;
                ($forms = new FormsWithData())->extractPostForms($fields, $_POST)->validateForms();
                $this->inspector->setForms($forms)->setContainer($this->errors)->conductInspection();
                $this->category->setFields($forms);
                if ($this->errors->isEmpty() && $this->category->save()) {
                    $this->writeAndGo($action);
                }
            }
        }

        protected function writeAndGo(string $action) : void
        {
            $message = 'Администратор: ' . $this->user->getId() . ' ' . $this->user->getLogin() . " $action категорию " . $this->category->getId() . ' ' . $this->category->getTitle();
            LoggerSelector::publication($message);
            header('Location: /overseer/categories');
        }
    }
