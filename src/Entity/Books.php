<?php

namespace App\Entity;

use App\Enum\ExchangeTypeEnum;
use App\Enum\StateEnum;
use App\Repository\BooksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Serializable;

#[ORM\Entity(repositoryClass: BooksRepository::class)]
#[Vich\Uploadable()]
class Books implements Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"ce champs est obligatoire")]
    #[Assert\Length(
        min:2,
        max:50,
        maxMessage:"le titre ne devrait aps passer 50 caracteres",
        minMessage:"le titre doit avoir au moins deux caracteres"
    )]

    private ?string $title = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"ce champs est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 50,
        maxMessage: "Le nom de l'auteur ne devrait pas dépasser 50 caractères",
        minMessage: "Le nom de l'auteur doit avoir au moins deux caractères"
    )]
    private ?string $author = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message:"ce champs est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 20,
        maxMessage: "L'ISBN ne devrait pas dépasser 20 caractères",
        minMessage: "L'ISBN doit avoir au moins deux caractères"
    )]
    private ?string $isbn = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"ce champs est obligatoire")]
    #[Assert\Length(
        min: 20,
        max: 500,
        maxMessage: "La description doit avoir entre 20 et 500 caractères",
        minMessage: "La description doit avoir au moins 20 caractères"
    )]

    private ?string $description = null;

    #[ORM\Column(length: 40)]
    private ?string $edition = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 50)]
    private ?string $location = null;

    #[ORM\ManyToOne(inversedBy: 'booksId')]
    #[ORM\JoinColumn(nullable: false)]
    private ?BookCategorie $bookCategorie = null;

    #[ORM\Column]
    private ?int $pages = null;

    #[ORM\Column(enumType: StateEnum::class)]
    private ?StateEnum $state = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, enumType: ExchangeTypeEnum::class)]
    private array $exchangeType = [];

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'favorites')]
    private Collection $users;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Image $back = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Image $cover = null;
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEdition(): ?string
    {
        return $this->edition;
    }

    public function setEdition(string $edition): static
    {
        $this->edition = $edition;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getBookCategorie(): ?BookCategorie
    {
        return $this->bookCategorie;
    }

    public function setBookCategorie(?BookCategorie $bookCategorie): static
    {
        $this->bookCategorie = $bookCategorie;

        return $this;
    }

    public function getPages(): ?int
    {
        return $this->pages;
    }

    public function setPages(int $pages): static
    {
        $this->pages = $pages;

        return $this;
    }

    public function getState(): ?StateEnum
    {
        return $this->state;
    }

    public function setState(StateEnum $state): static
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return ExchangeTypeEnum[]
     */
    public function getExchangeType(): array
    {
        return $this->exchangeType;
    }

    public function setExchangeType(array $exchangeType): static
    {
        $this->exchangeType = $exchangeType;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addFavorite($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeFavorite($this);
        }

        return $this;
    }

    public function getBack(): ?Image
    {
        return $this->back;
    }

    public function setBack(Image $back): static
    {
        $this->back = $back;

        return $this;
    }

    public function getCover(): ?Image
    {
        return $this->cover;
    }

    public function setCover(Image $cover): static
    {
        $this->cover = $cover;

        return $this;
    }

    public function serialize()
    {
        return serialize([
            $this->id
        ]);
    }

    public function unserialize(string $serialized)
    {
        list(
            $this->id
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
        ];
    }

    public function __unserialize(array $serialized): void
    {
        $this->id = $serialized['id'];
    }
}
