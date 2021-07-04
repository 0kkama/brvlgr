<?php


    namespace App\classes\controllers;


    use App\classes\abstract\Controller;
    use App\classes\Config;
    use App\classes\exceptions\FileException;
    use App\classes\exceptions\FullException;
    use JetBrains\PhpStorm\NoReturn;

    class Image extends Controller
    {
        protected array $list;
        protected string $image;

        public function __construct($params)
        {
            parent::__construct($params);
            $this->title = 'Изображение';
        }

        /**
         * @throws FileException
         */
        public function show() : void
        {
            $origPath = Config::getInstance()->IMG_PATH;

            $this->list = scandir($origPath, SCANDIR_SORT_DESCENDING);
            array_pop($this->list);
            array_pop($this->list);

            $callback = static function (&$value, $key, $path) {
                $value = $path . $value;
            };
            array_walk($this->list, $callback, $origPath);

            if (empty($this->list)) {
                throw new FileException('Ошибка при получении изображения',500);
            }

//            $id = $this->params['id'];
            $this->image = $this->list[$this->id];

            if (!is_readable($this->image)) {
                Error::deadend(404, 'Изображение не найдено');
            }
        }

        /**
         * @throws FileException
         */
        #[NoReturn] public function download() : void
        {
            $this->show();
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($this->image).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($this->image));
            readfile($this->image);
            exit;
        }

        public function action(string $action): void
        {
            if( $action === '') {
                $action = 'show';
            }
            parent::action($action);
        }

        /**
         */
        public function __invoke()
        {
            $this->id = $this->params['id'];

            if (!is_numeric($this->id)) {
                Error::deadend(400);
            }

            $this->action($this->params['action']);


//            $this->content = $this->page->assign('list', $this->list)->assign('id', $this->id)->render('image');
            $this->content = $this->page->assign('image', $this)->render('image');
            parent::__invoke();
        }

        /**
         * @return string
         */
        public function getImage(): string
        {
            return $this->image;
        }
    }
