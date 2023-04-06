<?php

namespace App;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TwigResponse implements ResponseInterface
{
    private string $html;
    
    public function __construct(string $fileName, array $data, private int $code = Response::HTTP_OK)
    {
        $loader = new FilesystemLoader(__DIR__ . '/../templates');
        $twig = new Environment($loader);
        try {
            $this->html = $twig->render($fileName . '.html.twig', $data);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            $this->code = Response::HTTP_NOT_FOUND;
            $this->html = $e->getMessage();
        }
    }
    
    public function send(): string
    {
        http_response_code($this->code);
        return $this->html;
    }
}