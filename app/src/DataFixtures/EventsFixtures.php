<?php
/**
 * Events fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Events;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class EventsFixtures.
 */
class EventsFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     */
    protected function loadData(): void
    {
        $categories = $this->manager->getRepository(Category::class)->findAll();

        for ($i = 0; $i < 10; ++$i) {
            $event = new Events();
            $event->setTitle($this->faker->word);
            $event->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $event->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $event->setDescription($this->faker->sentence);
            $event->setDate($this->faker->dateTimeBetween('now', '+100 days'));
            $event->setCategory($this->faker->randomElement($categories));
            $this->manager->persist($event);
        }

        $this->manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
