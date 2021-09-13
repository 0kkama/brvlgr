<?php

    namespace App\interfaces;

    use App\classes\utility\containers\ErrorsContainer;
    use App\classes\utility\containers\FormsWithData;

    interface InspectorInterface
    {
        public function conductInspection();
        public function setForms(FormsWithData $forms);
        public function setModel(?ExistenceInterface $model);
        public function setContainer(ErrorsContainer $container);
    }
