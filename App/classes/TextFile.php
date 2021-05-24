<?php
    namespace App\classes;

    class TextFile
    {
        protected $fileContent;
        protected $pathToFile;

        public function __construct($path)
        {
            $this->pathToFile = $path;
            $this->fileContent = $this->getData($this->pathToFile);
        }

        public function getData(string $pathToFile) : array
        {
            $content = file($pathToFile);
            if ( empty($content) ) {
                return [];
            }

            $wrapper = static function (string $line) : array {
                return json_decode($line,true);
            };
            return array_map($wrapper, $content);
        }

        public function getContent() : array
        {
            return $this->fileContent;
        }
    }
