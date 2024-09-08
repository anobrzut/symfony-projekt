<?php
/**
 * Contacts service.
 */

namespace App\Service;

use App\Entity\Contacts;
use App\Repository\ContactsRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;

/**
 * Class ContactsService.
 */
class ContactsService implements ContactsServiceInterface
{
    private const PAGINATOR_ITEMS_PER_PAGE = 5;

    public function __construct(
        private readonly ContactsRepository $contactsRepository,
        private readonly PaginatorInterface $paginator
    ) {
    }

    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->contactsRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function save(Contacts $contacts): void
    {
        $this->contactsRepository->save($contacts);
    }

    public function delete(Contacts $contacts): void
    {
        $this->contactsRepository->delete($contacts);
    }
}
