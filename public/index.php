<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

$app = AppFactory::create();
$twig = Twig::create(__DIR__ . '/../templates', ['cache' => false]);
$app->add(new \App\Middleware\CategoriesMiddleware($twig));
$app->add(TwigMiddleware::create($app, $twig));

(require __DIR__ . '/../public/routes_site.php')($app, $twig);
(require __DIR__ . '/../public/routes_admin.php')($app, $twig);

$app->run();
