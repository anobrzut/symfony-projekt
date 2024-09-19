<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista.
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\Service;

use App\Entity\Events;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface EventsServiceInterface.
 */
interface EventsServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int  $page Page number
     * @param User $user User entity
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page, User $user): PaginationInterface;

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
