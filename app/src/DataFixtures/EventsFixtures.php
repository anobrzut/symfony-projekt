<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista.
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\DataFixtures;

use App\Entity\Events;
use App\Entity\Category;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class EventsFixtures.
 *
 * This class is responsible for loading event data fixtures, which include creating events with associated categories, tags, and authors.
 */
class EventsFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Returns the dependencies for this fixture.
     *
     * This fixture depends on the CategoryFixtures, TagFixtures, and UserFixtures to ensure that events can reference valid categories, tags, and authors.
     *
     * @return array The list of dependent fixture classes
     */
    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            TagFixtures::class,
            UserFixtures::class,
        ];
    }

    /**
     * Loads the event data into the database.
     *
     * This method creates 10 event entities with random titles, descriptions, categories, tags, and authors.
     */
    protected function loadData(): void
    {
        $this->createMany(10, 'events', function (int $i) {
            $event = new Events();
            $event->setTitle($this->faker->sentence);
            $event->setDescription($this->faker->sentence);
            $event->setDate($this->faker->dateTimeBetween('now', '+100 days'));
            $event->setCreatedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
            $event->setUpdatedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));

            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $event->setCategory($category);

            /** @var array<array-key, Tag> $tags */
            $tags = $this->getRandomReferences('tags', $this->faker->numberBetween(0, 5));
            foreach ($tags as $tag) {
                $event->addTag($tag);
            }

            /** @var User $author */
            $author = $this->getRandomReference('users');
            $event->setAuthor($author);

            return $event;
        });

        $this->manager->flush();
    }
}
