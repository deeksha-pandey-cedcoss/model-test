<?php
// print_r(apache_get_modules());
// echo "<pre>"; print_r($_SERVER); die;
// $_SERVER["REQUEST_URI"] = str_replace("/phalt/","/",$_SERVER["REQUEST_URI"]);
// $_GET["_url"] = "/";
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher;

$config = new Config([]);

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');



// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);
$loader->registerNamespaces(
    [
        'MyApp\Handlers' => APP_PATH . '/handlers/',
        'MyApp\Controllers' => APP_PATH . '/controllers/',
        'MyApp\Models' => APP_PATH . '/models/',
        'Tests' => APP_PATH . '/../tests/',
    ]
);

$loader->register();

$container = new FactoryDefault();

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);

$application = new Application($container);
$eventsManager=new Manager();
// $eventsManager->attach(
//     'application:beforeHandleRequest',
//     new MyApp\Handlers\Listener()
// );

$container->set(
    'EventsManager',
    $eventsManager
);
$application->setEventsManager($eventsManager);
$container->set(
    'db',
    function () {
        return new Mysql(
            [
                'host'     => 'mysql-server',
                'username' => 'root',
                'password' => 'secret',
                'dbname'   => 'db',
                ]
            );
        }
);


$container->set(
    'dispatcher',
    function () {
        $dispatcher = new Dispatcher();

        $dispatcher->setDefaultNamespace(
            'MyApp\Controllers'
        );

        return $dispatcher;
    }
);

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
