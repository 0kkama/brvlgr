<?php

    namespace App\classes\utility;

    use App\classes\abstract\AbstractModel;

    class ErrorsInspector
    {
        protected ErrorsContainer $container;
        protected AbstractModel $object;

        public function setObject(AbstractModel $object) : void
        {
            $this->object = $object;
        }

        public function setContainer(ErrorsContainer $container) : void
        {
            $this->container = $container;
        }

        public function checkFormFields() : void
        {
//            $data = $this->object->getMetaData();
            $data = $this->object->getFormFields();
            $messages = $this->object->getErrorsList();

            foreach ($data as $index => $datum) {
                if (empty($datum)) {
                    $this->container->add($messages[$index]);
                }
            }
        }

        public function additionalVerification(array $callbackList) : void
        {
            if (!empty($callbackList)) {
                foreach ($callbackList as $method) {
                    if (method_exists($this, $method)) {
                        $this->container->add($this->$method());
                    } else {
//                    TODO throw new exception ?
                    }
                }
            }
        }
    }
