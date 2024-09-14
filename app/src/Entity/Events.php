<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista.
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\Entity;

use App\Repository\EventsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Events.
 *
 * Represents an event with title, date, description, category, tags, and an author.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: EventsRepository::class)]
#[ORM\Table(name: 'events')]
class Events
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: Category::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Category $category = null;

    /**
     * Tags associated with the event.
     *
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    #[ORM\JoinTable(name: 'events_tags')]
    private Collection $tags;

    /**
     * Author of the event.
     */
    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Type(User::class)]
    private ?User $author = null;

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
     * Get the event ID.
     *
     * @return int|null The event ID
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the title of the event.
     *
     * @return string|null The title of the event
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the title of the event.
     *
     * @param string $title The title of the event
     *
     * @return static
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the creation date of the event.
     *
     * @return \DateTimeInterface|null The creation date
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set the creation date of the event.
     *
     * @param \DateTimeInterface $createdAt The creation date
     *
     * @return static
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the last update date of the event.
     *
     * @return \DateTimeInterface|null The last update date
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set the last update date of the event.
     *
     * @param \DateTimeInterface $updatedAt The last update date
     *
     * @return static
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get the description of the event.
     *
     * @return string|null The description of the event
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the description of the event.
     *
     * @param string|null $description The description of the event
     *
     * @return static
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the date of the event.
     *
     * @return \DateTimeInterface|null The event date
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Set the date of the event.
     *
     * @param \DateTimeInterface $date The event date
     *
     * @return static
     */
    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the category of the event.
     *
     * @return Category|null The event category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Set the category of the event.
     *
     * @param Category|null $category The event category
     *
     * @return static
     */
    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the tags associated with the event.
     *
     * @return Collection<int, Tag> The event tags
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add a tag to the event.
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
     * Remove a tag from the event.
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

    /**
     * Get the author of the event.
     *
     * @return User|null The event author
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Set the author of the event.
     *
     * @param User|null $author The event author
     *
     * @return static
     */
    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }
}
