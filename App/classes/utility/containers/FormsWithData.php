<?php

    namespace App\classes\utility\containers;

    use App\classes\abstract\utility\AbstractContainer;

    class FormsWithData extends AbstractContainer
    {

        public function add(string $row) : void
        {
            $this->data[] = $row;
        }

        public function extractPostForms(array $keys, array $data) : self
        {
            $result = [];
            foreach ($keys as $key) {
                if (empty($data[$key])) {
                    $result[$key] = '';
                }
                else {
                    $result[$key] = $data[$key];
                }
            }
            $this->data = $result;
            return $this;
        }

        public function validateForms($strip = true) : self
        {
            if (!empty($this->data)) {
                array_walk($this->data, 'self::validation', $strip);
            }
            return $this;
        }

        private static function validation(string &$element, $key, bool $strip) : void
        {
            $element = match ($strip) {
                true => trim(strip_tags($element)),
                false => trim(htmlspecialchars($element)),
            };
        }

        /**
         * @return array
         */
        public function getData() : array
        {
            return $this->data;
        }

        public function get(string $key) : string
        {
            return $this->data[$key] ?? '';
        }

//        public function serialize() : ?string
//        {
//            return serialize($this->data);
//        }
//
//        public function unserialize($data) : void
//        {
//            $this->data = unserialize($data, false);
//        }
    }
