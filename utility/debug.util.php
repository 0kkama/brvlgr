<?php

    use App\classes\Config;

    // фуя перехвата ошибок для set_error_handler
    function err_catcher(int $errNo, string $errMsg, string $errFile, string $errLine) : void
    {
//        $currentDate = date('d-m-Y');
        $currentDate = date('Y-m-d');
        $currentTime = date('H:i:s');
        $errPath = __DIR__ . '/../logs/errors';

        if ($errNo === E_USER_ERROR) {
            // если ошибка сурьёзная
            echo 'ERROR!';
            $msgStr = "$currentTime - $errNo - $errMsg in $errFile line:$errLine";
//            error_log("$msgStr\n", 3, __DIR__ . "logs/errors/err_$currentDate.log");
            error_log("$msgStr\n", 3, "$errPath/err_$currentDate.log");
        } else {
            // если ошибка не такая сурьёзная
            echo 'NOTICE!';
            $msgStr = "$currentTime - $errNo - $errMsg in $errFile line:$errLine";
//            error_log("$msgStr\n", 3, "logs/errors/notc_$currentDate.log");
            error_log("$msgStr\n", 3, "$errPath/notc_$currentDate.log");
        }
    }

    function var_dump_pre($mixed = null)
    {
        echo '<pre>';
        var_dump($mixed);
        echo '</pre>';
        return null;
    }
