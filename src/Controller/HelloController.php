<?php

namespace App\Controller;

use App\Taxes\Calculator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController
{
    protected $calculator;

    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @route("/", name="index")
     */
    public function index()
    {
        $tva = $this->calculator->cacul('100');
        dd($tva);
        return new Response("HelloController");
    }

    /**
     * @route("hello/{name?world}", name="hello")
     */
    public function hello(Request $request, $name)
    {

        return new Response("hello $name");
    }
}
