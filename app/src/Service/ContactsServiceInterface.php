<?php

namespace App\Service;

use App\Entity\Contacts;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ContactsServiceInterface
{
    /**
     * Get paginated list of contacts for a specific user.
     *
     * @param int $page Page number
     * @param User $user User entity
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedList(int $page, User $user): PaginationInterface;

    /**
     * Save a contact.
     *
     * @param Contacts $contacts Contacts entity
     */
    public function save(Contacts $contacts): void;

    /**
     * Delete a contact.
     *
     * @param Contacts $contacts Contacts entity
     */
    public function delete(Contacts $contacts): void;

    /**
     * Add tags to a contact.
     *
     * @param Contacts $contacts Contacts entity
     * @param array<int, string> $tags Array of tag titles
     */
    public function addTags(Contacts $contacts, array $tags): void;

    /**
     * Remove tags from a contact.
     *
     * @param Contacts $contacts Contacts entity
     * @param array<int, string> $tags Array of tag titles
     */
    public function removeTags(Contacts $contacts, array $tags): void;
}
