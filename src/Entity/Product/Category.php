<?php

namespace App\Entity\Product;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=40)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=35, unique=true)
     */
    private $slug;
    
    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    private $kids;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="kids")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;
    
    /**
     * @ORM\OneToMany(targetEntity="\App\Entity\Product\Product", mappedBy="category", cascade={"persist"})
     */
    private $products;
    
    
    
    public function __construct() 
    {
        $this->kids = new \Doctrine\Common\Collections\ArrayCollection();
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function setSlug($slug)
    {
        $this->slug = $slug;
        
        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }
    
    /**
     * Add kid to category kids collection
     * 
     * @param type|NULL $category
     * @return $this
     */
    public function setKids($category)
    {
        if ($category) {
            $category->setParent($this);
            $this->kids[] = $category;
        }
        
        return $this;
    }
    
    /**
     * Get kids
     * 
     * @return \App\Entity\Product\Category
     */
    public function getKids()
    {        
        return $this->kids;
    }
    
    /**
     * Set parent to category
     * 
     * @param \App\Entity\Product\Category $category
     * @return \App\Entity\Product\Category
     */
    public function setParent(\App\Entity\Product\Category $category = null) 
    {        
        $this->parent = $category;
        
        return $this;
    }
    
    /**
     * Get parent
     * 
     * @return \App\Entity\Product\Category
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * Add product to products collection
     * 
     * @param \App\Entity\Product\Product $product
     * @return \App\Entity\Product\Category
     */
    public function setProduct(\App\Entity\Product\Product $product)
    {
        $product->setCategory($this);
        $this->products[] = $product;
        
        return $this;
    }
    
    /**
     * Get products
     * 
     * @return \App\Entity\Product\Product
     */
    public function getProducts()
    {        
        return $this->products;
    }
    
    public function __toString(){

        return $this->getName();
    }
}