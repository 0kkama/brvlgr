<?php

    namespace App\classes;

    use App\interfaces\Singleton;
    use App\traits\SingletonTrait;

    /*
    * TODO
    *   Добавьте в свое приложение класс App\Config. Объект этого класса при создании должен читать и сохранять в себе файл конфигурации. Его применение
    *   $config = new \App\Config; echo $config->data['db']['host'];
    *   пусть это пока коряво смотрится, но по-другому мы еще не умеем
    *   Изучите что такое синглтон (слайды + консультация в чате поддержки) и сделайте класс App\Config синглтоном
    */

    final class Config implements Singleton
    {
        private $configurations = null;
        private $constants;

        use SingletonTrait;

        public function setInstance(array $params) : void
        {
            if (!isset($this->configurations)) {
                $this->configurations['db'] = $params['db'];
                $this->constants = $params['CONSTANTS'];
            }
        }

        public function getDbHost() : string
        {
            return $this->configurations['db']['host'];
        }

        public function getDbName() : string
        {
            return $this->configurations['db']['name'];
        }

        public function getDbUser() : string
        {
            return $this->configurations['db']['user'];
        }

        public function getDbPass() : string
        {
            return $this->configurations['db']['pass'];
        }

        public function getDbChar() : string
        {
            return $this->configurations['db']['char'];
        }

        public function __isset($param)
        {

        }

        public function __set($set, $get)
        {
//            запрет на магическую установу параметров
        }

        public function __get($param) : ?string
        {
            return $this->constants[$param] ?? null;
        }
    }

