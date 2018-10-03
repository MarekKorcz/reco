<?php

namespace App\Controller;

use App\Entity\Product\Category;
use App\Form\CategoryType;
use App\Utils\Slugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Admin controller.
 *
 * @Route("/admin")
 */
class AdminController extends AbstractController 
{    
    /**
     * @Route("/users", name="users")
     */
    public function profile()
    {
        return $this->render('admin/users.html.twig', array());
    }
    
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/category/create", name="category_create")
     * @Method({"GET", "POST"})
     */
    public function createCategory(Request $request, Slugger $slugger)
    {
        $category = new Category();
        
        $form = $this->createForm(CategoryType::class, $category);
        
        if ($request->isMethod('POST')) {
            
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $slug = $slugger->slugify($category->getName());

                $category->setSlug($slug);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($category);
                $entityManager->flush();

                return $this->redirectToRoute('category_list', array());
            }
        }
        
        return $this->render('admin/category_create.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/category/list", name="category_list")
     * @Method({"GET", "POST"})
     */
    public function listCategory()
    {
        $categories = $this->getDoctrine()->getRepository('\App\Entity\Product\Category')->findAll();
        
        return $this->render('admin/category_list.html.twig', array(
            'categories' => $categories
        ));
    }
    
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/category/{slug}", name="category")
     * @Method({"GET", "POST"})
     */
    public function category(Request $request, $slug)
    {
        $category = $this->getDoctrine()->getRepository('\App\Entity\Product\Category')->findOneBy(array(
                'slug' => $slug
            ));
        
        // todo: if category exsists
        
        $form = $this->createForm(CategoryType::class, $category);
        
        if ($request->isMethod('POST')) {
            
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                
                $category->setParent($form['parent']->getData());
                
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($category);
                $entityManager->flush();
                
                return $this->redirectToRoute('category_list', array());
            }
        }
        
        return $this->render('admin/category.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
