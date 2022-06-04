<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Following
 *
 * @ORM\Table(name="following", indexes={@ORM\Index(name="fk_following_users", columns={"user"}), @ORM\Index(name="fk_followed", columns={"followed"})})
 * @ORM\Entity
 */
class Following
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="followed", referencedColumnName="id")
     * })
     */
    private $followed;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFollowed(): ?Users
    {
        return $this->followed;
    }

    public function setFollowed(?Users $followed): self
    {
        $this->followed = $followed;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }


}
