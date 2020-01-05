<?php
namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ApiResource()
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue("AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     * @Assert\Length(max="255")
     * @Assert\NotBlank()
     */
    private $name;
    /**
     * @ORM\Column(type="float")
     * @Assert\GreaterThan(0)
     * @Assert\NotBlank()
     */
    private $price;
    /**
     * @ORM\ManyToMany(targetEntity="Order", inversedBy="products")
     */
    private $orders;
    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }
    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    public function getPrice()
    {
        return $this->price;
    }
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }
    public function setOrders($orders)
    {
        $this->orders = $orders;
    }
}