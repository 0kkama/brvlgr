<?php

    namespace App\classes\utility;

    use App\interfaces\SingletonInterface;
    use App\traits\SingletonTrait;

    final class Config implements SingletonInterface
    {
        private array $configurations;
        private array $constants;

        use SingletonTrait;

        public function setInstance($params) : void
        {
            if (!isset($this->configurations)) {
                $this->configurations['db'] = $params['db'];
                $this->configurations['swift'] = $params['swift'];
                $this->configurations['RIGHTS'] = $params['RIGHTS'];
                $this->constants = $params['CONSTANTS'];
            }
        }

        public function getDb(string $name) : string
        {
            return $this->configurations['db'][$name] ?? '';
        }

        public function getSwift(string $name) : string
        {
            return $this->configurations['swift'][$name] ?? '';
        }

        public function getRightsLvl(string $position) : int
        {
            return $this->configurations['RIGHTS'][$position] ?? 0;
        }

        public function getAllRights() : array
        {
            return $this->configurations['RIGHTS'];
        }

        public function __isset($param)
        {

        }

        public function __set($set, $get)
        {
//            запрет на магическую установу параметров
        }

        public function __get($param) : string
        {
            return $this->constants[$param] ?? '';
        }
    }

