<?php

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Class TagService.
 */
class TagService implements TagServiceInterface
{
    private const PAGINATOR_ITEMS_PER_PAGE = 5;

    public function __construct(
        private readonly TagRepository $tagRepository,
        private readonly PaginatorInterface $paginator
    ) {}

    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->tagRepository->createQueryBuilder('t'),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    public function save(Tag $tag): void
    {
        $this->tagRepository->save($tag);
    }

    public function delete(Tag $tag): void
    {
        $this->tagRepository->delete($tag);
    }

    public function findOneByTitle(string $title): ?Tag
    {
        return $this->tagRepository->findOneBy(['title' => $title]);
    }
}
