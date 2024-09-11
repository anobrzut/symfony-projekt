<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\ChangePasswordType;
use App\Form\Type\UserType;
use App\Service\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class UserController.
 */
#[Route('/user')]
class UserController extends AbstractController
{
    private UserServiceInterface $userService;
    private TranslatorInterface $translator;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserServiceInterface $userService, TranslatorInterface $translator, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userService = $userService;
        $this->translator = $translator;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Change password action for regular users.
     */
    #[Route('/change-password', name: 'user_change_password')]
    public function changePassword(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        if (!$user instanceof UserInterface || !$user instanceof \Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface) {
            $this->addFlash('danger', $this->translator->trans('message.user_not_authenticated'));
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentPassword = $form->get('currentPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();

            if (!$this->passwordHasher->isPasswordValid($user, $currentPassword)) {
                $this->addFlash('danger', $this->translator->trans('message.invalid_current_password'));
            } else {
                $hashedPassword = $this->passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
                $doctrine->getManager()->flush();

                $this->addFlash('success', $this->translator->trans('message.password_changed_successfully'));
                return $this->redirectToRoute('app_logout');
            }
        }

        return $this->render('user/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Change password for a specific user (Admin only).
     */
    #[Route('/admin/{id}/change-password', name: 'user_change_password_admin', requirements: ['id' => '[1-9]\d*'], methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function changePasswordAdmin(Request $request, User $user, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('newPassword')->getData();
            $hashedPassword = $this->passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashedPassword);
            $doctrine->getManager()->flush();

            $this->addFlash('success', $this->translator->trans('message.password_changed_successfully'));

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/change_password_admin.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * User list for admins.
     */
    #[Route('/admin', name: 'user_index', methods: 'GET')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->userService->getPaginatedList($page);

        return $this->render('user/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show user details for admins.
     */
    #[Route('/admin/{id}', name: 'user_show', requirements: ['id' => '[1-9]\d*'], methods: 'GET')]
    #[IsGranted('ROLE_ADMIN')]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * Edit an existing user (Admin only).
     */
    #[Route('/admin/{id}/edit', name: 'user_edit', requirements: ['id' => '[1-9]\d*'], methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user, [
            'is_edit' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);

            $this->addFlash('success', $this->translator->trans('message.user_updated_successfully'));

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * Delete a user (Admin only).
     */
    #[Route('/admin/{id}/delete', name: 'user_delete', requirements: ['id' => '[1-9]\d*'], methods: ['GET', 'DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->userService->delete($user);
            $this->addFlash('success', $this->translator->trans('message.user_deleted_successfully'));
        }

        return $this->redirectToRoute('user_index');
    }
}
