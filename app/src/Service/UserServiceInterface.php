<?php

namespace App\Service;

use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface UserServiceInterface.
 */
interface UserServiceInterface
{
    /**
     * Get a paginated list of users.
     *
     * @param int $page The current page number
     *
     * @return PaginationInterface Paginated users
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Save a user entity.
     *
     * @param User $user The user entity to save
     *
     * @return void
     */
    public function save(User $user): void;

    /**
     * Delete a user entity.
     *
     * @param User $user The user entity to delete
     *
     * @return void
     */
    public function delete(User $user): void;
}
