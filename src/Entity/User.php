<?php

namespace App\Entity;

use App\Enum\UserStatusenum;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Cette adresse mail est déja utilisée pour un autre compte, veuillez vous connecter !')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['remove'])]
    private ?InfosUser $infosUser = null;

    /**
     * @var Collection<int, Books>
     */
    #[ORM\OneToMany(targetEntity: Books::class, mappedBy: 'user')]
    private Collection $books;

    /**
     * @var Collection<int, Books>
     */
    #[ORM\ManyToMany(targetEntity: Books::class, inversedBy: 'users')]
    #[ORM\JoinTable(name: 'favorite')]
    private Collection $favorites;

    /**
     * @var Collection<int, Conversation>
     */
    #[ORM\ManyToMany(targetEntity: Conversation::class, mappedBy: 'participants')]
    private Collection $conversations;

    /**
     * @var Collection<int, Exchange>
     */
    #[ORM\OneToMany(targetEntity: Exchange::class, mappedBy: 'userRequester', orphanRemoval: true)]
    private Collection $exchangeRequest;

    /**
     * @var Collection<int, Exchange>
     */
    #[ORM\OneToMany(targetEntity: Exchange::class, mappedBy: 'userReceiver', orphanRemoval: true)]
    private Collection $exchangeReceive;

    #[ORM\Column(enumType: UserStatusenum::class)]
    private ?UserStatusenum $status = null;

    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->exchangeRequest = new ArrayCollection();
        $this->exchangeReceive = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getInfosUser(): ?InfosUser
    {
        return $this->infosUser;
    }

    public function setInfosUser(InfosUser $infosUser): static
    {
        // set the owning side of the relation if necessary
        if ($infosUser->getUser() !== $this) {
            $infosUser->setUser($this);
        }

        $this->infosUser = $infosUser;

        return $this;
    }

    /**
     * @return Collection<int, Books>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Books $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setUser($this);
        }

        return $this;
    }

    public function removeBook(Books $book): static
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getUser() === $this) {
                $book->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Books>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Books $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
        }

        return $this;
    }

    public function removeFavorite(Books $favorite): static
    {
        $this->favorites->removeElement($favorite);

        return $this;
    }
    public function toggleFavorite(Books $books): void
    {
        if ($this->favorites->contains($books)) {
            $this->removeFavorite($books);
        } else {
            $this->addFavorite($books);
        }
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): static
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations->add($conversation);
            $conversation->addParticipant($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): static
    {
        if ($this->conversations->removeElement($conversation)) {
            $conversation->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Exchange>
     */
    public function getExchangeRequest(): Collection
    {
        return $this->exchangeRequest;
    }

    public function addExchangeRequest(Exchange $exchangeRequest): static
    {
        if (!$this->exchangeRequest->contains($exchangeRequest)) {
            $this->exchangeRequest->add($exchangeRequest);
            $exchangeRequest->setUserRequester($this);
        }

        return $this;
    }

    public function removeExchangeRequest(Exchange $exchangeRequest): static
    {
        if ($this->exchangeRequest->removeElement($exchangeRequest)) {
            // set the owning side to null (unless already changed)
            if ($exchangeRequest->getUserRequester() === $this) {
                $exchangeRequest->setUserRequester(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Exchange>
     */
    public function getExchangeReceive(): Collection
    {
        return $this->exchangeReceive;
    }

    public function addExchangeReceive(Exchange $exchangeReceive): static
    {
        if (!$this->exchangeReceive->contains($exchangeReceive)) {
            $this->exchangeReceive->add($exchangeReceive);
            $exchangeReceive->setUserReceiver($this);
        }

        return $this;
    }

    public function removeExchangeReceive(Exchange $exchangeReceive): static
    {
        if ($this->exchangeReceive->removeElement($exchangeReceive)) {
            // set the owning side to null (unless already changed)
            if ($exchangeReceive->getUserReceiver() === $this) {
                $exchangeReceive->setUserReceiver(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?UserStatusenum
    {
        return $this->status;
    }

    public function setStatus(UserStatusenum $status): static
    {
        $this->status = $status;

        return $this;
    }
}
