<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista.
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class TagService.
 *
 * Manages operations related to tags, including pagination, saving, deleting, and finding by title.
 */
class TagService implements TagServiceInterface
{
    private const PAGINATOR_ITEMS_PER_PAGE = 5;

    /**
     * Constructor.
     *
     * @param TagRepository      $tagRepository The tag repository
     * @param PaginatorInterface $paginator     The paginator service
     */
    public function __construct(private readonly TagRepository $tagRepository, private readonly PaginatorInterface $paginator)
    {
    }

    /**
     * Get a paginated list of tags.
     *
     * @param int $page The page number
     *
     * @return PaginationInterface The paginated list of tags
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->tagRepository->createQueryBuilder('t'),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save a tag.
     *
     * @param Tag $tag The tag entity to save
     */
    public function save(Tag $tag): void
    {
        $this->tagRepository->save($tag);
    }

    /**
     * Delete a tag.
     *
     * @param Tag $tag The tag entity to delete
     */
    public function delete(Tag $tag): void
    {
        $this->tagRepository->delete($tag);
    }

    /**
     * Find a tag by its title.
     *
     * @param string $title The title of the tag
     *
     * @return Tag|null The tag entity or null if not found
     */
    public function findOneByTitle(string $title): ?Tag
    {
        return $this->tagRepository->findOneBy(['title' => $title]);
    }
}
