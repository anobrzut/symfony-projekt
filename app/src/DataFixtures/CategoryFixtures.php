<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\DataFixtures;

use App\Entity\Category;
use DateTimeImmutable;
use Exception;

/**
 * Class CategoryFixtures.
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * This method creates 10 category entities with random titles and timestamps.
     */
    protected function loadData(): void
    {
        $this->createMany(10, 'categories', function (int $i) {
            $category = new Category();
            $category->setTitle($this->faker->word);

            try {
                $createdAt = DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'));
                $updatedAt = DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween($createdAt, 'now'));
            } catch (Exception $e) {
                $createdAt = new DateTimeImmutable();
                $updatedAt = new DateTimeImmutable();
            }

            $category->setCreatedAt($createdAt);
            $category->setUpdatedAt($updatedAt);

            return $category;
        });

        $this->manager->flush();
    }
}
