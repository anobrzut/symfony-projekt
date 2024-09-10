<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Events;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\EventsRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class EventsService.
 */
class EventsService implements EventsServiceInterface
{
    private const PAGINATOR_ITEMS_PER_PAGE = 5;

    public function __construct(
        private readonly EventsRepository $eventsRepository,
        private readonly PaginatorInterface $paginator,
        private readonly CategoryRepository $categoryRepository
    ) {
    }

    public function getPaginatedList(int $page, User $user, ?Category $category = null, bool $hidePastEvents = false): PaginationInterface
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

        return $this->paginator->paginate($queryBuilder, $page, self::PAGINATOR_ITEMS_PER_PAGE);
    }

    public function getCategoriesForFilter(): array
    {
        $categories = $this->categoryRepository->findAll();
        $choices = [];

        foreach ($categories as $category) {
            $choices[$category->getTitle()] = $category;
        }

        return $choices;
    }

    public function findCategoryById(int $categoryId): ?Category
    {
        return $this->categoryRepository->find($categoryId);
    }

    public function save(Events $events): void
    {
        $this->eventsRepository->save($events);
    }

    public function delete(Events $events): void
    {
        $this->eventsRepository->delete($events);
    }
}
