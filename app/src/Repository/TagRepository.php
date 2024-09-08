<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function findOneByTitle(string $title): ?Tag
    {
        return $this->findOneBy(['title' => $title]);
    }

    public function save(Tag $tag): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($tag);
        $entityManager->flush();
    }

    public function delete(Tag $tag): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($tag);
        $entityManager->flush();
    }
}
