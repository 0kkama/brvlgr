<?php
    namespace App\classes\utility;

    use App\traits\IteratorTrait;

    class View implements \Countable, \Iterator
    {
        /**
         * @var array $data
         * @var int $key
         */
        private int $key = 0;
        private array $data = [];

        //<editor-fold desc="Iterator interface implementation">
        use IteratorTrait;
        //</editor-fold>

        public function assign(string $name, $value) : object
        {
            $this->data[$name] = $value;
            return $this;
        }

        public function count() : int
        {
            return count($this->data);
        }

        public function assignArray(array $value) : object
        {
            $this->data = array_merge($this->data, $value);
            return $this;
        }

        public function render(string $template) : string
        {
            $fullPathToTemplate = Config::getInstance()->TEMPLATES . "$template.view.php";
            extract($this->data, EXTR_OVERWRITE);
            ob_start();
            include ($fullPathToTemplate);
            return ob_get_clean();
        }

        public function display(string $template) : void
        {
            echo $this->render($template);
        }

        public function getData() : array
        {
            return $this->data;
        }
    }
