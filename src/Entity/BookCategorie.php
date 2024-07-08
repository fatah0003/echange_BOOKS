<?php

namespace App\Entity;

use App\Repository\BookCategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookCategorieRepository::class)]
class BookCategorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    /**
     * @var Collection<int, Books>
     */
    #[ORM\OneToMany(targetEntity: Books::class, mappedBy: 'bookCategorie')]
    private Collection $booksId;

    public function __construct()
    {
        $this->booksId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Books>
     */
    public function getBooksId(): Collection
    {
        return $this->booksId;
    }

    public function addBooksId(Books $booksId): static
    {
        if (!$this->booksId->contains($booksId)) {
            $this->booksId->add($booksId);
            $booksId->setBookCategorie($this);
        }

        return $this;
    }

    public function removeBooksId(Books $booksId): static
    {
        if ($this->booksId->removeElement($booksId)) {
            // set the owning side to null (unless already changed)
            if ($booksId->getBookCategorie() === $this) {
                $booksId->setBookCategorie(null);
            }
        }

        return $this;
    }
}
