<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'participant')]
class Participant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire.')]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le nom doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $name = null;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'L\'email est obligatoire.')]
    #[Assert\Email(message: 'Veuillez fournir une adresse email valide.')]
    private ?string $email = null;

    #[ORM\ManyToOne(targetEntity: Event::class, inversedBy: 'participants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank(message: 'La latitude est obligatoire.')]
    private ?float $latitude = null;

    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank(message: 'La longitude est obligatoire.')]
    private ?float $longitude = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }

    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getEmail(): ?string { return $this->email; }

    public function setEmail(string $email): self { $this->email = $email; return $this; }

    public function getLongitude(): ?float { return $this->longitude; }

    public function setLongitude(float $longitude): self { $this->longitude = $longitude; return $this; }

    public function getLatitude(): ?float { return $this->latitude; }

    public function setLatitude(float $latitude): self { $this->latitude = $latitude; return $this; }

    public function getEvent(): ?Event { return $this->event; }

    public function setEvent(?Event $event): self { $this->event = $event; return $this; }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->created_at; }

    public function setCreatedAt(\DateTimeImmutable $created_at): self { $this->created_at = $created_at; return $this; }

    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updated_at; }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self { $this->updated_at = $updated_at; return $this; }
}
