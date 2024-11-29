<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'event')]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire.')]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: 'Le nom doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $name = null;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'Le lieu est obligatoire.')]
    private ?string $location = null;

    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank(message: 'La longitude est obligatoire.')]
    private ?float $longitude = null;

    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank(message: 'La latitude est obligatoire.')]
    private ?float $latitude = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Participant::class, cascade: ['persist', 'remove'])]
    private Collection $participants;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: 'La date est obligatoire.')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }

    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getLocation(): ?string { return $this->location; }

    public function setLocation(string $location): self { $this->location = $location; return $this; }

    public function getLongitude(): ?float { return $this->longitude; }

    public function setLongitude(float $longitude): self { $this->longitude = $longitude; return $this; }

    public function getLatitude(): ?float { return $this->latitude; }

    public function setLatitude(float $latitude): self { $this->latitude = $latitude; return $this; }

    public function getDate(): ?\DateTimeInterface { return $this->date; }

    public function setDate(\DateTimeInterface $date): self { $this->date = $date; return $this; }

    public function getParticipants(): Collection { return $this->participants; }

    public function addParticipant(Participant $participant): self {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setEvent($this);
        }
        return $this;
    }

    public function removeParticipant(Participant $participant): self {
        if ($this->participants->removeElement($participant)) {
            if ($participant->getEvent() === $this) {
                $participant->setEvent(null);
            }
        }
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->created_at; }

    public function setCreatedAt(\DateTimeImmutable $created_at): self { $this->created_at = $created_at; return $this; }

    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updated_at; }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self { $this->updated_at = $updated_at; return $this; }
}
