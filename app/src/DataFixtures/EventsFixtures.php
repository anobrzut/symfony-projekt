<?php
/**
 * Events fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Events;
use App\Entity\Category;
use App\Entity\Tag;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class EventsFixtures.
 */
class EventsFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    protected function loadData(): void
    {
        $this->createMany(10, 'events', function (int $i) {
            $event = new Events();
            $event->setTitle($this->faker->sentence);
            $event->setDescription($this->faker->sentence);
            $event->setDate($this->faker->dateTimeBetween('now', '+100 days'));
            $event->setCreatedAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
            $event->setUpdatedAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));

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

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            TagFixtures::class,
            UserFixtures::class,
        ];
    }
}
