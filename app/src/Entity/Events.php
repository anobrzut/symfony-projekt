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
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Title of the event.
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $title = null;

    /**
     * The date and time when the event was created.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * The date and time when the event was last updated.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * Description of the event, optional.
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /**
     * The date when the event is scheduled to occur.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $date = null;

    /**
     * The category the event belongs to.
     */
    #[ORM\ManyToOne(targetEntity: Category::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Category $category = null;

    /**
     * Tags associated with the event.
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    #[ORM\JoinTable(name: 'events_tags')]
    private Collection $tags;

    /**
     * The author (user) who created the event.
     */
    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Type(User::class)]
    private ?User $author = null;

    /**
     * Constructor to initialize the tags collection.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * Getter for id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for the event title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for the event title.
     *
     * @param string $title Title
     *
     * @return static Current Events entity
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Getter for the creation date.
     *
     * @return \DateTimeInterface|null Creation date
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Setter for the creation date.
     *
     * @param \DateTimeInterface $createdAt Creation date
     *
     * @return static Current Events entity
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Getter for the last update date.
     *
     * @return \DateTimeInterface|null Last update date
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Setter for the last update date.
     *
     * @param \DateTimeInterface $updatedAt Update date
     *
     * @return static Current Events entity
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Getter for the event description.
     *
     * @return string|null Description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Setter for the event description.
     *
     * @param string|null $description Description
     *
     * @return static Current Events entity
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Getter for the event date.
     *
     * @return \DateTimeInterface|null Event date
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Setter for the event date.
     *
     * @param \DateTimeInterface $date Event date
     *
     * @return static Current Events entity
     */
    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Getter for the event's category.
     *
     * @return Category|null Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for the event's category.
     *
     * @param Category|null $category Category
     *
     * @return static Current Events entity
     */
    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Getter for tags associated with the event.
     *
     * @return Collection Tags
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Adds a tag to the event.
     *
     * @param Tag $tag Tag to add
     *
     * @return static Current Events entity
     */
    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    /**
     * Removes a tag from the event.
     *
     * @param Tag $tag Tag to remove
     *
     * @return static Current Events entity
     */
    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * Getter for the author (creator) of the event.
     *
     * @return User|null Author
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Setter for the author (creator) of the event.
     *
     * @param User|null $author Author
     *
     * @return static Current Events entity
     */
    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }
}
