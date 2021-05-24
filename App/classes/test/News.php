<?php
    namespace classes\test;

    class News
    {

        private $list, $articles, $path;

        public function __construct($pathToFiles)
        {
            $this->path = $pathToFiles;
            $this->list = $this->getArtsList();
            $this->articles = $this->getArtsArray();
        }

        protected function getArtsList() : array
        {
            $listID = [];
            $filesList = scandir($this->path);

            if (false === $filesList) {
                trigger_error('Нет файлов с новостями или указан неверный путь');
                return $listID;
            }

            $filesList = array_diff($filesList ,['..', '.']);
            foreach ($filesList as $index => $item) {
                preg_match('/(\d+)\.json/', $item, $matches);
                $listID[] = $matches[1];
            }
            return $listID;
        }

        protected function getArtsArray() : array
        {
            $articles = [];
            if ( empty($this->list) ) {
                return $articles;
            }

            foreach ($this->list as $index => $item) {
                $articles[] = new Article($item);
            }

            return $articles;
        }

        public function getArticles() : array
        {
            return $this->articles;
        }

        public function debugg() : void
        {
            //            var_dump($this->list);
        }
    }

