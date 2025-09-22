<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use App\Controllers\Site\HomeController;

$app = AppFactory::create();

$twig = Twig::create(__DIR__ . '/../templates', ['cache' => false]);
$app->add(TwigMiddleware::create($app, $twig));

$homeController = new HomeController($twig);

$app->get('/', [$homeController, 'index']);

$app->get('/login', [$homeController, 'login']);

$app->run();
