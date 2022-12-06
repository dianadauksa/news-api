<?php

use App\Redirect;
use App\View;
use App\ViewVariables\AuthViewVariables;
use Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once 'vendor/autoload.php';
session_start();

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
$loader = new FilesystemLoader('views');
$twig = new Environment($loader);

$authVariables = [
    AuthViewVariables::class
];
/** @var AuthViewVariables $variable */
foreach ($authVariables as $variable) {
    $variable = new $variable;
    $twig->addGlobal($variable->getName(), $variable->getValue()); //auth.name likt twigā
}

/*$twig->addFunction(

    new TwigFunction('findNameByID', function (int $id): ?string {
        $name = (new RegisterService())->getConnection()->executeQuery(
            "SELECT name FROM Users WHERE id= ?", [$id]
        )->fetchAssociative()["name"];
        if (!$name) {
            return null;
        }
        return $name;
    }));
*/

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $route) {
    $route->addRoute('GET', '/', ['App\Controllers\ArticleController', 'index']);
    $route->addRoute('GET', '/register', ['App\Controllers\RegisterController', 'index']);
    $route->addRoute('POST', '/register', ['App\Controllers\RegisterController', 'register']);
    $route->addRoute('GET', '/login', ['App\Controllers\LoginController', 'index']);
    $route->addRoute('POST', '/login', ['App\Controllers\LoginController', 'login']);
    $route->addRoute('GET', '/logout', ['App\Controllers\LoginController', 'logout']);
    $route->addRoute('GET', '/userprofile', ['App\Controllers\UserProfile', 'index']);
    $route->addRoute('POST', '/userprofile', ['App\Controllers\UserProfile', 'updateUserInfo']);
    $route->addRoute('POST', '/userprofile-pass', ['App\Controllers\UserProfile', 'updateUserPassword']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo "Page not found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo "Method not allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        [$controller, $method] = $handler;

        $response = (new $controller)->{$method}($vars);
        if ($response instanceof View) {
            echo $twig->render($response->getTemplatePath() . '.twig', $response->getProperties());
        }

        if ($response instanceof Redirect) {
            header('Location: ' . $response->getUrl());
        }
        break;
}

