<?php
    namespace App\classes;

    use App\classes\Config;

    class View implements \Countable, \Iterator
    {
        /**
         * @var array $data
         * @var int $key
         */
        private array $data = [];
        private int $key = 0;

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

        public function getData() : array
        {
            return $this->data;
        }

        public function current()
        {
            return $this->data[$this->key];
        }

        public function next() : void
        {
            ++$this->key;
        }

        public function key(): int
        {
            return $this->key;
        }

        public function valid() : bool
        {
            return isset($this->data[$this->key]);
        }

        public function rewind() : void
        {
            $this->key = 0;
        }
    }
