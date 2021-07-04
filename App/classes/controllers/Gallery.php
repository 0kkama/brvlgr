<?php

    namespace App\classes\controllers;

    use App\classes\abstract\Controller;
    use App\classes\Config;
    use App\classes\exceptions\FileException;
    use App\classes\utility\UsersErrors;
    use App\classes\utility\Uploader;
    use Exception;

    class Gallery extends Controller
    {
        protected array $list;

        /**
         * @throws FileException
         * @throws Exception
         */
        public function __construct($params)
        {
            parent::__construct($params);
            $this->errors = new UsersErrors();

            // TODO подумать, не перемудрил ли я здесь с условием
            if ( ( $_SERVER['REQUEST_METHOD'] === 'POST' ) && (isset($_FILES['newimage'])) && $this->user->exist()) {
                $newImage = new Uploader($_FILES['newimage'], $this->user);
                $this->errors = $newImage->upload();
            }

            $this->title = 'Галерея';
            $origPath = Config::getInstance()->IMG_PATH;
            $prePath = Config::getInstance()->IMG_PRE;

            $this->list = scandir($origPath, SCANDIR_SORT_DESCENDING);
            array_pop($this->list);
            array_pop($this->list);

            $callback = static function (&$value, $key, $path) {
                $value = $path . $value;
            };
            array_walk($this->list, $callback, $prePath);

            if (empty($this->list)) {
                throw new FileException('Ошибка при получении списка изображений',500);
            }

            $this->content = $this->page->assign('list', $this->list)->assign('errMsg', $this->errors)->assign('user', $this->user)->render('gallery');
        }
    }

    /*
     * TODO ПЕРЕДЕЛАТЬ РАБОТУ КОНТРОЛЛЕРОВ Gallery и Image с учётом ЧПУ
        TODO 1. м.б. добавить рандомайзер имени для файла
        TODO 2. и/или добавить проверку совпадения нового имени и уже существущих
        TODO возможно, создать в будущем модель Gallery, которая будет работать с изображениями
    */
