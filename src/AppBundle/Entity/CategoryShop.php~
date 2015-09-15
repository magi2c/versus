<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoryShop
 */
class CategoryShop
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Category
     */
    private $category;

    /**
     * @var \AppBundle\Entity\Shop
     */
    private $shop;


    /**
     * Set name
     *
     * @param string $name
     * @return CategoryShop
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

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     * @return CategoryShop
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set shop
     *
     * @param \AppBundle\Entity\Shop $shop
     * @return CategoryShop
     */
    public function setShop(\AppBundle\Entity\Shop $shop = null)
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * Get shop
     *
     * @return \AppBundle\Entity\Shop 
     */
    public function getShop()
    {
        return $this->shop;
    }
}
