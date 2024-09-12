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

            return $category;
        });

        $this->manager->flush();
    }
}
