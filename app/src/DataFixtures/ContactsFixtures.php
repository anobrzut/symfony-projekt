<?php

namespace App\DataFixtures;

use App\Entity\Contacts;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class ContactsFixtures.
 */
class ContactsFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
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
        return (int) $this->faker->numberBetween(100000000, 999999999);
    }
}
