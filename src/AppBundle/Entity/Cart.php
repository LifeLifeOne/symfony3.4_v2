<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 *
 * @ORM\Table(name="cart")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartRepository")
 */
class Cart
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
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Users")
     */
    private $user;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Articles")
     */
    private $article;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity_order", type="integer")
     */
    private $quantityOrder;

    /**
     * @var string
     *
     * @ORM\Column(name="date_order", type="string", length=255)
     */
    private $dateOrder;

    /**
     * @var float
     *
     * @ORM\Column(name="total_order", type="float")
     */
    private $totalOrder;


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
     * Set quantityOrder
     *
     * @param integer $quantityOrder
     *
     * @return Cart
     */
    public function setQuantityOrder($quantityOrder)
    {
        $this->quantityOrder = $quantityOrder;

        return $this;
    }

    /**
     * Get quantityOrder
     *
     * @return integer
     */
    public function getQuantityOrder()
    {
        return $this->quantityOrder;
    }

    /**
     * Set dateOrder
     *
     * @param string $dateOrder
     *
     * @return Cart
     */
    public function setDateOrder($dateOrder)
    {
        $this->dateOrder = $dateOrder;

        return $this;
    }

    /**
     * Get dateOrder
     *
     * @return string
     */
    public function getDateOrder()
    {
        return $this->dateOrder;
    }

    /**
     * Set totalOrder
     *
     * @param float $totalOrder
     *
     * @return Cart
     */
    public function setTotalOrder($totalOrder)
    {
        $this->totalOrder = $totalOrder;

        return $this;
    }

    /**
     * Get totalOrder
     *
     * @return float
     */
    public function getTotalOrder()
    {
        return $this->totalOrder;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\Users $user
     *
     * @return Cart
     */
    public function setUser(\AppBundle\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\Users
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set article
     *
     * @param \AppBundle\Entity\Articles $article
     *
     * @return Cart
     */
    public function setArticle(\AppBundle\Entity\Articles $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \AppBundle\Entity\Articles
     */
    public function getArticle()
    {
        return $this->article;
    }
}
