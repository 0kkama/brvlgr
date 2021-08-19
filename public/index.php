<?php
    //<editor-fold desc="INITIALIZATION">
    declare(strict_types=1);
    setlocale(LC_ALL, "ru_RU.UTF-8");
    date_default_timezone_set('Europe/Moscow');
    error_reporting(E_ALL);

    use App\classes\Config;
    use App\classes\View;
    use App\classes\controllers\Error;
    use App\classes\exceptions\CustomException;
    use App\classes\exceptions\ExceptionWrapper as MyExWrapper;
    use App\classes\utility\EmailSender;
    use App\classes\utility\LoggerForExceptions;
    use App\classes\utility\Router;
    use SebastianBergmann\Timer\ResourceUsageFormatter;

    // set composer autoload
    require __DIR__ . '/../vendor/autoload.php';

    // set autoload
    spl_autoload_register(static function($className) {
        $include = __DIR__ . '/../' . str_replace('\\', '/', $className) . '.php';
        if (is_readable($include)) {
            require_once $include;
        } else {
            trigger_error("Ошибка при подключении класса $className. Файл $include не существует или повреждён");
//            Error::deadend(400, 'Ошибка при подключении класса');
        }
    });

    set_error_handler('App\classes\utility\LittleLogger::errorCatcher', E_ALL);
    // set config instance
    Config::getInstance()->setInstance(include (__DIR__ . '/../config/config.php'));

    // include library
    include_once (__DIR__ . '/../App/helpers/libra.php');
    //</editor-fold>

    //    /var/lib/php/sessions
    session_start();

    $uri = val($_SERVER['REQUEST_URI']);
    $params = (new Router($uri))();

    $cntrl = ucfirst($params['controller']);
    $className = "App\classes\controllers\\$cntrl";

    if (!class_exists($className)) {
        trigger_error("Контроллер несуществующего класса $className");
        Error::deadEnd(400);
    }

    try {
        // запуск контроллера с параметрами
        (new $className($params, new View))();
    }
    catch (CustomException|MyExWrapper $ex ) {
        (new LoggerForExceptions($ex, new EmailSender))();
        Error::deadend($ex->getHttpCode(), $ex->getAlert());
    } catch (Exception $ex) {
        (new LoggerForExceptions($ex, new EmailSender))();
        Error::deadend();
    }

//    вывод данных о ресурсах
    echo (new ResourceUsageFormatter)->resourceUsageSinceStartOfRequest();

    //<editor-fold desc="TODO">
/* TODO
    - модуль юзер:
        + решить, как именно лучше реализовать вывод ошибок при регистрации (все сразу или сперва только ошибки незаполненных форм)
        - реализовать отправку письма на указанный ящик с кодом проверки и дальнейший ввод этого кода
        + изменить механизм сессий с файла на БД
        - добавить чекбокс с кукой на месяц
    - модуль статьи:
        - добавить проверку контента (по количеству символов)
    - общее:
        - реализовать переход на [Twig]
        - сделать миграцию
        - подключить Телегу для отправки сообщений об ошибках
        - изменить CSS для шаблона регистрации
    - допилить MultiException
    - решить проблему с повторной отправкой данных при F5 на Login и Gallery
    - решить проблему с костылями и путями на Login и Gallery
    - допилить или переделать способ парсинга в Роутере
    - серверная часть
        - доделать конфиг nginx для запрета доступа ко всем директориям, кроме public
          а так же файлам типа /css/style.css
        - https://stackoverflow.com/questions/40966017/nginx-deny-access-of-a-directory-and-files-inside-it
        - сделать доступ по домену [http]
*/

        //</editor-fold>


