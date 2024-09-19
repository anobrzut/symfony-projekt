<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista.
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\Service;

use App\Entity\Contacts;
use App\Entity\Tag;
use App\Entity\User;
use App\Repository\ContactsRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ContactsService.
 *
 * Provides methods to manage contacts, including pagination, saving, deleting, and managing tags.
 */
class ContactsService implements ContactsServiceInterface
{
    private const PAGINATOR_ITEMS_PER_PAGE = 5;

    /**
     * Constructor.
     *
     * @param ContactsRepository  $contactsRepository The contacts repository
     * @param PaginatorInterface  $paginator          The paginator service
     * @param TagServiceInterface $tagService         The tag service
     */
    public function __construct(private readonly ContactsRepository $contactsRepository, private readonly PaginatorInterface $paginator, private readonly TagServiceInterface $tagService)
    {
    }

    /**
     * Get paginated list of contacts for a specific user.
     *
     * @param int  $page Page number
     * @param User $user User entity
     *
     * @return PaginationInterface Pagination result
     */
    public function getPaginatedList(int $page, User $user): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->contactsRepository->queryByUser($user),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save a contact.
     *
     * @param Contacts $contacts Contacts entity
     */
    public function save(Contacts $contacts): void
    {
        $this->contactsRepository->save($contacts);
    }

    /**
     * Delete a contact.
     *
     * @param Contacts $contacts Contacts entity
     */
    public function delete(Contacts $contacts): void
    {
        $this->contactsRepository->delete($contacts);
    }

    /**
     * Add tags to a contact.
     *
     * @param Contacts           $contacts Contacts entity
     * @param array<int, string> $tags     Array of tag titles
     */
    public function addTags(Contacts $contacts, array $tags): void
    {
        foreach ($tags as $tagTitle) {
            $tag = $this->tagService->findOneByTitle($tagTitle);

            if (!$tag instanceof Tag) {
                $tag = new Tag();
                $tag->setTitle($tagTitle);
                $this->tagService->save($tag);
            }

            $contacts->addTag($tag);
        }

        $this->contactsRepository->save($contacts);
    }

    /**
     * Remove tags from a contact.
     *
     * @param Contacts           $contacts Contacts entity
     * @param array<int, string> $tags     Array of tag titles
     */
    public function removeTags(Contacts $contacts, array $tags): void
    {
        foreach ($tags as $tagTitle) {
            $tag = $this->tagService->findOneByTitle($tagTitle);

            if ($tag instanceof Tag) {
                $contacts->removeTag($tag);
            }
        }

        $this->contactsRepository->save($contacts);
    }
}
