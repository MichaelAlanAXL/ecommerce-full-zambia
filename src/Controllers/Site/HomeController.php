<?php

namespace App\Controllers\Site;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

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
        return $this->render($response, "site/home", [
            'title' => 'Home',
            'username' => 'Michael',
            'cartCount' => 0
        ]);
    }

    public function login(Request $request, Response $response, $args): Response
    {
        return $this->render($response, "site/login", [
            "title" => "Login"
        ], false); // without header/footer
    }
}
