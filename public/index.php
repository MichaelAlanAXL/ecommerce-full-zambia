<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use App\Controllers\Site\HomeController;
use App\Models\Categories;

$app = AppFactory::create();

$twig = Twig::create(__DIR__ . '/../templates', ['cache' => false]);
$app->add(TwigMiddleware::create($app, $twig));

$homeController = new HomeController($twig);

$app->get('/', [$homeController, 'index']);

$app->get('/login', [$homeController, 'login']);

$app->get('/admin', function ($request, $response, $args) use ($twig) {
    $categories = Categories::all();

    return $twig->render($response, 'admin/dashboard.html.twig', [
        'username' => 'Michael',
        'totalCategories' => count($categories),
        'totalProducts' => 20,
        'totalOrders' => 3,
        'categories' => $categories
    ]);
});

$app->get('/admin/categorias', function ($request, $response, $args) use ($twig) {
    $categories = Categories::all();

    return $twig->render($response, 'admin/categories.html.twig', [
        'categories' => $categories
    ]);
});

$app->post('/admin/categorias', function ($request, $response, $args) {
    $data = $request->getParsedBody();
    $descategory = $data['descategory'] ?? null;

    if ($descategory) {
        Categories::create($descategory);
    }

    return $response
        ->withHeader('Location', '/admin/categorias')
        ->withStatus(302);
});

$app->get('/admin/categorias/{idcategory}/editar', function ($request, $response, $args) use ($twig) {
    $idcategory = (int) $args['idcategory'];
    $categoria = Categories::find($idcategory);

    return $twig->render($response, 'admin/editar_categorias.html.twig', [
        'categoria' => $categoria
    ]);
});

$app->post('/admin/categorias/{idcategory}/editar', function ($request, $response, $args) {
    $idcategory = (int) $args['idcategory'];
    $data = $request->getParsedBody();
    $descategory = $data['descategory'] ?? null;

    if ($idcategory && $descategory) {
        Categories::update($idcategory, $descategory);
    }

    return $response
        ->withHeader('Location', '/admin/categorias')
        ->withStatus(302);
});

$app->get('/admin/categorias/delete/{idcategory}', function ($request, $response, $args) {
    $idcategory = (int) $args['idcategory'];

    if ($idcategory) {
        Categories::delete($idcategory);
    }

    return $response
        ->withHeader('Location', '/admin/categorias')
        ->withStatus(302);
});

$app->run();
