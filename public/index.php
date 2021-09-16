<?php
    //<editor-fold desc="INITIALIZATION">
    declare(strict_types=1);
    setlocale(LC_ALL, "ru_RU.UTF-8");
    date_default_timezone_set('Europe/Moscow');
    error_reporting(E_ALL);

    use App\classes\utility\Config;
    use App\classes\utility\loggers\LoggerSelector;
    use App\classes\utility\View;
    use App\classes\controllers\Error;
    use App\classes\abstract\exceptions\CustomException;
    use App\classes\exceptions\ExceptionWrapper as MyExWrapper;
    use App\classes\utility\EmailSender;
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
        }
    });

    set_error_handler('App\classes\utility\loggers\LittleLogger::errorCatcher', E_ALL);
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
        LoggerSelector::exception($ex, new EmailSender);
        Error::deadend($ex->getHttpCode(), $ex->getAlert());
    } catch (Exception $ex) {
        LoggerSelector::exception($ex, new EmailSender);
        Error::deadend();
    }

    var_dump($params);

//    вывод данных о ресурсах
    echo (new ResourceUsageFormatter)->resourceUsageSinceStartOfRequest();

    /* TODO
        - ПЕРВИЧНОЕ
            + решить проблему с меню навигации (каждый должен видеть только нужный набор меню)
            -/+ сделать личный кабинет для пользователя: редактирование параметров записи, смена пароля, ящика и т.д.
            - !!! решить проблему с перенаправлением на статью после написания, помеченную как модерация на 0
            - доступ к редактированию и удалению статьи только у админов, модераторов и у автора
            - пагинация
            + вывод всех статей по конкретной категории
        - админка
            - попробовать использовать полиморфизм в контроллерах и инспекторах. Либо через наследование,
                либо через композицию. Возможно пригодится Reflection API.
        - модуль юзер:
            - реализовать отправку письма на указанный ящик с кодом проверки и дальнейший ввод этого кода
        - модуль статьи:
            - добавить проверку контента (по количеству символов)
        - общее:
            + добавить админку: страницы с выводом всех юзеров, всех статей и действий с ними
                + возможность модерировать статьи: удаление, публикация, пометка как не опубликованная
            + нормализовать БД
            - реализовать переход на [Twig]
            - сделать миграцию
            - подключить Телегу для отправки сообщений об ошибках
            - изменить CSS для шаблона регистрации
        - решить проблему с повторной отправкой данных при F5 на Login и Gallery
        - решить проблему с костылями и путями на Login и Gallery
        - допилить или переделать способ парсинга в Роутере
        - серверная часть
            - доделать конфиг nginx для запрета доступа ко всем директориям, кроме public
              а так же файлам типа /css/style.css
            - https://stackoverflow.com/questions/40966017/nginx-deny-access-of-a-directory-and-files-inside-it
            - сделать доступ по домену [http]
    */
    //<editor-fold desc="TODO">

        //</editor-fold>


