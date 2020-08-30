<?php


namespace App\Controllers;
use Zend\Diactoros\Response\HtmlResponse;

class BaseController
{
    protected $temaplateEngine;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader('../views');
        $this->temaplateEngine=new \Twig\Environment($loader, [
            'cache' => false,
            'debug' =>true
        ]);
    }

    public function renderHTML($fileName,$data=[]){
        return new HtmlResponse($this->temaplateEngine->render($fileName,$data));
    }
}