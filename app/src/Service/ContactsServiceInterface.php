<?php

namespace App\Service;

use App\Entity\Contacts;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ContactsServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     * @param User $user User entity
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedList(int $page, User $user): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Contacts $contacts Contacts entity
     */
    public function save(Contacts $contacts): void;

    /**
     * Delete entity.
     *
     * @param Contacts $contacts Contacts entity
     */
    public function delete(Contacts $contacts): void;
}
