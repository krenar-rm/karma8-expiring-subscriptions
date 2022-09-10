<?php

namespace App\Entity;

use App\Repository\UsersSubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Schema\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass=UsersSubscriptionRepository::class)
 */

/**
 * @ORM\Entity
 * @ORM\Table(
 *      uniqueConstraints={@ORM\UniqueConstraint(columns={"validts"})}
 * )
 *
 * @ORM\Entity(repositoryClass=UsersSubscriptionRepository::class)
 */
class UsersSubscription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Users::class, inversedBy="usersSubscription", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $validts;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getValidts(): ?int
    {
        return $this->validts;
    }

    public function setValidts(?int $validts): self
    {
        $this->validts = $validts;

        return $this;
    }
}
