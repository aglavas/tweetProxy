<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * Tweet
 *
 * @ORM\Table(name="tweets", indexes={@ORM\Index(columns={"text"}, flags={"fulltext"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TweetRepository")
 */
class Tweet
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
     * @ORM\Column(name="tweeter_id", type="string", length=50)
     */
    private $tweeterId;


    // ...
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="tweets")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;
    // ...


    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;


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
     * Set tweeterId
     *
     * @param string $tweeterId
     *
     * @return Tweet
     */
    public function setTweeterId($tweeterId)
    {
        $this->tweeterId = $tweeterId;

        return $this;
    }

    /**
     * Get tweeterId
     *
     * @return string
     */
    public function getTweeterId()
    {
        return $this->tweeterId;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Tweet
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}
