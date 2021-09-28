<?php

    namespace App\traits;

    trait ExtractDataTrait
    {
        public function  extractDataFrom($object, array $fields): void
        {
            foreach ($fields as $field) {
                $method = 'get' . ucfirst($field);
                if (method_exists($object, $method)) {
                    $this->data[$field] = $object->$method();
                }
            }
        }
    }
