<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $birthDate = null;

    #[ORM\Column(length: 8)]
    private ?string $membershipNumber = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBirthDate(): ?\DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeImmutable $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getMembershipNumber(): ?string
    {
        return $this->membershipNumber;
    }

    public function setMembershipNumber(string $membershipNumber): self
    {
        $this->membershipNumber = $membershipNumber;

        return $this;
    }
}
