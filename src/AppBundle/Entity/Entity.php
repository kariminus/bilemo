<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Entity
 *
 * @ORM\Table(name="entity")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EntityRepository")
 * @UniqueEntity(fields={"email"}, message="Ce mail est déjà utilisé")
 */
class Entity implements UserInterface
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
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     *
     * @var string
     * @Assert\NotBlank(groups={"Registration"}, message="Saisissez un mot de passe")
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = [];


    public function __construct()
    {
        $this->setRoles(['ROLE_USER']);
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
     * Set email
     *
     * @param string $email
     *
     * @return Entity
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getRoles()
    {
        $roles = $this->roles;
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }
        return $roles;
    }
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        $this->password = null;
    }
    public function getSalt()
    {
    }
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }
}

