<?php
use Slim\App;
use Slim\Views\Twig;
use App\Controllers\Admin\AdminController;

return function(App $app, Twig $twig) {   
    $adminController = new AdminController($twig);

    // Dashboard Admin
    $app->get('/admin', [$adminController, 'dashboard']);

    // Categorias    
    $app->get('/admin/categorias', [$adminController, 'listCategories']);
    $app->post('/admin/categorias', [$adminController, 'createCategory']);
    $app->get('/admin/categorias/{idcategory/editar', [$adminController, 'editCategoryForm']);
    $app->post('/admin/categorias/{idcategory}/editar', [$adminController, 'editCategory']);
    $app->get('/admin/categorias/delete/{idcategory}', [$adminController, 'deleteCategory']);

    // Produtos
    $app->get('/admin/produtos', [$adminController, 'listProducts']);
    $app->get('/admin/produtos/novo', [$adminController, 'newProductForm']);
    $app->post('/admin/produtos/novo', [$adminController, 'storeProduct']);

    // editar produto
    $app->get('/admin/produtos/{idproduct}/editar', [$adminController, 'editProductForm']);
    $app->post('/admin/produtos/{idproduct}/editar', [$adminController, 'updateProduct']);

    // deletar produto
    $app->get('/admin/produtos/{idproduct}/deletar', [$adminController, 'deleteProduct']);
};
