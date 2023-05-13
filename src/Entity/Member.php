<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[UniqueEntity('membershipNumber')]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $birthDate = null;

    #[ORM\Column(length: 8, unique: true)]
    private ?string $membershipNumber = null;

    #[ORM\ManyToOne(inversedBy: 'members')]
    private ?Article $article = null;

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

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }
}
