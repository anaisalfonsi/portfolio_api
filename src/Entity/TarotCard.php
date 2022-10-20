<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\ApiCreateTarotImagesController;
use App\Repository\TarotCardRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: TarotCardRepository::class)]
#[ApiResource (
    types: ['http://localhost:8000/TarotCard'],
    operations: [
        new Get(),
        new GetCollection(),
        new Patch(),
        new Delete(),
        new Post(
            inputFormats: ['multipart' => ['multipart/form-data']],
            controller: ApiCreateTarotImagesController::class
        )
    ],
    normalizationContext: ['groups' => ['tarot:read']],
    denormalizationContext: ['groups' => ['tarot:write']]
)]
class TarotCard
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('tarot:read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tarot:read', 'tarot:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tarot:read', 'tarot:write'])]
    private ?string $number = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['tarot:read', 'tarot:write'])]
    private ?string $description = null;

    #[ApiProperty(types: ['http://localhost:8000/contentUrl'])]
    #[Groups('tarot:read')]
    public ?string $contentUrl = null;

    #[Vich\UploadableField(mapping: "tarot_image", fileNameProperty: "filePath")]
    #[Groups(['tarot:read', 'tarot:write'])]
    public ?File $file = null;

    #[ORM\Column(nullable: true)]
    #[Groups('tarot:read')]
    public ?string $filePath = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
