<?php

namespace App\Service;

use App\Entity\Contacts;
use App\Entity\User;
use App\Repository\ContactsRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class ContactsService implements ContactsServiceInterface
{
    private const PAGINATOR_ITEMS_PER_PAGE = 5;

    public function __construct(
        private readonly ContactsRepository $contactsRepository,
        private readonly PaginatorInterface $paginator
    ) {
    }

    /**
     * Get paginated list of contacts for a specific user.
     *
     * @param int $page Page number
     * @param User $user User entity
     *
     * @return PaginationInterface Pagination result
     */
    public function getPaginatedList(int $page, User $user): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->contactsRepository->queryByUser($user),
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
