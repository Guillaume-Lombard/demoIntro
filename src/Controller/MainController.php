<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends  AbstractController
{

    /**
     * @Route("/", name="main_home")
     */
    public function home(){

        die("Coucou !");
    }

    /**
     * @Route("/test", name="main_test")
     */
    public function test(){

        die("C'est le test !");
    }
}