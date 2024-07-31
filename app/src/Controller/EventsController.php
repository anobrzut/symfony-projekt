<?php
/**
 * Events controller.
 */

namespace App\Controller;

use App\Entity\Events;
use App\Repository\EventsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

/**
 * Class EventsController.
 */
#[Route('/events')]
class EventsController extends AbstractController
{
    /**
     * Index action.
     *
     * @param EventsRepository   $eventsRepository   Events repository
     * @param PaginatorInterface $paginator          Paginator
     * @param int                $page               Page number
     *
     * @return Response HTTP response
     */
    #[Route(name: 'event_index', methods: 'GET')]
    public function index(EventsRepository $eventsRepository, PaginatorInterface $paginator, #[MapQueryParameter] int $page = 1): Response
    {
        $pagination = $paginator->paginate(
            $eventsRepository->queryAll(),
            $page,
            EventsRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render('events/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Events $event Event entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'event_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(Events $event): Response
    {
        return $this->render(
            'events/show.html.twig',
            ['event' => $event]
        );
    }
}
