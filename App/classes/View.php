<?php
    namespace App\classes;

    use App\classes\Config;

    class View
    {
        private $data = [];

        public function assign(string $name, $value) : object
        {
            $this->data[$name] = $value;
            return $this;
        }

        public function assignArray(array $value) : object
        {
            $this->data = array_merge($this->data, $value);
            return $this;
        }

        public function render(string $template) : string
        {
            $fullPathToTemplate = Config::getInstance()->PATH_TO_TEMPLATES . "$template.view.php";
            extract($this->data, EXTR_OVERWRITE);
            ob_start();
            include ($fullPathToTemplate);
            return ob_get_clean();
        }

        public function display(string $template) : void
        {
            echo $this->render($template);
        }

        public function getData()
        {
            return $this->data;
        }
    }
