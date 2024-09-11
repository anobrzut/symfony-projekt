<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\Events;
use App\Entity\User;
use App\Entity\Tag;
use App\Repository\CategoryRepository;
use App\Repository\EventsRepository;
use App\Repository\TagRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class EventsService.
 *
 * Manages operations related to events, including pagination, filtering, saving, and deleting.
 */
class EventsService implements EventsServiceInterface
{
    private const PAGINATOR_ITEMS_PER_PAGE = 5;

    /**
     * Constructor.
     *
     * @param EventsRepository     $eventsRepository    The events repository
     * @param PaginatorInterface   $paginator           The paginator service
     * @param CategoryRepository   $categoryRepository  The category repository
     * @param TagRepository        $tagRepository       The tag repository
     */
    public function __construct(
        private readonly EventsRepository $eventsRepository,
        private readonly PaginatorInterface $paginator,
        private readonly CategoryRepository $categoryRepository,
        private readonly TagRepository $tagRepository
    ) {
    }

    /**
     * Get paginated list of events.
     *
     * @param int       $page           The page number
     * @param User      $user           The user entity
     * @param Category|null $category   The category filter (optional)
     * @param bool      $hidePastEvents Whether to hide past events
     * @param array     $tags           Array of tags for filtering
     *
     * @return PaginationInterface The paginated list of events
     */
    public function getPaginatedList(int $page, User $user, ?Category $category = null, bool $hidePastEvents = false, array $tags = []): PaginationInterface
    {
        $queryBuilder = $this->eventsRepository->queryByUser($user);

        if ($category) {
            $queryBuilder->andWhere('events.category = :category')
                ->setParameter('category', $category);
        }

        if ($hidePastEvents) {
            $queryBuilder->andWhere('events.date >= :today')
                ->setParameter('today', new \DateTimeImmutable('today'));
        }

        if ($tags) {
            $queryBuilder->join('events.tags', 't')
                ->andWhere('t IN (:tags)')
                ->setParameter('tags', $tags);
        }

        return $this->paginator->paginate($queryBuilder, $page, self::PAGINATOR_ITEMS_PER_PAGE);
    }

    /**
     * Get categories for filter.
     *
     * @return array<string, Category> The array of categories indexed by title
     */
    public function getCategoriesForFilter(): array
    {
        $categories = $this->categoryRepository->findAll();
        $choices = [];

        foreach ($categories as $category) {
            $choices[$category->getTitle()] = $category;
        }

        return $choices;
    }

    /**
     * Get tags for filter.
     *
     * @return array<string, Tag> The array of tags indexed by title
     */
    public function getTagsForFilter(): array
    {
        $tags = $this->tagRepository->findAll();
        $choices = [];

        foreach ($tags as $tag) {
            $choices[$tag->getTitle()] = $tag;
        }

        return $choices;
    }

    /**
     * Find a category by its ID.
     *
     * @param int $categoryId The category ID
     *
     * @return Category|null The category entity or null if not found
     */
    public function findCategoryById(int $categoryId): ?Category
    {
        return $this->categoryRepository->find($categoryId);
    }

    /**
     * Find tags by their IDs.
     *
     * @param array<int> $tagIds The array of tag IDs
     *
     * @return array<Tag> The array of tag entities
     */
    public function findTagsByIds(array $tagIds): array
    {
        return $this->tagRepository->findBy(['id' => $tagIds]);
    }

    /**
     * Save an event.
     *
     * @param Events $events The event entity to save
     */
    public function save(Events $events): void
    {
        $this->eventsRepository->save($events);
    }

    /**
     * Delete an event.
     *
     * @param Events $events The event entity to delete
     */
    public function delete(Events $events): void
    {
        $this->eventsRepository->delete($events);
    }
}
