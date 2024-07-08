<?php

namespace App\Entity;

use App\Enum\StateEnum;
use App\Repository\BooksRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BooksRepository::class)]
class Books
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"ce champs est obligatoire")]
    #[Assert\Length(min:2, max:50, maxMessage:"le titre ne devrait aps passer 50 caracteres", minMessage:"le titre doit avoir au moins deux caracteres")]

    private ?string $title = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"ce champs est obligatoire")]
    #[Assert\Length(min:2, max:50, maxMessage:"le nom de l'auteur ne devrait aps passer 50 caracteres", minMessage:"le nom de l'auteur doit avoir au moins deux caracteres")]
    private ?string $author = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message:"ce champs est obligatoire")]
    #[Assert\Length(min:2, max:20, maxMessage:"l'ISBN ne devrait aps passer 20 caracteres", minMessage:"l'ISBN doit avoir au moins deux caracteres")]
    private ?string $isbn = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"ce champs est obligatoire")]
    #[Assert\Length(min:1, max:500, maxMessage:"La discreption doit avoir entre 20 et 500 caracteres", minMessage:"La discreption doit avoir entre 100 et 500 caracteres")]
    private ?string $description = null;

    #[ORM\Column(length: 40)]
    private ?string $edition = null;

    #[ORM\Column]
    private ?bool $isFavorite = null;

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

    public function isFavorite(): ?bool
    {
        return $this->isFavorite;
    }

    public function setFavorite(bool $isFavorite): static
    {
        $this->isFavorite = $isFavorite;

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
}
