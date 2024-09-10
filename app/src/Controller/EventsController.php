<?php
/**
 * Events controller.
 */

namespace App\Controller;

use App\Entity\Events;
use App\Form\Type\EventsType;
use App\Service\EventsServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
* Class EventsController.
*/
#[Route('/events')]
class EventsController extends AbstractController
{
/**
* Constructor.
*
* @param EventsServiceInterface $eventsService Events service
* @param TranslatorInterface    $translator    Translator
*/
public function __construct(
private readonly EventsServiceInterface $eventsService,
private readonly TranslatorInterface $translator
) {
}

/**
* Index action.
*
* @param Request $request HTTP request
*
* @return Response HTTP response
*/
#[Route(name: 'event_index', methods: 'GET')]
public function index(Request $request): Response
{
$page = $request->query->getInt('page', 1);
$user = $this->getUser(); // Get the current authenticated user

$pagination = $this->eventsService->getPaginatedList($page, $user);

return $this->render('events/index.html.twig', ['pagination' => $pagination]);
}

/**
* Show action.
*
* @param Events $event Event entity
*
* @return Response HTTP response
*/
#[Route('/{id}', name: 'event_show', requirements: ['id' => '[1-9]\d*'], methods: 'GET')]
#[IsGranted('VIEW', subject: 'event')]
public function show(Events $event): Response
{
return $this->render('events/show.html.twig', ['event' => $event]);
}

/**
* Create action.
*
* @param Request $request HTTP request
*
* @return Response HTTP response
*/
#[Route('/create', name: 'event_create', methods: ['GET', 'POST'])]
public function create(Request $request): Response
{
/** @var User $user */
$user = $this->getUser();
$event = new Events();
$event->setAuthor($user);

$form = $this->createForm(EventsType::class, $event);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
$this->eventsService->save($event);

$this->addFlash(
'success',
$this->translator->trans('message.created_successfully')
);

return $this->redirectToRoute('event_index');
}

return $this->render('events/create.html.twig', ['form' => $form->createView()]);
}

/**
* Edit action.
*
* @param Request $request HTTP request
* @param Events  $event   Event entity
*
* @return Response HTTP response
*/
#[Route('/{id}/edit', name: 'event_edit', requirements: ['id' => '[1-9]\d*'], methods: ['GET', 'PUT'])]
#[IsGranted('EDIT', subject: 'event')]
public function edit(Request $request, Events $event): Response
{
$form = $this->createForm(
EventsType::class,
$event,
[
'method' => 'PUT',
'action' => $this->generateUrl('event_edit', ['id' => $event->getId()]),
]
);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
$this->eventsService->save($event);

$this->addFlash(
'success',
$this->translator->trans('message.updated_successfully')
);

return $this->redirectToRoute('event_index');
}

return $this->render(
'events/edit.html.twig',
[
'form' => $form->createView(),
'event' => $event,
]
);
}

/**
* Delete action.
*
* @param Request $request HTTP request
* @param Events  $event   Event entity
*
* @return Response HTTP response
*/
#[Route('/{id}/delete', name: 'event_delete', requirements: ['id' => '[1-9]\d*'], methods: ['GET', 'DELETE'])]
#[IsGranted('DELETE', subject: 'event')]
public function delete(Request $request, Events $event): Response
{
$form = $this->createForm(FormType::class, $event, [
'method' => 'DELETE',
'action' => $this->generateUrl('event_delete', ['id' => $event->getId()]),
]);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
$this->eventsService->delete($event);

$this->addFlash(
'success',
$this->translator->trans('message.deleted_successfully')
);

return $this->redirectToRoute('event_index');
}

return $this->render(
'events/delete.html.twig',
[
'form' => $form->createView(),
'event' => $event,
]
);
}
}