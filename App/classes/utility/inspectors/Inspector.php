<?php

    namespace App\classes\utility\inspectors;

    use App\classes\utility\containers\ErrorsContainer;
    use App\classes\utility\containers\FormsWithData;
    use App\interfaces\ExistenceInterface;
    use App\interfaces\InspectorInterface;

    abstract class Inspector implements InspectorInterface
    {
        protected ?ExistenceInterface $model = null;
        protected FormsWithData $forms;
        protected ErrorsContainer $container;
        protected static array $regexp;
        protected static array $errorsMessages;
        protected static array $errorsAbsence;
        protected string $subject;

        abstract protected function prepareData() : void;

        /**
         * @param FormsWithData $forms
         * @return Inspector
         */
        public function setForms(FormsWithData $forms) : self
        {
            $this->forms = $forms;
            return $this;
        }

        /**
         * @param ErrorsContainer $container
         * @return Inspector
         */
        public function setContainer(ErrorsContainer $container) : self
        {
            $this->container = $container;
            return $this;
        }

        public function setModel(?ExistenceInterface $model) : self
        {
            $this->model = $model ?? null;
            return $this;
        }

        public function conductInspection(): self
        {
            $this->checkFormFields();

            $list = array_keys(static::$errorsMessages);

            foreach($list as $value) {
                if (empty($this->container->get($value))) {
                    $callback[] = $value;
                }
            }

            if (!empty($callback)) {
                $this->additionalVerification($callback);
            }
            return $this;
        }

        private function checkFormFields(): void
        {
            $messages = static::$errorsAbsence;

            foreach ($this->forms as $index => $datum) {
                if (empty($datum) && isset($messages[$index])) {
                    $this->container[$index] = $messages[$index];
                }
            }
        }

        private function additionalVerification(array $callback): void
        {
            $this->prepareData();
            //            если метод прописан явным образом в наследнике, то он будет вызван
            foreach ($callback as $subject) {
                $method = 'check' . ucfirst($subject);
                if (method_exists($this, $method)) {
                    $errMessage = $this->$method();
                    if (!empty($errMessage)) {
                        $this->container[$subject] = $errMessage;
                    }
                } // в противном случае будут вызваны стандартные методы с подстановкой данных $subject
                else {
                    $this->container->set($subject, $this->formalCheck($subject));
                    // (при добавлении) если указана модель и предыдущие не вызвали ошибки, то будет проверка
                    if (isset($this->model) && empty($this->container->get($subject))) {
                        $this->container->set($subject, $this->duplicateCheck($subject));
                    }
                }
            }
        }

        protected function formalCheck(string $subject): string
        {
            $value = $this->forms->get($subject);
            if(!preg_match(static::$regexp[$subject], $value)) {
                return static::$errorsMessages[$subject][0];
            }
            return '';
        }

        protected function duplicateCheck(string $subject): string
        {
            $value = $this->forms->get($subject);
            $one = $this->model::findOneBy($subject, $value);
            if ($one->exist() && $one->getId() !== $this->model->getId()) {
                return static::$errorsMessages[$subject][1];
            }
            return '';
        }

        public function getErrorsContainer(): ErrorsContainer
        {
            return $this->container;
        }
    }
