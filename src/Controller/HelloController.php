<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController
{

    /**
     * @route("hello/{name?world}", name="hello")
     */
    public function hello($name)
    {
        return new Response("hello $name");
    }
}
