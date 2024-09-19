<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista.
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * Class AbstractBaseFixtures.
 */
abstract class AbstractBaseFixtures extends Fixture
{
    /**
     * Faker.
     */
    protected Generator $faker;

    /**
     * Persistence object manager.
     */
    protected ObjectManager $manager;

    /**
     * References index.
     */
    private array $referencesIndex = [];

    /**
     * Load.
     *
     * @param ObjectManager $manager Persistence object manager
     */
    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->faker = Factory::create();
        $this->loadData();
    }

    /**
     * Load data.
     */
    abstract protected function loadData(): void;

    /**
     * Create many objects at once.
     *
     * @param int      $count     Number of objects to create
     * @param string   $groupName Name of the group (for reference storage)
     * @param callable $factory   Factory callable that takes the iteration index as an argument
     */
    protected function createMany(int $count, string $groupName, callable $factory): void
    {
        for ($i = 0; $i < $count; ++$i) {
            $entity = $factory($i);

            if (null === $entity) {
                throw new \LogicException('Did you forget to return the entity object from your callback to AbstractBaseFixtures::createMany()?');
            }

            $this->manager->persist($entity);

            // Store for later usage with references
            $this->addReference(sprintf('%s_%d', $groupName, $i), $entity);
        }
    }

    /**
     * Get a random reference to an entity.
     *
     * @param string $groupName Group name
     *
     * @return object|null Random entity
     */
    protected function getRandomReference(string $groupName): ?object
    {
        if (!isset($this->referencesIndex[$groupName])) {
            $this->referencesIndex[$groupName] = [];

            foreach (array_keys($this->referenceRepository->getReferences()) as $key) {
                if (str_starts_with($key, $groupName.'_')) {
                    $this->referencesIndex[$groupName][] = $key;
                }
            }
        }

        if (empty($this->referencesIndex[$groupName])) {
            throw new \InvalidArgumentException(sprintf('No references found for group "%s"', $groupName));
        }

        $randomReferenceKey = $this->faker->randomElement($this->referencesIndex[$groupName]);

        return $this->getReference($randomReferenceKey);
    }

    /**
     * Get multiple random references to an entity.
     *
     * @param string $groupName Group name
     * @param int    $count     Number of references
     *
     * @return array<array-key, object> Array of entities
     */
    protected function getRandomReferences(string $groupName, int $count): array
    {
        $references = [];
        for ($i = 0; $i < $count; ++$i) {
            $references[] = $this->getRandomReference($groupName);
        }

        return $references;
    }
}
