<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista.
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\EventsRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CategoryService.
 *
 * Manages operations related to categories, including pagination, saving, deleting, and checking if a category can be deleted.
 */
class CategoryService implements CategoryServiceInterface
{
    private const PAGINATOR_ITEMS_PER_PAGE = 5;

    private CategoryRepository $categoryRepository;
    private EventsRepository $eventsRepository;
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param CategoryRepository $categoryRepository The category repository
     * @param EventsRepository   $eventsRepository   The events repository
     * @param PaginatorInterface $paginator          The paginator service
     */
    public function __construct(CategoryRepository $categoryRepository, EventsRepository $eventsRepository, PaginatorInterface $paginator)
    {
        $this->categoryRepository = $categoryRepository;
        $this->eventsRepository = $eventsRepository;
        $this->paginator = $paginator;
    }

    /**
     * Get paginated list of categories.
     *
     * @param int $page The page number
     *
     * @return PaginationInterface The paginated list of categories
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->categoryRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save a category.
     *
     * @param Category $category The category entity to save
     */
    public function save(Category $category): void
    {
        $this->categoryRepository->save($category);
    }

    /**
     * Delete a category.
     *
     * @param Category $category The category entity to delete
     */
    public function delete(Category $category): void
    {
        $this->categoryRepository->delete($category);
    }

    /**
     * Can the category be deleted?
     *
     * Checks if the category can be deleted by ensuring no events are associated with it.
     *
     * @param Category $category The category entity
     *
     * @return bool True if the category can be deleted, false otherwise
     *
     * @throws NoResultException        If no result is found when counting events
     * @throws NonUniqueResultException If more than one result is found when counting events
     */
    public function canBeDeleted(Category $category): bool
    {
        try {
            $result = $this->eventsRepository->countByCategory($category);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException $e) {
            return false;
        }
    }
}
