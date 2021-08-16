<?php

namespace App\Controller;

use App\Taxes\Detector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController
{
    // protected $calculator;

    // public function __construct(Calculator $calculator)
    // {
    //     $this->calculator = $calculator;
    // }



    /**
     * @route("/", name="index")
     */
    public function index(Detector $detector)
    {
        dump($detector->detect(101));
        dump($detector->detect(10));
        // $tva = $this->calculator->cacul(100);
        return new Response("hello world");
    }

    /**
     * @route("hello/{name?world}", name="hello")
     */
    public function hello(Request $request, $name)
    {

        return new Response("hello $name");
    }
}
