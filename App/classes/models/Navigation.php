<?php

    namespace App\classes\models;

    use App\classes\abstract\models\AbstractModel;

    class Navigation extends AbstractModel
    {
        protected const TABLE_NAME = 'navigation';
        protected string $title = '', $url = '', $order = '', $status = '';
//        protected ?string $status;

        public function exist() : bool
        {
            return (!empty($this->id) && !empty($this->url));
        }

        public function __invoke($place) : string
        {
            return match ($place) {
                'aside' => "<li> <a href=\"$this->url\"> $this->title </a> </li>",
                'footer' => "<li style='display:inline;margin-right:15px'> <a href=\"$this->url\"> $this->title </a></li>",
            };
        }

        public function __toString(): string
        {
            return "<li> $this->url $this->title </li>";
        }

        /**
         * @return string
         */
        public function getTitle(): string
        {
            return $this->title;
        }

        /**
         * @return string
         */
        public function getOrder(): string
        {
            return $this->order;
        }

        /**
         * @return string
         */
        public function getUrl(): string
        {
            return $this->url;
        }

        /**
         * @return string
         */
        public function getStatus(): string
        {
            return $this->status;
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

        /**
         * @param string $order
         */
        public function setOrder(string $order) : self
        {
            $this->order = $order;
            return $this;
        }

        /**
         * @param string $status
         */
        public function setStatus(string $status) : self
        {
            $this->status = $status;
            return $this;
        }
    }
