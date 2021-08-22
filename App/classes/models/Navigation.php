<?php

    namespace App\classes\models;

    use App\classes\abstract\models\AbstractModel;

    class Navigation extends AbstractModel
    {

        protected const TABLE_NAME = 'navigation';
        protected string $title = '', $url = '';

        public function exist() : bool
        {
            return (!empty($this->id) && !empty($this->url));
        }

        /**
         * @param string $title
         */
        public function setTitle(string $title) : self
        {
            $this->title = $title;
            return $this;
        }

        /**
         * @param string $url
         */
        public function setUrl(string $url) : self
        {
            $this->url = $url;
            return $this;
        }

        public function __invoke($place) : string
        {
            return match ($place) {
                'aside' => "<li> <a href=\"$this->url\"> $this->title </a> </li>",
                'footer' => "<li style='display:inline;margin-right:15px'> <a href=\"$this->url\"> $this->title </a></li>",
            };
        }
    }
