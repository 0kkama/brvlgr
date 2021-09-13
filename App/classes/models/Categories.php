<?php

    namespace App\classes\models;

    use App\classes\abstract\models\AbstractModel;
    use App\classes\utility\Db;

    class Categories extends AbstractModel
    {
        protected const TABLE_NAME = 'categories';
        protected string $title = '', $url = '';
        protected ?string $date, $status;

        public function __toString() : string
        {
            return "<li><a href=/categories/{$this->getUrl()}>{$this->getTitle()}</a></li>";
        }

        public function __invoke(string $cat_mark) : string
        {
            if ($this->title === $cat_mark || $cat_mark === $this->id) {
                return '<option value=' . "$this->id selected>"  . $this->title . '</option>';
            }
            return '<option value=' . $this->id . '>' . $this->title . '</option>';
        }

        public function exist() : bool
        {
            return (!empty($this->id) && !empty($this->date));
        }

        public function setUrl(string $url) : self
        {
            $this->url = $url;
            return $this;
        }

        public function setTitle(string $title) : self
        {
            $this->title = $title;
            return $this;
        }

        public function setStatus(int $status) : self
        {
            if (0 === $status || 1 === $status) {
                $this->status = $status;
            } else {
//                throw new exception
            }
            return  $this;
        }

        /**
         * @return string|null
         */
        public function getStatus() : ?string
        {
            return $this->status;
        }

        /**
         * @return string
         */
        public function getTitle() : string
        {
            return $this->title;
        }

        /**
         * @return string
         */
        public function getUrl() : string
        {
            return $this->url;
        }

        /**
         * @return string|null
         */
        public function getDate() : ?string
        {
            return $this->date;
        }
    }
