<?php
/**
 * Contacts service interface.
 */

namespace App\Service;

use App\Entity\Contacts;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface ContactsServiceInterface.
 */
interface ContactsServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

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
