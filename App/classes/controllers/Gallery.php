<?php

    namespace App\classes\controllers;

    use App\classes\abstract\Controller;
    use App\classes\Config;
    use App\classes\UsersErrors;
    use App\classes\Uploader;

    class Gallery extends Controller
    {
        protected array $list;

        public function __construct($params)
        {
            parent::__construct($params);
            $this->errors = new UsersErrors();

            // TODO подумать, не перемудрил ли я здесь с условием
            if ( !empty($this->user->__invoke()) && ( $_SERVER['REQUEST_METHOD'] = 'POST' ) && (isset($_FILES['newimage'])) ) {
                $newImage = new Uploader($_FILES['newimage'], $this->user);
                $this->errors = $newImage->upload();
            }

            $this->title = 'Галерея';
            $this->list = glob(Config::getInstance()->IMG_PATH . "*.{jpg,jpeg}", GLOB_BRACE);
//            var_dump($this->list);
            $this->content = $this->page->assign('list', $this->list)->assign('errMsg', $this->errors)->assign('user', $this->user)->render('gallery');
        }
    }

    /*
     *
     * TODO ПЕРЕДЕЛАТЬ РАБОТУ КОНТРОЛЛЕРОВ Gallery и Image с учётом ЧПУ
        TODO 1. м.б. добавить рандомайзер имени для файла
        TODO 2. и/или добавить проверку совпадения нового имени и уже существущих
        TODO возможно, создать в будущем модель Gallery, которая будет работать с изображениями
    */
