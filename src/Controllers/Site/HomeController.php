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
            'title' => $category['descategory'],
            'category' => $category,
            'products' => $products
        ]);
    }

    // productDetail por id
    public function productDetail(Request $request, Response $response, $args): Response
    {
        $idproduct = (int)$args['idproduct'] ?? 0;
        $product = Products::find($idproduct);

        if (!$product) {
            throw new HttpNotFoundException($request, "Product not found.");
        }

        $category = Categories::find((int)$product['idcategory']);

        return $this->render($response, "site/product_detail", [
            'title' => $product['desproduct'],
            'product' => $product,
            'category' => $category
        ]);
    }

    // productDetail por url amigavel (slug bonitinho)
    public function productDetailByUrl(Request $request, Response $response, $args): Response
    {
        $url = $args['url'] ?? null;
        if (!$url) {
            throw new HttpNotFoundException($request, "Product URL not found.");
        }

        $product = Products::findByUrl($url);

        if (!$product) {
            throw new HttpNotFoundException($request, "Product not found.");
        }

        $category = Categories::find((int)$product['idcategory']);

        return $this->render($response, "site/product_detail", [
            'title' => $product['desproduct'],
            'product' => $product,
            'category' => $category
        ]);
    }

    public function login(Request $request, Response $response, $args): Response
    {
        return $this->render($response, "site/login", [
            "title" => "Login"
        ], false); // without header/footer
    }
}
