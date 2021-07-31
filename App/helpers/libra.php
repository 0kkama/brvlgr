<?php

    use App\classes\Config;

    function addMessage(array $newMessage, string $fileName) : bool
    {
        $newMessage = json_encode($newMessage);
        file_put_contents($fileName, $newMessage . "\n", FILE_APPEND);
        return true;
    }

    function getMessages (string $fileName) : array
    {
        $wrapper = static function (string $line) : array {
            return json_decode($line,true);
        };
        return array_map($wrapper, file($fileName));
    }

    // извлечение данных из форм (массив target) в новый массив, с ключами из массива fields
    /**
     * @param array $fields
     * @param array $target
     * @return array
     */
    function extractFields(array $fields, array $target) : array {
        $result = [];
        foreach ($fields as $field) {
            if (empty($target[$field])) {
                $result[$field] = '';
            }
            else {
                $result[$field] = val($target[$field], 2);
            }
        }
        return $result;
    }

    // короткоименная фуя для простой обработки данных, вводимых пользователем.
    function val(string $inputStr, int $key = 1) : string {
        return match ($key) {
            1 => trim(strip_tags($inputStr)),
            2 => trim(htmlspecialchars($inputStr)),
        };
    }

    /**
     * generate random string (token)
     * @param int $length (by default equal 32)
     * @return string $token (random string in hexadecimal representation. Max length of token is 128 bytes)
     *
     * @throws Exception
     */
    function makeToken ( int $length = 32 ) : string
    {
        if ( $length > 64 ) {
            $token = substr(bin2hex(random_bytes(64)), 0,128);
        } else {
            $token = bin2hex(random_bytes($length));
        }
        return $token;
    }
//    TODO implement custom exception... maybe

    /**
     * Возвращает массив с данными, декодированными из json-файла в случае успеха, либо пустой массив.
     * @param string $fileName
     * @return array
     * @throws JsonException
     */
    function getFileContent (string $fileName) : array
    {
//        TODO проверка на empty оказалась недостаточной в случае наличия пробела или переноса.
//          ДОРАБОТАТЬ!!!
        $content = file($fileName);
        if ( empty($content) ) {
            return [];
        }

        $wrapper = static function (string $line) : array {
            return json_decode($line,true, 8, JSON_THROW_ON_ERROR);
        };
        return array_map($wrapper, $content);
    }

    // функция логирования
    function makeDownloadsLog(string $userName,string $path, string $fileName) : void
    {
        $currentTime =  date('H:i:s');
        $currentDate = date('d-m-Y');
        $msgStr =  "$currentTime - Пользователь $userName загрузил файл $fileName в $path\n";
        file_put_contents(Config::getInstance()->AUTH_LOG . "$currentDate.log", $msgStr, FILE_APPEND);
        //        file_put_contents('/home/proletarian/NBProj/profit/resources/logs/auth/11-01-2021.log', $msgStr, FILE_APPEND);
    }
    // ucfirst для не латинских символов
    function my_mb_ucfirst(string $str) : string {
        $firstCh = mb_strtoupper(mb_substr($str, 0, 1));
        return $firstCh . mb_substr($str, 1);
    }
    // фуя приводит строку к виду: первая буква заглавная, остальные строчные
    function makeFrstLttrUp (string $str) : string {
        $str = mb_strtolower($str);
        $str = my_mb_ucfirst($str);
        return $str;
    }

    // фуя получает число и возвращает количество секунд, равное кол-ву дней от текущей даты
    function getDaysQuant (int $days) : int {
        $seconds = 3600;
        $hours = 24;
        return time() + $seconds * $hours * $days;
    }
