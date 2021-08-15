<?php

    namespace App\classes\controllers;

    use App\classes\abstract\ControllerActing;
    use App\classes\Config;
    use App\classes\exceptions\FileException;
    use App\classes\utility\ErrorsContainer;
    use App\classes\utility\Uploader;
    use Exception;

    class Gallery extends ControllerActing
    {
        protected array $list;

        /**
         * @throws FileException
         * @throws Exception
         */
        public function __construct($params, $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            $this->errors = new ErrorsContainer();

            // TODO подумать, не перемудрил ли я здесь с условием
            if ( ( $_SERVER['REQUEST_METHOD'] === 'POST' ) && (isset($_FILES['newimage'])) && $this->user->hasUserRights()) {
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
