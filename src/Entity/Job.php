<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: JobRepository::class)]
class Job
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getPerson"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getPerson"])]
    private ?string $companyName = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getPerson"])]
    private ?string $position = null;

    #[ORM\ManyToOne(inversedBy: 'jobs')]
    private ?Person $person = null;

    #[ORM\Column]
    #[Groups(["getPerson"])]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["getPerson"])]
    private ?\DateTimeImmutable $stopedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): static
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): static
    {
        $this->person = $person;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getStopedAt(): ?\DateTimeImmutable
    {
        return $this->stopedAt;
    }

    public function setStopedAt(?\DateTimeImmutable $stopedAt): static
    {
        $this->stopedAt = $stopedAt;

        return $this;
    }
}
