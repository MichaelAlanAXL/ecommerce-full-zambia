<?php
use Slim\App;
use App\Models\Categories;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function(App $app, Twig $twig) {    

    // Dashboard Admin
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

    // Listar Categorias    
    $app->get('/admin/categorias', function ($request, $response, $args) use ($twig) {
        $categories = Categories::all();

        return $twig->render($response, 'admin/categories.html.twig', [
            'categories' => $categories
        ]);
    });

    // Criar categoria
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

    // Editar categoria (GET)
    $app->get('/admin/categorias/{idcategory}/editar', function ($request, $response, $args) use ($twig) {        
        $idcategory = (int) $args['idcategory'];
        $categoria = Categories::find($idcategory);

        return $twig->render($response, 'admin/editar_categorias.html.twig', [
            'categoria' => $categoria
        ]);
    });

    // Editar categoria (POST)
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

    // Deletar categoria
    $app->get('/admin/categorias/delete/{idcategory}', function ($request, $response, $args) {
        $idcategory = (int) $args['idcategory'];

        if ($idcategory) {
            Categories::delete($idcategory);
        }

        return $response
            ->withHeader('Location', '/admin/categorias')
            ->withStatus(302);
    });
};
