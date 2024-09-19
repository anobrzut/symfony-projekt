<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista.
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\Service;

use App\Entity\Tag;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface TagServiceInterface.
 *
 * Provides methods for managing tags, including pagination, saving, deleting, and finding tags by title.
 */
interface TagServiceInterface
{
    /**
     * Get paginated list of tags.
     *
     * @param int $page The page number
     *
     * @return PaginationInterface The paginated list of tags
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Save a tag.
     *
     * @param Tag $tag The tag entity to save
     */
    public function save(Tag $tag): void;

    /**
     * Delete a tag.
     *
     * @param Tag $tag The tag entity to delete
     */
    public function delete(Tag $tag): void;

    /**
     * Find a tag by its title.
     *
     * @param string $title The title of the tag
     *
     * @return Tag|null The found tag entity or null if not found
     */
    public function findOneByTitle(string $title): ?Tag;
}
