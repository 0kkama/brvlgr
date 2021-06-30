<?php

    namespace App\classes\utility;

    use App\classes\Config;
    use App\classes\controllers\Error;

    class Router
    {
        protected string $uri;
        protected array $chunks;
        protected array $result =
            [
                'controller' => '',
                'action' => '',
                'id' => '',
                'params' => null,
            ];

        protected array $regex =
            [
                'controller' => '@\w+@',
                'action' => '@[A-z]+@',
                'id' => "@[0-9]+\d*@",
            ];

        public function __construct(string $uri)
        {
            $this->uri = $uri;
        }

        protected function parseEnd(string $key) : void
        {
            if (preg_match($this->regex[$key], end($this->chunks), $matches)) {
                $this->result[$key] = $matches[0];
                unset($this->chunks[array_key_last($this->chunks)]);
            }
        }

        protected function parseEach(string $key) : void
        {
            if (isset($this->chunks[array_key_first($this->chunks)])) {
                if (preg_match($this->regex[$key], $this->chunks[array_key_first($this->chunks)], $matches)) {
                    $this->result[$key] = $matches[0];
                    unset($this->chunks[array_key_first($this->chunks)]);
                }
            }
        }

        protected function parser() : array
        {
            if ($_GET !== []) {
                $this->result['params'] = extractFields(array_keys($_GET), $_GET);
                $pattern = '@(\?.*)?@';
                $this->uri = preg_replace($pattern, '', $this->uri);
            }

            $this->uri = trim($this->uri, '/');
//            var_dump($this->uri);

            if ($this->uri === '' || $this->uri === 'index.php') {
                $this->result['controller'] = 'Index';
                return $this->result;
            }

            $this->chunks = explode('/', $this->uri);
//            var_dump($this->chunks);

            foreach ($this->regex as $index => $regex) {
                $this->parseEach($index);
            }

            return $this->result;
        }

        public function __invoke() : array
        {
            return $this->parser();
        }
    }

