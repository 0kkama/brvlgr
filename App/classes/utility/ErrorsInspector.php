<?php

    namespace App\classes\utility;

    use App\classes\utility\containers\ErrorsContainer;
    use App\classes\utility\containers\FormsWithData;

    class ErrorsInspector
    {
//        protected AbstractModel $object;
        protected FormsWithData $forms;
        protected ErrorsContainer $container;
        protected array $errorsList;

//        public function __construct(AbstractModel $object, ErrorsContainer $container)
        public function __construct(FormsWithData $forms, ErrorsContainer $container, array $list)
        {
//            $this->object = $object;
            $this->container = $container;
            $this->forms = $forms;
            $this->errorsList = $list;
        }

        public function conductInspection(array $callback = []) : self
        {
            $this->checkFormFields();

            if (!empty($callback)) {
                $this->additionalVerification($callback);
            }
            return $this;
        }

        private function checkFormFields() : self
        {
            $messages = $this->errorsList;

            foreach ($this->forms as $index => $datum) {
                if (empty($datum) && isset($messages[$index])) {
                    $this->container[] = $messages[$index];
                }
            }
            return $this;
        }

        private function additionalVerification(array $callbackList) : self
        {
            if (!empty($callbackList)) {
                foreach ($callbackList as $method) {
                    if (method_exists($this, $method)) {
                        $errMessage = $this->$method();
                        if(!empty($errMessage)) {
                            $this->container[] = $errMessage;
                        }
                    }
                    //else {
//                    TODO throw new exception ?
                    //}
                }
            }
            return $this;
        }

        public function getErrorsContainer() : ErrorsContainer
        {
            return $this->container;
        }
    }
