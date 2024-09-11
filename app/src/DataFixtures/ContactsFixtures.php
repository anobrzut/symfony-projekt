<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\DataFixtures;

use App\Entity\Contacts;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class ContactsFixtures.
 *
 * This class is responsible for loading contact data fixtures, associating each contact with a user.
 */
class ContactsFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Loads contact data into the database.
     *
     * This method creates 10 contact entities with random names, phone numbers, descriptions, and assigns a random user as the author.
     */
    protected function loadData(): void
    {
        $this->createMany(10, 'contacts', function () {
            $contact = new Contacts();
            $contact->setName($this->faker->name);
            $contact->setPhone($this->generateNineDigitPhoneNumber());
            $contact->setDescription($this->faker->sentence);

            /** @var User $user */
            $user = $this->getRandomReference('users');
            $contact->setAuthor($user);

            return $contact;
        });

        $this->manager->flush();
    }

    /**
     * Returns the dependencies for this fixture.
     *
     * This fixture depends on the UserFixtures to ensure that contacts can reference valid users.
     *
     * @return array The list of dependent fixture classes
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    /**
     * Generate a 9-digit phone number.
     *
     * @return int 9-digit phone number
     */
    private function generateNineDigitPhoneNumber(): int
    {
        return $this->faker->numberBetween(100000000, 999999999);
    }
}
