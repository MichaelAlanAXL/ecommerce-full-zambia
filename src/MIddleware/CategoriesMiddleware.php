<?php

namespace App\Middleware;

use App\Models\Categories;
use Slim\Views\Twig;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ResponseInterface as Response;

class CategoriesMiddleware
{
    private $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Handler $handler): Response
    {
        $categories = Categories::all();
        $this->twig->getEnvironment()->addGlobal('categories', $categories);

        return $handler->handle($request);
    }
}