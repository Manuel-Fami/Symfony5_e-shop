<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */

    public function test()
    {
        dd("page test");
    }
}
