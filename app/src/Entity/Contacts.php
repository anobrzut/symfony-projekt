<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\Entity;

use App\Repository\ContactsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Contacts.
 *
 * Represents a contact entity with name, phone, description, associated tags, and timestamps for creation and updates.
 */
#[ORM\Entity(repositoryClass: ContactsRepository::class)]
class Contacts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * Tags associated with the contact.
     *
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    #[ORM\JoinTable(name: 'contacts_tags')]
    private Collection $tags;

    /**
     * Constructor.
     *
     * Initializes the tags collection.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * Get the contact ID.
     *
     * @return int|null The contact ID
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the phone number of the contact.
     *
     * @return string|null The phone number
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Set the phone number of the contact.
     *
     * @param string $phone The phone number
     *
     * @return static
     */
    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the name of the contact.
     *
     * @return string|null The contact name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the name of the contact.
     *
     * @param string $name The contact name
     *
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the description of the contact.
     *
     * @return string|null The contact description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the description of the contact.
     *
     * @param string|null $description The contact description
     *
     * @return static
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the creation timestamp.
     *
     * @return \DateTimeInterface|null The creation timestamp
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Get the last update timestamp.
     *
     * @return \DateTimeInterface|null The last update timestamp
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Get the author of the contact.
     *
     * @return User|null The author of the contact
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Set the author of the contact.
     *
     * @param User|null $author The author of the contact
     *
     * @return static
     */
    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get the tags associated with the contact.
     *
     * @return Collection<int, Tag> The tags associated with the contact
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add a tag to the contact.
     *
     * @param Tag $tag The tag to add
     *
     * @return static
     */
    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    /**
     * Remove a tag from the contact.
     *
     * @param Tag $tag The tag to remove
     *
     * @return static
     */
    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
