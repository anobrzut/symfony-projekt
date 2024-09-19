<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista.
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 */
class TagRepository extends ServiceEntityRepository
{
    /**
     * TagRepository constructor.
     *
     * @param ManagerRegistry $registry the registry to use for entity management
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * Find a Tag entity by its title.
     *
     * @param string $title the title to search for
     *
     * @return Tag|null the Tag entity or null if not found
     */
    public function findOneByTitle(string $title): ?Tag
    {
        return $this->findOneBy(['title' => $title]);
    }

    /**
     * Save a Tag entity to the database.
     *
     * @param Tag $tag the Tag entity to save
     */
    public function save(Tag $tag): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($tag);
        $entityManager->flush();
    }

    /**
     * Delete a Tag entity from the database.
     *
     * @param Tag $tag the Tag entity to delete
     */
    public function delete(Tag $tag): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($tag);
        $entityManager->flush();
    }
}
