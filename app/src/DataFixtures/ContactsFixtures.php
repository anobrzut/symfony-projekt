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
    /**
     * Load data.
     */
    protected function loadData(): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $contact = new Contacts();
            $contact->setName($this->faker->name);
            $contact->setPhone($this->faker->phoneNumber);
            $contact->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $contact->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $contact->setDescription($this->faker->sentence);
            $this->manager->persist($contact);
        }

        $this->manager->flush();
    }
}
