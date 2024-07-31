<?php
/**
 * Contacts controller.
 */

namespace App\Controller;

use App\Entity\Contacts;
use App\Repository\ContactsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

/**
 * Class ContactsController.
 */
#[Route('/contacts')]
class ContactsController extends AbstractController
{
    /**
     * Index action.
     *
     * @param ContactsRepository $contactsRepository Contacts repository
     * @param PaginatorInterface $paginator          Paginator
     * @param int                $page               Page number
     *
     * @return Response HTTP response
     */
    #[Route(name: 'contacts_index', methods: 'GET')]
    public function index(ContactsRepository $contactsRepository, PaginatorInterface $paginator, #[MapQueryParameter] int $page = 1): Response
    {
        $pagination = $paginator->paginate(
            $contactsRepository->queryAll(),
            $page,
            ContactsRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render('contacts/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Contacts $contact Contact entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'contacts_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(Contacts $contact): Response
    {
        return $this->render(
            'contacts/show.html.twig',
            ['contact' => $contact]
        );
    }
}
