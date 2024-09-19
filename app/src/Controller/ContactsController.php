<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista.
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\Controller;

use App\Entity\Contacts;
use App\Form\Type\ContactsType;
use App\Service\ContactsServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ContactsController.
 *
 * Handles CRUD operations for contacts.
 */
#[\Symfony\Component\Routing\Attribute\Route('/contacts')]
class ContactsController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param ContactsServiceInterface $contactsService The contacts service
     */
    public function __construct(private readonly ContactsServiceInterface $contactsService)
    {
    }

    /**
     * Display a list of contacts for the logged-in user.
     *
     * @param Request $request The current request
     *
     * @return Response The response with the contacts list
     */
    #[\Symfony\Component\Routing\Attribute\Route(name: 'contacts_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);

        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to view your contacts.');
        }

        $pagination = $this->contactsService->getPaginatedList($page, $user);

        return $this->render('contacts/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show a specific contact.
     *
     * @param Contacts $contact The contact entity
     *
     * @return Response The response with the contact details
     */
    #[\Symfony\Component\Routing\Attribute\Route('/{id}', name: 'contacts_show', requirements: ['id' => '[1-9]\d*'], methods: 'GET')]
    public function show(Contacts $contact): Response
    {
        return $this->render('contacts/show.html.twig', ['contact' => $contact]);
    }

    /**
     * Create a new contact.
     *
     * @param Request $request The current request
     *
     * @return Response The response for the contact creation form
     */
    #[\Symfony\Component\Routing\Attribute\Route('/create', name: 'contacts_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to create a contact.');
        }

        $contact = new Contacts();
        $form = $this->createForm(ContactsType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setAuthor($user);
            $this->contactsService->save($contact);

            $this->addFlash('success', 'Contact created successfully.');

            return $this->redirectToRoute('contacts_index');
        }

        return $this->render('contacts/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit an existing contact.
     *
     * @param Request  $request The current request
     * @param Contacts $contact The contact entity to edit
     *
     * @return Response The response for the contact edit form
     */
    #[\Symfony\Component\Routing\Attribute\Route('/{id}/edit', name: 'contacts_edit', requirements: ['id' => '[1-9]\d*'], methods: ['GET', 'PUT'])]
    public function edit(Request $request, Contacts $contact): Response
    {
        $form = $this->createForm(ContactsType::class, $contact, [
            'method' => 'PUT',
            'action' => $this->generateUrl('contacts_edit', ['id' => $contact->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactsService->save($contact);

            $this->addFlash('success', 'Contact updated successfully.');

            return $this->redirectToRoute('contacts_index');
        }

        return $this->render('contacts/edit.html.twig', [
            'form' => $form->createView(),
            'contact' => $contact,
        ]);
    }

    /**
     * Delete a contact.
     *
     * @param Request  $request The current request
     * @param Contacts $contact The contact entity to delete
     *
     * @return Response The response for the contact delete form
     */
    #[\Symfony\Component\Routing\Attribute\Route('/{id}/delete', name: 'contacts_delete', requirements: ['id' => '[1-9]\d*'], methods: ['GET', 'DELETE'])]
    public function delete(Request $request, Contacts $contact): Response
    {
        $form = $this->createForm(FormType::class, $contact, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('contacts_delete', ['id' => $contact->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactsService->delete($contact);

            $this->addFlash('success', 'Contact deleted successfully.');

            return $this->redirectToRoute('contacts_index');
        }

        return $this->render('contacts/delete.html.twig', [
            'form' => $form->createView(),
            'contact' => $contact,
        ]);
    }
}
