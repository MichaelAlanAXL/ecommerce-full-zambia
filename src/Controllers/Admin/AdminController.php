<?php

namespace App\Controllers\Admin;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use App\Models\Categories;
use App\Models\Products;

class AdminController 
{
    private Twig $twig;

    public function __construct(Twig $twig) 
    {
        $this->twig = $twig;
    }    

    private function render(Response $response, string $template, array $data = [], bool $withLayout = true): Response 
    {
        if ($withLayout) {
            $response = $this->twig->render($response, 'admin/header.html.twig', $data);
        }

        $response = $this->twig->render($response, $template . '.html.twig', $data);

        if ($withLayout) {
            $response = $this->twig->render($response, 'admin/footer.html.twig', $data);
        }

        return $response;
    }

    // Dashboard Admin
    public function dashboard(Request $request, Response $response, $args): Response
    {
        $categories = Categories::all();

        return $this->render($response, "admin/dashboard", [
            'title' => 'Painel Administrativo',
            'username' => 'Michael',
            'totalCategories' => count($categories),
            'totalProducts' => 20, // TODO: count(Products::all()),
            'totalOrders' => 3,
            'categories' => $categories
        ]);
    }

    // List Categories
    public function listCategories(Request $request, Response $response, $args): Response
    {
        $categories = Categories::all();

        return $this->render($response, "admin/categories", [
            'title' => 'Categorias',
            'categories' => $categories
        ]);
    }

    // Create category (POST)
    public function createCategory(Request $request, Response $response, $args): Response
    {
        $data = (array)$request->getParsedBody();
        $descategory = $data['descategory'] ?? null;

        if ($descategory) {
            Categories::create($descategory);
        }
        
        return $response
            ->withHeader('Location', '/admin/categorias')
            ->withStatus(302);
    }

    // Edit category (GET)
    public function editCategory(Request $request, Response $response, $args): Response
    {
        $idcategory = (int)$args['idcategory'];
        $category = Categories::find($idcategory);        

        return $this->render($response, "admin/edit_category", [
            'title' => 'Editar Categoria',
            'category' => $category
        ]);
    }

    // Edit category (POST)
    public function editCategoryPost(Request $request, Response $response, $args): Response
    {
        $idcategory = (int)$args['idcategory'];
        $data = $request->getParsedBody();
        $descategory = $data['descategory'] ?? null;

        if ($idcategory && $descategory) {
            Categories::update($idcategory, $descategory);
        }
        
        return $response
            ->withHeader('Location', '/admin/categorias')
            ->withStatus(302);
    }

    // Delete category
    public function deleteCategory(Request $request, Response $response, $args): Response
    {
        $idcategory = (int)$args['idcategory'];

        if ($idcategory) {
            Categories::delete($idcategory);
        }
        
        return $response
            ->withHeader('Location', '/admin/categorias')
            ->withStatus(302);
    }

    // List Products
    public function listProducts(Request $request, Response $response, $args): Response
    {
        $products = Products::all();

        return $this->render($response, "admin/products", [
            'title' => 'Produtos',
            'products' => $products
        ]);
    }

    // formulário de novo produto
    public function newProductForm(Request $request, Response $response, $args): Response
    {
        $categories = Categories::all();

        return $this->render($response, "admin/new_product", [
            'title' => 'Novo Produto',
            'categories' => $categories
        ]);
    }

    // Salvar no banco
    public function storeProduct(Request $request, Response $response, $args): Response
    {
        $data = $request->getParsedBody();
        
        $desproduct = $data['desproduct'] ?? null;
        $vlprice = $data['vlprice'] ?? null;
        $vlwidth = $data['vlwidth'] ?? null;
        $vlheight = $data['vlheight'] ?? null;
        $vllength = $data['vllength'] ?? null;
        $vlweight = $data['vlweight'] ?? null;
        $url = $data['url'] ?? null;
        $idcategory = $data['idcategory'] ?? null;

        if ($desproduct && $vlprice && $vlwidth && $vlheight && $vllength && $vlweight && $url && $idcategory) {
            Products::create([
                'desproduct' => $desproduct,
                'vlprice' => $vlprice,
                'vlwidth' => $vlwidth,
                'vlheight' => $vlheight,
                'vllength' => $vllength,
                'vlweight' => $vlweight,
                'url' => $url,
                'idcategory' =>$idcategory
            ]);
        }
        
        return $response
            ->withHeader('Location', '/admin/produtos')
            ->withStatus(302);
    }

    // formulário de edição de produto
    public function editProductForm(Request $request, Response $response, $args): Response
    {
        $idproduct = (int)$args['idproduct'];
        $product = Products::find($idproduct);        
        $categories = Categories::all();

        return $this->render($response, "admin/edit_product", [
            'title' => 'Editar Produto',
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function updateProduct(Request $request, Response $response, $args): Response
    {
        $idproduct = (int)$args['idproduct'] ?? null;
        $data = $request->getParsedBody();
        
        $desproduct = $data['desproduct'] ?? null;
        $vlprice = $data['vlprice'] ?? null;
        $vlwidth = $data['vlwidth'] ?? null;
        $vlheight = $data['vlheight'] ?? null;
        $vllength = $data['vllength'] ?? null;
        $vlweight = $data['vlweight'] ?? null;
        $url = $data['url'] ?? null;
        $idcategory = $data['idcategory'] ?? null;
        

        if ($idproduct && $desproduct && $vlprice && $vlwidth && $vlheight && $vllength && $vlweight && $url && $idcategory) {
            Products::update($idproduct, [
                'desproduct' => $desproduct,
                'vlprice' => $vlprice,
                'vlwidth' => $vlwidth,
                'vlheight' => $vlheight,
                'vllength' => $vllength,
                'vlweight' => $vlweight,
                'url' => $url,
                'idcategory' =>$idcategory
            ]);
        }
        
        return $response
            ->withHeader('Location', '/admin/produtos')
            ->withStatus(302);
    }

    // Deletar produto
    public function deleteProduct(Request $request, Response $response, $args): Response
    {
        $idproduct = $args['idproduct'] ?? null;
        
        if ($idproduct) {
            Products::delete($idproduct);
        }
        return $response
            ->withHeader('Location', '/admin/produtos')
            ->withStatus(302);
    }
}