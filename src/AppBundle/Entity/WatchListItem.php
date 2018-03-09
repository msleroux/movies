<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WatchListItem
 *
 * @ORM\Table(name="watch_list_item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WatchListItemRepository")
 */
class WatchListItem
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateAdded", type="datetime")
     */
    private $dateAdded;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="watchItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Movie
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Movie", inversedBy="watchItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $movie;


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
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     *
     * @return WatchListItem
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return WatchListItem
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

    /**
     * Set movie
     *
     * @param \AppBundle\Entity\Movie $movie
     *
     * @return WatchListItem
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


    public function __construct($user,$movie)
    {
        $this->setUser($user);
        $this->setMovie($movie);
        $this->setDateAdded( new \DateTime());
    }
}
