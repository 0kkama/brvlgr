<?php

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

    function checkUploadedImage (array $image) : string
    {
        $errors = [];
        $errMssg = '';

        if ($image['size'] == 0) {
            $errors[] = 'Файл не выбран';
            return $errors[0];
        }

        if ($image['size'] > 2000000) {
            $errors[] = 'Превышен допустимый размер файла';
            return $errors[0];
        }

        if ((preg_match("`^[-0-9A-Z_\.]\.jpe?g$`i", $image['name']))) {
            $errors[] = 'Некорректное расширение или имя файла. Допустим только jpg-формат!';
        }

        if ((mb_strlen($image['name'], "UTF-8") > 225)) {
            $errors[] = 'Имя файла больше допустимой длины';
        }

        if (!($image['type'] === 'image/jpeg' xor $image['type'] === 'image/jpg')) {
            $errors[] = 'Некорректный формат файла. Допустим только jpg-формат!';
        }

        if ($errors !== []) {
            foreach ($errors as $error) {
                $errMssg .= $error;
                $errMssg .= "<br>";
            }
        }

        return $errMssg;
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

