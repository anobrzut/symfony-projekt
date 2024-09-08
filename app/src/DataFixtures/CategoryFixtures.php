<?php
/**
 * Category fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;

/**
 * Class CategoryFixtures.
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     */
    protected function loadData(): void
    {
        $this->createMany(10, 'categories', function (int $i) {
            $category = new Category();
            $category->setTitle($this->faker->word);

            $createdAt = new \DateTimeImmutable($this->faker->dateTimeBetween('-100 days', '-1 days')->format('Y-m-d H:i:s'));
            $updatedAt = new \DateTimeImmutable($this->faker->dateTimeBetween($createdAt->format('Y-m-d H:i:s'), 'now')->format('Y-m-d H:i:s'));

            $category->setCreatedAt($createdAt);
            $category->setUpdatedAt($updatedAt);

            return $category;
        });

        $this->manager->flush();
    }
}
