<?php

namespace App\Repository;

use App\Entity\Contacts;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class ContactsRepository extends ServiceEntityRepository
{
    public const PAGINATOR_ITEMS_PER_PAGE = 5;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contacts::class);
    }

    /**
     * Query contacts by user.
     *
     * @param User $user User entity
     *
     * @return QueryBuilder Query builder
     */
    public function queryByUser(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('contacts')
            ->andWhere('contacts.author = :user')
            ->setParameter('user', $user)
            ->orderBy('contacts.updatedAt', 'DESC');
    }

    /**
     * Save entity.
     *
     * @param Contacts $contact Contacts entity
     */
    public function save(Contacts $contact): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($contact);
        $entityManager->flush();
    }

    /**
     * Delete entity.
     *
     * @param Contacts $contact Contacts entity
     */
    public function delete(Contacts $contact): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($contact);
        $entityManager->flush();
    }
}
