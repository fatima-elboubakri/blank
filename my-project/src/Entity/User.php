<?php
namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity()
 * @ORM\Table(name="user")
 * @ApiResource(
 *     normalizationContext={
 *          "groups"={"user_read"}
 *     }
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue("AUTO")
     * @Groups({"user_read"})
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     * @Groups({"user_read"})
     */
    private $username;
    /**
     * @ORM\Column(type="string")
     */
    private $password;
    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="user")
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
    public function getUsername()
    {
        return $this->username;
    }
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
    /**
     * @return Order[]|Collection
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }
    public function getSalt()
    {
    }
    public function getRoles()
    {
        return ['ROLE_USER'];
    }
    public function eraseCredentials()
    {
    }
}