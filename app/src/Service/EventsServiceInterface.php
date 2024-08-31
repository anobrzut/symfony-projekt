<?php
/**
 * Events service interface.
 */

namespace App\Service;

use App\Entity\Events;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface EventsServiceInterface.
 */
interface EventsServiceInterface
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
     * @param Events $events Events entity
     */
    public function save(Events $events): void;

    /**
     * Delete entity.
     *
     * @param Events $events Events entity
     */
    public function delete(Events $events): void;
}
