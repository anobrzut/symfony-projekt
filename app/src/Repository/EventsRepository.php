<?php
// src/Repository/EventsRepository.php

namespace App\Repository;

use App\Entity\Events;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
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
    public const PAGINATOR_ITEMS_PER_PAGE = 5;

    public function __construct(ManagerRegistry $registry)
    {
    parent::__construct($registry, Events::class);
    }

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
    $qb = $this->getOrCreateQueryBuilder()
    ->andWhere('events.author = :user')
    ->setParameter('user', $user)
    ->orderBy('events.id', 'ASC');

    return $qb;
    }

    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
    return $queryBuilder ?? $this->createQueryBuilder('events');
    }

    public function save(Events $event): void
    {
    $entityManager = $this->getEntityManager();
    $entityManager->persist($event);
    $entityManager->flush();
    }

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
    }
