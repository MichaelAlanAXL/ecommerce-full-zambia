<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

// configurações do RainTPL
\Rain\Tpl::configure([
    "tpl_dir"   => __DIR__ . "/../templates/",
    "cache_dir" => __DIR__ . "/../cache/",
    "debug"     => true
]);

$app = AppFactory::create();

// rota de teste
$app->get('/', function($request, $response) {
    $tpl = new \Rain\Tpl();
    $tpl->assign("name", "Mundo");
    $html = $tpl->draw("home", true);
    $response->getBody()->write($html);
    return $response;
});

$app->run();
