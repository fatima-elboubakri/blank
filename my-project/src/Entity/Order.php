<?php
namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 * @ORM\Table(name="commerce_order")
 * @ApiResource()
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue("AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="orders")
     */
    private $products;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders")
     */
    private $user;
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateOrdered;
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return Product[]|Collection
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }
    public function setProducts($products)
    {
        $this->products = $products;
        return $this;
    }
    public function getUser()
    {
        return $this->user;
    }
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
    public function getDateOrdered()
    {
        return $this->dateOrdered;
    }
    public function setDateOrdered(DateTimeInterface $dateOrdered)
    {
        $this->dateOrdered = $dateOrdered;
        return $this;
    }
}