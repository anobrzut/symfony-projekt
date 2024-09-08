<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;

/**
 * Class TagFixtures.
 */
class TagFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     */
    protected function loadData(): void
    {
        $this->createMany(10, 'tags', function (int $i) {
            $tag = new Tag();
            $tag->setTitle($this->faker->unique()->word);

            return $tag;
        });

        $this->manager->flush();
    }
}
