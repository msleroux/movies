<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Critique
 *
 * @ORM\Table(name="critique")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CritiqueRepository")
 */
class Critique
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
     * @ORM\Column(name="title", type="string", length=50)
     */
    private $title;




    /**
     * @var string
     *
     * @Assert\NotBlank(message="Veuillez renseigner votre commentaire !")
     * @Assert\Length(
     *      min=5,
     *      max=255,
     *      minMessage="5 caractères minimum SVP !",
     *      maxMessage="255 caractères max SVP !"
     * )
     *
     * @ORM\Column(name="content", type="text", length=255)
     */
    private $content;

    /**
     * @var Movie
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Movie", inversedBy="critiques")
     * @ORM\JoinColumn(nullable=false)
     */
    private $movie;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="critiques")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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
     * Set title
     *
     * @param string $title
     *
     * @return Critique
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Critique
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set movie
     *
     * @param \AppBundle\Entity\Movie $movie
     *
     * @return Critique
     */
    public function setMovie(\AppBundle\Entity\Movie $movie)
    {
        $this->movie = $movie;

        return $this;
    }

    /**
     * Get movie
     *
     * @return \AppBundle\Entity\Movie
     */
    public function getMovie()
    {
        return $this->movie;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Critique
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
