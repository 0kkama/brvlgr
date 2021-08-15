<?php

    namespace App\classes\utility;

    use App\classes\abstract\AbstractModel;

    class ErrorsInspector
    {
        protected AbstractModel $object;
        protected ErrorsContainer $container;
        protected array $errorsList;

        public function __construct(AbstractModel $object, ErrorsContainer $container)
        {
            $this->object = $object;
            $this->container = $container;
        }

        public function conductInspection() : self
        {
            $this->checkFormFields();

            $callback = $this->object->getCheckList();

            if (!empty($callback) && $this->container->isEmpty()) {
                $this->additionalVerification($callback);
            }
            return $this;
        }

        public function checkFormFields() : self
        {
            $data = $this->object->getFormFields();
            $messages = $this->object->getErrorsList();

            foreach ($data as $index => $datum) {
                if (empty($datum)) {
                    $this->container[] = $messages[$index];
                }
            }
            return $this;
        }

        public function additionalVerification(array $callbackList) : self
        {
            if (!empty($callbackList)) {
                foreach ($callbackList as $method) {
                    if (method_exists($this, $method)) {
                        $errMessage = $this->$method();
                        if(!empty($errMessage)) {
                            $this->container[] = $errMessage;
                        }
                    } else {
//                    TODO throw new exception ?
                    }
                }
            }
            return $this;
        }

        public function getErrorsContainer() : ErrorsContainer
        {
            return $this->container;
        }
    }
