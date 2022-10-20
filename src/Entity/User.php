<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\ApiCreateUserImagesController;
use App\Repository\UserRepository;
use App\State\UserProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Validator\Constraints as CustomConstraints;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Post(
            uriTemplate: '/users',
            name: 'user',
            processor: UserProcessor::class
        ),
        new Delete(),
        new GetCollection(),
        new Post(
            uriTemplate: '/users/{id}/images',
            inputFormats: ['multipart' => ['multipart/form-data']],
            controller: ApiCreateUserImagesController::class
        ),
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['languages' => 'end'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Groups(['read', 'write'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups('read')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Length(
        min: 6,
        max: 200,
        minMessage: 'Your password must be at least {{ limit }} characters long',
        maxMessage: 'Your password cannot be longer than {{ limit }} characters',
    )]
    #[CustomConstraints\PasswordBlackList]
    private ?string $password = null;


    #[Assert\Length(
        min: 6,
        max: 30,
        minMessage: 'Your password must be at least {{ limit }} characters long',
        maxMessage: 'Your password cannot be longer than {{ limit }} characters',
    )]
    #[Assert\Regex(
        pattern: '/[a-z0-9]+/i',
        message: 'Your password must only contain letters and numbers',
        match: true,
    )]
    #[CustomConstraints\PasswordBlackList]
    #[SerializedName("password")]
    #[Groups('write')]
    private ?string $plainPassword = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/[a-z0-9]+/i',
        message: 'Your pseudo must only contain letters and numbers',
        match: true,
    )]
    #[Assert\Length(
        min: 4,
        max: 30,
        minMessage: 'Your pseudo must be at least {{ limit }} characters long',
        maxMessage: 'Your pseudo cannot be longer than {{ limit }} characters',
    )]
    #[Groups(['read', 'write'])]
    private ?string $pseudo = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Image::class, cascade: ['persist'], orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\Count(
        max: 20,
        maxMessage: 'You cannot add more than {{ limit }} images',
    )]
    #[Groups('read')]
    private Collection $images;

    #[ORM\ManyToMany(targetEntity: Language::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['read', 'write'])]
    private Collection $languages;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
        $this->images = new ArrayCollection();
        $this->languages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setUser($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getUser() === $this) {
                $image->setUser(null);
            }
        }

        return $this;
    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->email = $data['email'];
    }

    /**
     * @return Collection<int, Language>
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    public function addLanguage(Language $language): self
    {
        if (!$this->languages->contains($language)) {
            $this->languages->add($language);
        }

        return $this;
    }

    public function removeLanguage(Language $language): self
    {
        $this->languages->removeElement($language);

        return $this;
    }
}
