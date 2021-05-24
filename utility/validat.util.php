<?php
    // короткоименная фуя для простой обработки данных, вводимых пользователем.
    function val(string $inputStr, int $key = 1) : string {
        switch ($key) {
            case 1: $inputStr = trim(strip_tags($inputStr)); break;
            case 2: $inputStr = trim(htmlspecialchars($inputStr)); break;
        }
        return $inputStr;
    }