<?php
    //<editor-fold desc="INITIALIZATION">
    declare(strict_types=1);
    setlocale(LC_ALL, "ru_RU.UTF-8");
    date_default_timezone_set('Europe/Moscow');
    error_reporting(E_ALL);

    use App\classes\Config;
    use App\classes\controllers\Error;
    use App\classes\exceptions\CustomException;
    use App\classes\exceptions\ExceptionWrapper as MyExWrapper;
    use App\classes\utility\LoggerForExceptions;
    use App\classes\utility\Router;
    use App\classes\utility\EmailSender;
    use App\classes\View;
    use SebastianBergmann\Timer\ResourceUsageFormatter;

    // set composer autoload
    require __DIR__ . '/../vendor/autoload.php';

    // set autoload
    spl_autoload_register(static function($className) {
        $include = __DIR__ . '/../' . str_replace('\\', '/', $className) . '.php';
        if (is_readable($include)) {
            require_once $include;
        } else {
            trigger_error("Ошибка при попытке подключения класса $className. Файл $include не существует или повреждён");
            Error::deadend(400, 'Ошибка при подключении класса');
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
        trigger_error("Контроллер несуществующего класса $className в " . __FILE__ . __LINE__ );
        Error::deadEnd(400);
    }

    try {
        // запуск контроллера с параметрами
        (new $className($params, new View))();
    }
    catch (CustomException|MyExWrapper $ex ) {
        (new LoggerForExceptions($ex, new EmailSender))();
        Error::deadend($ex->getHttpCode(), $ex->getAlert());
        //                var_dump($ex);
    } catch (Exception $ex) {
        (new LoggerForExceptions($ex, new EmailSender))();
        Error::deadend();
        //        var_dump($ex);
    }

//    вывод данных о ресурсах
    echo (new ResourceUsageFormatter)->resourceUsageSinceStartOfRequest();

    //<editor-fold desc="TODO">
/* TODO
    - допилить MultiException
    - решить проблему с повторной отправкой данных при F5 на Login и Gallery
    - решить проблему с костылями и путями на Login и Gallery
    - доделать конфиг nginx для запрета доступа ко всем директориям, кроме public
          а так же файлам типо /css/style.css
    - https://stackoverflow.com/questions/40966017/nginx-deny-access-of-a-directory-and-files-inside-it
    - сделать доступ по домену (http)
    1. + Добавьте в свой проект класс исключений, возникающих при работе с базой данных.
            Придумайте - где их можно бросать? Как вариант - нет соединения с БД, ошибка в запросе.
    2. Ловите исключения из пункта 1 во фронт-контроллере, поймав же,
       выдавайте пользователю красивую страницу с сообщением об ошибке.
    3. Добавьте класс исключений, означающих "Ошибка 404 - не найдено". Бросайте такое исключение в ситуациях,
       когда вы не можете найти в базе запрашиваемую запись. Добавьте обработку исключений этого типа во фронт-контроллер.
    4. Добавьте в модель новостей (а лучше - в базовую модель) метод fill(array $data),
       который заполняет свойства модели данными из массива. Примените в этом методе паттерн "Мультиисключение".
        + 5*. Добавьте в свой проект класс-логгер. Его задача - записывать в текстовый лог информацию об
       ошибках - когда и где возникла ошибка, какая. Логируйте исключения из пунктов 1 и 3.
*/

//    TODO допилить или переделать способ парсинга в Роутере и
//    TODO разобраться с проблемой в релокейтере
        //</editor-fold>


