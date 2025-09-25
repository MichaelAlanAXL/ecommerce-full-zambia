<?php
use Slim\App;
use App\Controllers\Site\HomeController;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function(App $app, Twig $twig) {

    $homeController = new HomeController($twig);

    // Página inicial
    $app->get('/', [$homeController, 'index']);

    // Página de login
    $app->get('/login', [$homeController, 'login']);

    // Página de categoria dinâmica
    $app->get('/category/{id}', [$homeController, 'category']);
};