<?php

// src/Service/EventsService.php

namespace App\Service;

use App\Entity\Events;
use App\Entity\User;
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
        private readonly PaginatorInterface $paginator
    ) {
    }

    public function getPaginatedList(int $page, User $user): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->eventsRepository->queryByUser($user),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
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
