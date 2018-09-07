<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController {
   
    /**
     * @Route("/", name="main")
     */
    public function mainPage() {
        
        return $this->render('default/main.html.twig');
    }
}