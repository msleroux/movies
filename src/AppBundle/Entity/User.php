<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;


/**
 * User
 * @UniqueEntity("username",message="This username is already used !")
 * @UniqueEntity("email",message="This email is already registered for an account !")
 *
 * @ORM\Table(name="app_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface
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
     * @Assert\Length(
     *     min=4,
     *     max=30,
     * )
     * @Assert\NotBlank(message="Please choose a username !")
     * @Assert\Regex("/^[a-z0-9_-]+$/",message="your username must match our regex!")
     * @ORM\Column(name="username", type="string", length=30, unique=true)
     */
    private $username;

    /**
     * @var string
     * @Assert\NotBlank(message="Please enter an email !")
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=6,
     *     max=4000
     *     )
     * @ORM\Column(name="password", type="string", length=255)
     *
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="roles", type="string", length=30)
     */
    private $roles;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateRegistered", type="datetime")
     */
    private $dateRegistered;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Critique", mappedBy="user")
     */
    private $critiques;


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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
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

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set roles
     *
     * @param string $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return string
     */
    public function getRoles()
    {
        //cette fonction doit toujours renvoyer un tableau
        return [$this->roles];
    }

    /**
     * Set dateRegistered
     *
     * @param \DateTime $dateRegistered
     *
     * @return User
     */
    public function setDateRegistered($dateRegistered)
    {
        $this->dateRegistered = $dateRegistered;

        return $this;
    }

    /**
     * Get dateRegistered
     *
     * @return \DateTime
     */
    public function getDateRegistered()
    {
        return $this->dateRegistered;
    }


    //méthodes inutiles mais obligatoires dans le userInterface
    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->critiques = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add critique
     *
     * @param \AppBundle\Entity\Critique $critique
     *
     * @return User
     */
    public function addCritique(\AppBundle\Entity\Critique $critique)
    {
        $this->critiques[] = $critique;

        return $this;
    }

    /**
     * Remove critique
     *
     * @param \AppBundle\Entity\Critique $critique
     */
    public function removeCritique(\AppBundle\Entity\Critique $critique)
    {
        $this->critiques->removeElement($critique);
    }

    /**
     * Get critiques
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCritiques()
    {
        return $this->critiques;
    }

    public function __toString()
    {
       return $this->getUsername();
    }
}
