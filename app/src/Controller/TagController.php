<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista.
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\Controller;

use App\Entity\Tag;
use App\Form\Type\TagType;
use App\Service\TagServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class TagController.
 *
 * Handles CRUD operations for tags.
 */
#[\Symfony\Component\Routing\Attribute\Route('/tag')]
#[IsGranted('ROLE_ADMIN')]
class TagController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param TagServiceInterface $tagService The tag service
     * @param TranslatorInterface $translator The translator service
     */
    public function __construct(private readonly TagServiceInterface $tagService, private readonly TranslatorInterface $translator)
    {
    }

    /**
     * Displays a paginated list of tags.
     *
     * @param Request $request The current request
     *
     * @return Response The response containing the tag list
     */
    #[\Symfony\Component\Routing\Attribute\Route(name: 'tag_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->tagService->getPaginatedList($page);

        return $this->render('tag/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Creates a new tag.
     *
     * @param Request $request The current request
     *
     * @return Response The response for the tag creation form
     */
    #[\Symfony\Component\Routing\Attribute\Route('/create', name: 'tag_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->save($tag);

            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a tag.
     *
     * @param Tag $tag The tag entity
     *
     * @return Response The response showing the tag details
     */
    #[\Symfony\Component\Routing\Attribute\Route('/{id}', name: 'tag_show', requirements: ['id' => '\d+'], methods: 'GET')]
    public function show(Tag $tag): Response
    {
        return $this->render('tag/show.html.twig', ['tag' => $tag]);
    }

    /**
     * Edits an existing tag.
     *
     * @param Request $request The current request
     * @param Tag     $tag     The tag entity
     *
     * @return Response The response for the tag edit form
     */
    #[\Symfony\Component\Routing\Attribute\Route('/{id}/edit', name: 'tag_edit', requirements: ['id' => '\d+'], methods: ['GET', 'PUT'])]
    public function edit(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(TagType::class, $tag, [
            'method' => 'PUT',
            'action' => $this->generateUrl('tag_edit', ['id' => $tag->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->save($tag);

            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/edit.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag,
        ]);
    }

    /**
     * Deletes a tag.
     *
     * @param Request $request The current request
     * @param Tag     $tag     The tag entity
     *
     * @return Response The response for the tag deletion form
     */
    #[\Symfony\Component\Routing\Attribute\Route('/{id}/delete', name: 'tag_delete', requirements: ['id' => '\d+'], methods: ['GET', 'DELETE'])]
    public function delete(Request $request, Tag $tag): Response
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('tag_delete', ['id' => $tag->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->delete($tag);

            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/delete.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag,
        ]);
    }
}
