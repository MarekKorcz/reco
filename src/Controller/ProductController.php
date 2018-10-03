<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends Controller
{
    /**
     * Finds and displays a product entity.
     *
     * @Route("/{slug}", name="product_show")
     * @Method("GET")
     */
    public function showAction($slug)
    {
        $product = $this->getDoctrine()->getRepository('\App\Entity\Product\Product')->findOneBy(array(
            'slug' => $slug
        ));  
        
        return $this->render('product/product_show.html.twig', array(
            'product' => $product
        ));
    }
}
