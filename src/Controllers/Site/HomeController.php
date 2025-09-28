<?php

namespace App\Controllers\Site;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use App\Models\Products;
use App\Models\Categories;
use Slim\Exception\HttpNotFoundException;

class HomeController 
{
    private Twig $twig;

    public function __construct(Twig $twig) 
    {
        $this->twig = $twig;
    }    

    private function render(Response $response, string $template, array $data = [], bool $withLayout = true): Response 
    {
        if ($withLayout) {
            $response = $this->twig->render($response, 'site/header.html.twig', $data);
        }

        $response = $this->twig->render($response, $template . '.html.twig', $data);

        if ($withLayout) {
            $response = $this->twig->render($response, 'site/footer.html.twig', $data);
        }

        return $response;
    }

    public function index(Request $request, Response $response, $args): Response
    {
        $products = Products::all();
        
        return $this->render($response, "site/home", [
            'title' => 'Home',
            'products' => $products,
            'username' => 'Michael',
            'cartCount' => 0
        ]);
    }

    public function productsByCategory(Request $request, Response $response, $args): Response
    {
        $idcategory = (int)$args['idcategory'];
        $products = Products::findByCategory($idcategory);

        return $this->render($response, "site/products_by_category", [
            'title' => 'Produtos da Categoria' . htmlspecialchars($idcategory),
            'products' => $products
        ]);
    }

    public function category(Request $request, Response $response, $args): Response
    {
        $slug = $args['slug'];
        $category = Categories::findBySlug($slug);

        if (!$category) {
            throw new HttpNotFoundException($request, "Category not found.");
        }

        $products = Products::findByCategory($category['idcategory']);

        return $this->render($response, "site/category_products", [
            'category' => $category,
            'products' => $products
        ]);
    }

    public function login(Request $request, Response $response, $args): Response
    {
        return $this->render($response, "site/login", [
            "title" => "Login"
        ], false); // without header/footer
    }
}
