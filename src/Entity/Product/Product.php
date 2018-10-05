<?php

namespace App\Entity\Product;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=45, unique=true)
     */
    private $slug;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=500)
     */
    private $description;
    
    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;
    
    /**
     * @var int
     * 
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;
    
    /**
     * @ORM\ManyToOne(targetEntity="\App\Entity\Product\Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     */
    private $category;
    
    /**
     * @ORM\OneToMany(targetEntity="\App\Entity\Product\Image", mappedBy="product", cascade={"persist"})
     */
    private $images;
    
    
    public function __construct() 
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString()
    {        
        return $this->getName();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
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
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }
    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }
    
    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Product
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }
    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
    
    /**
     * Set category to product
     * 
     * @param \App\Entity\Product\Category $category
     * @return \App\Entity\Product\Product
     */
    public function setCategory(\App\Entity\Product\Category $category = null) 
    {        
        $this->category = $category;
        
        return $this;
    }
    
    /**
     * Get category
     * 
     * @return \App\Entity\Product\Category
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    
    
    
    
    
    
    
    
//    /**
//     * Add images into images collection
//     * 
//     * @param \App\Entity\Product\Image $image
//     * @return $this
//     */
//    public function setImages(\App\Entity\Product\Image $image = null)
//    {        
//        $image->setProduct($this);
//        $this->images[] = $image;
//        
//        return $this;
//    }
//    
//    /**
//     * Add image
//     * 
//     * @param \App\Entity\Product\Image $image
//     * @return $this
//     */
//    public function addImages(\App\Entity\Product\Image $image)
//    {
//        $this->images[] = $image;
//        
//        return $this;
//    }
//    
//    /**
//     * Remove image
//     * 
//     * @param \App\Entity\Product\Image $image
//     */
//    public function removeImages(\App\Entity\Product\Image $image)
//    {
//        $this->images->removeElement($image);
//    }
//    
//    /**
//     * Get images
//     * 
//     * @return \Doctrine\Common\Collections\ArrayCollection
//     */
//    public function getImages()
//    {    
//        return $this->images;
//    }
    
    

    
    
    
//    /**
//     * Get images
//     * 
//     * @return \Doctrine\Common\Collections\ArrayCollection
//     */
//    public function getImages()
//    {    
//        return $this->images;
//    }
    

    
    
//    public function addImages(\App\Entity\Product\Image $image)
//    {
//        if (!$this->images->contains($image)) {
//            $this->images->add($image);
//        }
//    }
//    
//    public function removeImages(\App\Entity\Product\Image $image)
//    {
//        $this->images->removeElement($image);
//    }
    
    

    
//    public function addImages(\App\Entity\Product\Image $image)
//    {
//        $image->setProduct($this);
//
//        $this->images->add($image);
//    }
//    
//    public function removeImages(\App\Entity\Product\Image $image)
//    {
//        $this->images->removeElement($image);
//    }
    
    
    
    
    
    
    

    
    
    
    
    /**
     * Add images into images collection
     * 
     * @param \App\Entity\Product\Image $image
     * @return $this
     */
    public function setImages(\App\Entity\Product\Image $image = null)
    {        
        $image->setProduct($this);
        $this->images[] = $image;

        return $this;
    }
    
    /**
     * Add image
     *
     * @param \App\Entity\Product\Image $image
     *
     * @return Product
     */
    public function addImages(\App\Entity\Product\Image $image)
    {
        $this->images[] = $image;
        $image->setProduct($this);
        
        return $this;
    }
    
    /**
     * Remove image
     *
     * @param \App\Entity\Product\Image $image
     */
    public function removeImages(\App\Entity\Product\Image $image)
    {
        $this->images->removeElement($exp);
    }
    
    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }
}