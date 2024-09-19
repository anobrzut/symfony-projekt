<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista.
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class RegistrationService.
 *
 * Handles user registration, including password hashing and setting default roles.
 */
class RegistrationService implements RegistrationServiceInterface
{
    /**
     * Constructor.
     *
     * @param EntityManagerInterface      $entityManager  The entity manager
     * @param UserPasswordHasherInterface $passwordHasher The password hasher
     */
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    /**
     * Registers a new user.
     *
     * Hashes the user's password, sets default roles, and persists the user entity.
     *
     * @param User $user The user entity to register
     */
    public function registerUser(User $user): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->getPlainPassword()
        );

        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
