<?php
/**
 * Contacts fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Contacts;
use Doctrine\Persistence\ObjectManager;

/**
 * Class ContactsFixtures.
 */
class ContactsFixtures extends AbstractBaseFixtures
{
    protected function loadData(): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $contact = new Contacts();
            $contact->setName($this->faker->name);
            $contact->setPhone($this->generateNineDigitPhoneNumber());
            $contact->setDescription($this->faker->sentence);
            $this->manager->persist($contact);
        }

        $this->manager->flush();
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
