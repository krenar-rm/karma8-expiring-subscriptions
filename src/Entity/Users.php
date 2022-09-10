<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 */
class Users
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;


    /**
     * @ORM\Column(type="boolean")
     */
    private $confirmed;

    /**
     * @ORM\OneToOne(targetEntity=UsersSubscription::class, mappedBy="user_id", cascade={"persist", "remove"})
     */
    private $usersSubscription;

    /**
     * @ORM\OneToOne(targetEntity=Emails::class, inversedBy="email", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $email;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function isConfirmed(): ?bool
    {
        return $this->confirmed;
    }

    public function setConfirmed(bool $confirmed): self
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    public function getUsersSubscription(): ?UsersSubscription
    {
        return $this->usersSubscription;
    }

    public function setUsersSubscription(UsersSubscription $usersSubscription): self
    {
        // set the owning side of the relation if necessary
        if ($usersSubscription->getUser() !== $this) {
            $usersSubscription->setUser($this);
        }

        $this->usersSubscription = $usersSubscription;

        return $this;
    }

    public function getEmail(): ?Emails
    {
        return $this->email;
    }

    public function setEmail(Emails $email): self
    {
        $this->email = $email;

        return $this;
    }
}
