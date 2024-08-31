<?php
/**
 * Events service.
 */

namespace App\Service;

use App\Entity\Events;
use App\Repository\EventsRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;

/**
 * Class EventsService.
 */
class EventsService implements EventsServiceInterface
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 5;

    /**
     * Constructor.
     *
     * @param EventsRepository   $eventsRepository Events repository
     * @param PaginatorInterface $paginator        Paginator
     */
    public function __construct(
        private readonly EventsRepository $eventsRepository,
        private readonly PaginatorInterface $paginator
    ) {
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->eventsRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Events $events Events entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Events $events): void
    {
        if (null == $events->getId()) {
            $events->setCreatedAt(new \DateTimeImmutable());
        }
        $events->setUpdatedAt(new \DateTimeImmutable());

        $this->eventsRepository->save($events);
    }

    /**
     * Delete entity.
     *
     * @param Events $events Events entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Events $events): void
    {
        $this->eventsRepository->delete($events);
    }
}
