<?php
    //<editor-fold desc="INITIALIZATION">
    // base settings
    declare(strict_types=1);
    setlocale(LC_ALL, "ru_RU.UTF-8");
    date_default_timezone_set('Europe/Moscow');
    error_reporting(E_ALL);
    // include DEBUGGER
    include_once (__DIR__ . '/../utility/debug.util.php');
    set_error_handler('err_catcher', E_ALL);

    use App\classes\Config;
    use App\classes\controllers\Error;
    use App\classes\exceptions\FullException;
    use App\classes\utility\Logger;
    use App\classes\utility\Router;
    use FastRoute\RouteCollector;

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

    // set config instance
    Config::getInstance()->setInstance(include (__DIR__ . '/../config/config.php'));

    // include library
    include_once (__DIR__ . '/../models/libra.php');
    //</editor-fold>

    //    /var/lib/php/sessions
    session_start();

//    $dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
//
//        $r->addRoute('GET', '/users', 'get_all_users_handler');
//        // {id} must be a number (\d+)
//        $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
//        // The /{title} suffix is optional
////        $r->addRoute('GET', '/article/{id:\d+}', 'get_article_handler');
//
////        App\classes\controllers
//
//    $r->addGroup('/article', function (RouteCollector $r) {
//        $r->get('/{action:read}/{id:\d+}', 'article');
//        $r->get('/{action:add}/', 'article');
//        $r->get('/{action:edit}/{id:\d+}', 'article');
//        $r->get('/{action:delete}/{id:\d+}', 'article');
//    });
//
//    });
//
//    // Fetch method and URI from somewhere
//    $httpMethod = $_SERVER['REQUEST_METHOD'];
////    /article/read/33
////    $uri = $_SERVER['REQUEST_URI'];
//    $uri = '/article/read/33';
//
//    $router = new Router($uri);
//    $params = $router();
//
//    // Strip query string (?foo=bar) and decode URI
//    if (false !== $pos = strpos($uri, '?')) {
//        $uri = substr($uri, 0, $pos);
//    }
//    $uri = rawurldecode($uri);
//
//    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
//    switch ($routeInfo[0]) {
//        case FastRoute\Dispatcher::NOT_FOUND:
//            // ... 404 Not Found
//            break;
//        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
//            $allowedMethods = $routeInfo[1];
//            // ... 405 Method Not Allowed
//            break;
//        case FastRoute\Dispatcher::FOUND:
//            $handler = $routeInfo[1];
//            $vars = $routeInfo[2];
//            // ... call $handler with $vars
//            break;
//    }
//
//
//
//    var_dump($routeInfo, $handler, $vars, $params);


    // import the Intervention Image Manager Class
    use Intervention\Image\ImageManagerStatic as Image;
    use Intervention\Image\ImageManager;


    // configure with favored image driver (gd by default)
    $origPath = Config::getInstance()->IMG_PATH;
    $prePath = Config::getInstance()->IMG_PRE;
    $list = glob($origPath.'*.{jpg,jpeg}', GLOB_BRACE);

    // foreach ($list as $index => $item) {
    //     $name = substr($item, strrpos($item, '/') + 1);
    //     $newName = makeToken(16).'.jpg';
    //     $newOrig = $origPath . $newName;
    //     $oldPreFile = $prePath . 'pre_' . $name;
    //     $newPreFile = $prePath . $newName;
    //     rename($item, $newOrig);
    //     rename($oldPreFile, $newPreFile);
    // }



//    var_dump($list);



//
//    Image::configure(array('driver' => 'imagick'));
//
//    foreach ($list as $index => $item) {
//        $name = substr($item, strrpos($item, '/') + 1);
////        echo $name . '<br>';
//        $image = Image::make($item)->resize(200, 200)->save($prePath."pre_$name");
//    }

//$manager = new ImageManager(array('driver' => 'imagick'));
//$image = $manager->make($list[0])->resize(200, 200);
//$image->save('/home/proletarian/Desktop/2/0/'.'changed.jpg');
