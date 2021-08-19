<?php

    namespace App\classes\utility;

    use App\traits\ArrayAccessTrait;
    use App\traits\ArrayIteratorTrait;
    use App\traits\CountableTrait;
    use App\traits\JsonSeializableTrait;
    use ArrayAccess;
    use ArrayIterator;
    use Countable;
    use IteratorAggregate;
    use JsonSerializable;

    class FormsWithData implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
    {
        protected int $key = 0;
        protected array $data = [];

        //<editor-fold desc="Interfaces implementation">
//        use IteratorTrait;
        use ArrayAccessTrait;
        use CountableTrait;
        use JsonSeializableTrait;
        use ArrayIteratorTrait;
        //</editor-fold>

        public function extractPostForms(array $keys, array $data, $validation = false) : self
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

        private static function validation(string &$element, $key, bool $strip) {
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
    }
