<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\Type\TagType;
use App\Service\TagServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class TagController.
 */
#[Route('/tag')]
#[IsGranted('ROLE_ADMIN')]
class TagController extends AbstractController
{
    public function __construct(
        private readonly TagServiceInterface $tagService,
        private readonly TranslatorInterface $translator
    ) {}

    #[Route(name: 'tag_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->tagService->getPaginatedList($page);

        return $this->render('tag/index.html.twig', ['pagination' => $pagination]);
    }

    #[Route('/create', name: 'tag_create', methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'tag_show', requirements: ['id' => '\d+'], methods: 'GET')]
    public function show(Tag $tag): Response
    {
        return $this->render('tag/show.html.twig', ['tag' => $tag]);
    }

    #[Route('/{id}/edit', name: 'tag_edit', requirements: ['id' => '\d+'], methods: ['GET', 'PUT'])]
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

    #[Route('/{id}/delete', name: 'tag_delete', requirements: ['id' => '\d+'], methods: ['GET', 'DELETE'])]
    public function delete(Request $request, Tag $tag): Response
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('tag_delete', ['id' => $tag->getId()]))
            ->setMethod('DELETE')
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
