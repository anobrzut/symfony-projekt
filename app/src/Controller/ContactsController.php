<?php
/**
 * Contacts controller.
 */

namespace App\Controller;

use App\Entity\Contacts;
use App\Form\Type\ContactsType;
use App\Service\ContactsServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContactsController.
 */
#[Route('/contacts')]
class ContactsController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param ContactsServiceInterface $contactsService Contacts service
     * @param TranslatorInterface      $translator      Translator
     */
    public function __construct(
        private readonly ContactsServiceInterface $contactsService,
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * Index action.
     *
     * @param int $page Page number
     *
     * @return Response HTTP response
     */
    #[Route(name: 'contacts_index', methods: 'GET')]
    public function index(#[MapQueryParameter] int $page = 1): Response
    {
        $pagination = $this->contactsService->getPaginatedList($page);

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
        methods: 'GET'
    )]
    public function show(Contacts $contact): Response
    {
        return $this->render('contacts/show.html.twig', ['contact' => $contact]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'contacts_create',
        methods: ['GET', 'POST'],
    )]
    public function create(Request $request): Response
    {
        $contact = new Contacts();
        $form = $this->createForm(ContactsType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactsService->save($contact);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('contacts_index');
        }

        return $this->render(
            'contacts/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request  $request HTTP request
     * @param Contacts $contact Contact entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'contacts_edit', requirements: ['id' => '[1-9]\d*'], methods: ['GET', 'PUT'])]
    public function edit(Request $request, Contacts $contact): Response
    {
        $form = $this->createForm(
            ContactsType::class,
            $contact,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('contacts_edit', ['id' => $contact->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactsService->save($contact);

            $this->addFlash(
                'success',
                $this->translator->trans('message.updated_successfully')
            );

            return $this->redirectToRoute('contacts_index');
        }

        return $this->render(
            'contacts/edit.html.twig',
            [
                'form' => $form->createView(),
                'contact' => $contact,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request  $request HTTP request
     * @param Contacts $contact Contact entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'contacts_delete', requirements: ['id' => '[1-9]\d*'], methods: ['GET', 'DELETE'])]
    public function delete(Request $request, Contacts $contact): Response
    {
        $form = $this->createForm(FormType::class, $contact, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('contacts_delete', ['id' => $contact->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactsService->delete($contact);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('contacts_index');
        }

        return $this->render(
            'contacts/delete.html.twig',
            [
                'form' => $form->createView(),
                'contact' => $contact,
            ]
        );
    }
}
