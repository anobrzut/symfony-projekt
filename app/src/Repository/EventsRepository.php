<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista.
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\Repository;

use App\Entity\Events;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class EventsRepository.
 *
 * @method Events|null find($id, $lockMode = null, $lockVersion = null)
 * @method Events|null findOneBy(array $criteria, array $orderBy = null)
 * @method Events[]    findAll()
 * @method Events[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<Events>
 */
class EventsRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Events::class);
    }

    /**
     * Query all events.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('events.id', 'ASC');
    }

    /**
     * Query events by user.
     *
     * @param User $user User entity
     *
     * @return QueryBuilder Query builder
     */
    public function queryByUser(User $user): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->andWhere('events.author = :user')
            ->setParameter('user', $user)
            ->orderBy('events.id', 'ASC');
    }

    /**
     * Save an event entity.
     *
     * @param Events $event The event entity to save
     */
    public function save(Events $event): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($event);
        $entityManager->flush();
    }

    /**
     * Delete an event entity.
     *
     * @param Events $event The event entity to delete
     */
    public function delete(Events $event): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($event);
        $entityManager->flush();
    }

    /**
     * Count events by category.
     *
     * @param Category $category Category
     *
     * @return int Number of events in category
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByCategory(Category $category): int
    {
        $qb = $this->getOrCreateQueryBuilder()
            ->select('COUNT(DISTINCT events.id)')
            ->where('events.category = :category')
            ->setParameter('category', $category);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(?QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('events');
    }
}
