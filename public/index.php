<?php

use App\Controllers\AuthController;
use App\Controllers\IndexController;
use App\Controllers\UserAdminController;
use App\Middlewares\AuthMiddleware;
use App\Models\SessionModel;
use App\Services\AuthorizeService;
use App\Services\LikesService;
use App\Services\MyPicsService;
use App\Services\ShowNextProfileService;
use App\Services\TinderService;
use App\Services\UploadService;
use App\Services\UsersPicsService;
use App\Validations\LogInValidation;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Repositories\DBInterface;
use App\Repositories\MySQLRepository;


require_once '../vendor/autoload.php';

session_start();
if (isset($_POST['logout'])) {
    session_unset();
}

//Twig
$loader = new FilesystemLoader('../app/Views');
$twig = new Environment($loader);


$container = new League\Container\Container;
$container->add(DBInterface::class, MySQLRepository::class);

$container->add(SessionModel::class, SessionModel::class);

$container->add(UserAdminController::class, UserAdminController::class)
    ->addArguments([$twig,
        UploadService::class,
        MyPicsService::class]);


$container->add(IndexController::class, IndexController::class)
    ->addArguments([$twig,
        UsersPicsService::class,
        ShowNextProfileService::class,
        LikesService::class]);

$container->add(AuthController::class, AuthController::class)
    ->addArguments([$twig,
        AuthorizeService::class,
        LogInValidation::class]);


$container->add(UploadService::class, UploadService::class)
    ->addArgument(DBInterface::class);
$container->add(AuthorizeService::class, AuthorizeService::class)
    ->addArguments([DBInterface::class,
        LogInValidation::class]);

$container->add(LogInValidation::class);
$container->add(UsersPicsService::class, UsersPicsService::class)
    ->addArgument(DBInterface::class);
$container->add(MyPicsService::class, MyPicsService::class)
    ->addArgument(DBInterface::class);
$container->add(ShowNextProfileService::class, ShowNextProfileService::class)
    ->addArgument(DBInterface::class);
$container->add(LikesService::class, LikesService::class)
    ->addArgument(DBInterface::class);


//Routes
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {

    $r->addRoute('POST', '/user', [IndexController::class, 'index']);
    $r->addRoute('GET', '/user', [IndexController::class, 'index']);
    $r->addRoute('POST', '/admin', [UserAdminController::class, 'admin']);
    $r->addRoute('GET', '/admin', [UserAdminController::class, 'admin']);
    $r->addRoute('GET', '/', [AuthController::class, 'auth']);
    $r->addRoute('POST', '/', [AuthController::class, 'auth']);
    $r->addRoute('POST', '/upload', [UserAdminController::class, 'upload']);


});

//Middlewares

$middlewares = [
    IndexController::class . '@index' => [
        AuthMiddleware::class
    ],
    IndexController::class . '@admin' => [
        AuthMiddleware::class
    ]

];

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo 'wrong address, please logIn';

        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:

        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        [$controller, $method] = $handler;

        $middlewareKey = $controller . '@' . $method;
        $controllerMiddlewares = $middlewares[$middlewareKey] ?? [];

        foreach ($controllerMiddlewares as $controllerMiddleware) {
            (new $controllerMiddleware)->handle();
        }

        ($container->get($controller))->$method($vars);
        break;
}

if ($httpMethod == 'GET' && isset($_SESSION['errors'])) {
    unset ($_SESSION['errors']);
}