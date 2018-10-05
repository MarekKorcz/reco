<?php

namespace App\Entity\Product;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 */
class Image
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
     * @ORM\Column(type="string", length=50)
     * @var string
     */
    private $imageName;
    
    /**
     * @var File
     */
    private $imageFile;
    
    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;
    
    /**
     * @ORM\ManyToOne(targetEntity="\App\Entity\Product\Product", inversedBy="images")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    

//    public function __construct() {
//        
//        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
//    }

        public function __toString() 
    {
        return $this->getImageName();
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

    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }
    
    public function getImageName()
    {
        return $this->imageName;
    }
    
    public function setImageFile(UploadedFile $image = null)
    {
        $this->imageFile = $image;
        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            
            $this->setUpdatedAt();
        }
    }
    
    public function getImageFile()
    {
        return $this->imageFile;
    }
    
    public function setUpdatedAt() 
    {        
        $this->updatedAt = new \DateTime('now');
        
        return $this;
    }
    
    public function getUpdatedAt() 
    {        
        return $this->updatedAt;
    }
    
    /**
     * Set product
     * 
     * @param \App\Entity\Product\Product $product
     * @return Image
     */
    public function setProduct(\App\Entity\Product\Product $product = null) {
        
        $this->product = $product;
        
        return $this;
    }
    
    /**
     * Get product
     * 
     * @return \App\Entity\Product\Product
     */
    public function getProduct(){
        
        return $this->product;
    }  
}