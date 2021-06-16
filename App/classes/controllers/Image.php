<?php


    namespace App\classes\controllers;


    use App\classes\abstract\Controller;
    use App\classes\Config;

    class Image extends Controller
    {
        protected array $list;

        public function __construct()
        {
            parent::__construct();
            $this->title = 'Изображение';
        }

        public function __invoke()
        {
            $id = $_GET['id'];
            if (!is_numeric($id) || empty($id)) { exit('Некорректный ID'); }

            $this->list = glob(Config::getInstance()->IMG_PATH . "*.{jpg,jpeg}", GLOB_BRACE);
            $this->content = $this->page->assign('list', $this->list)->assign('id', $id)->render('image');

            parent::__invoke(); // TODO: Change the autogenerated stub
        }

    }
