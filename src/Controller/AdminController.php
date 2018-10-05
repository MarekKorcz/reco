<?php

namespace App\Controller;

use App\Entity\Product\Category;
use App\Entity\Product\Product;
use App\Entity\Product\Image;
use App\Form\CategoryType;
use App\Utils\Slugger;
use App\Service\FileUploader;
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

                return $this->redirectToRoute('admin_category_list', array());
            }
        }
        
        return $this->render('admin/category_create.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/category/list", name="admin_category_list")
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
                
                return $this->redirectToRoute('admin_category_list', array());
            }
        }
        
        return $this->render('admin/category.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    
    
    
    /**
     * Creates a new product entity.
     * 
     * @Route("/product/new", name="product_new")
     * @Method({"GET", "POST"})
     */
    public function newProductAction(Request $request, FileUploader $fileUploader, Slugger $slugger)
    {
        $product = new Product();
        
//        print "<pre>";
//
//        print_r($product);
//
//        print "</pre>";
        
        $form = $this->createForm('App\Form\ProductType', $product);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $images = $product->getImages();
            
            /*
             * https://stackoverflow.com/questions/43104128/symfony-3-expected-doctrine-common-collections-arraycollection-given-when?rq=1
             * 
             * $form->get('busVehiclesAmenities')->getData()->map(
                function ($amenities) use ($em, $bus) {

                    $bus->setBusVehiclesAmenities($amenities);
                    $em->persist($amenities);
                }
            );*/
            
            foreach ($images as $image) {
                
                $fileName = $fileUploader->upload($image->getImageName());

                $product->setImages($fileName);
                
                // maybe add some $em->persist($image);  later ??
            }
            
            $product->setSlug(Slugger::slugify($product->getName()));
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            
            return $this->redirectToRoute('product_show', array(
                'slug' => $product->getSlug()
            ));
        }
        
        return $this->render('admin/product_new.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }
    /**
     * Displays a form to edit an existing product entity.
     * 
     * @Route("/product/{slug}/edit", name="product_edit")
     * @Method({"GET", "POST"})
     */
    public function editProductAction(Request $request, $slug)
    {
        $product = $this->getDoctrine()->getRepository('\App\Entity\Product\Product')->findOneBy(array(
            'slug' => $slug
        ));        
        
        $deleteForm = $this->createProductDeleteForm($product);
        $editForm = $this->createForm('App\Form\ProductType', $product);
        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('product_edit', array(
                'slug' => $slug
            ));
        }
        
        return $this->render('admin/product_edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Lists of all product entities.
     * 
     * @Route("/product/list", name="admin_product_list")
     * @Method("GET")
     */
    public function listProductsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('\App\Entity\Product\Product')->findAll();
        
        return $this->render('admin/product_list.html.twig', array(
            'products' => $products,
        ));
    }
    
    /**
     * Deletes a product entity.
     *
     * @Route("/product/{slug}", name="product_delete")
     * @Method("DELETE")
     */
    public function deleteProductAction(Request $request, $slug)
    {
        $product = $this->getDoctrine()->getRepository('\App\Entity\Product\Product')->findOneBy(array(
            'slug' => $slug
        ));    
        
        $form = $this->createProductDeleteForm($product);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }
        
        return $this->redirectToRoute('admin_product_list');
    }
    
    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createProductDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('slug' => $product->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
