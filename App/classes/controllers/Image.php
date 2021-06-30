<?php


    namespace App\classes\controllers;


    use App\classes\abstract\Controller;
    use App\classes\Config;
    use App\classes\exceptions\FileException;

    class Image extends Controller
    {
        protected array $list;

        public function __construct($params)
        {
            parent::__construct($params);
            $this->title = 'Изображение';
        }

        /**
         * @throws FileException
         */
        public function __invoke()
        {
            $id = $this->params['id'];
            var_dump($this->params);

            if (!is_numeric($id)) {
                Error::deadend(400);
            }

            $this->list = glob(Config::getInstance()->IMG_PATH . "*.{jpg,jpeg}", GLOB_BRACE);

            if (empty($this->list)) {
                throw new FileException('Ошибка при получении изображения',500);
            }

            if (!is_readable($this->list[$id])) {
                Error::deadend(404, 'Изображение не найдено');
            }
            $this->content = $this->page->assign('list', $this->list)->assign('id', $id)->render('image');

            parent::__invoke();
        }

    }
