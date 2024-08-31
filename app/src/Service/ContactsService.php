<?php
/**
 * Contacts service.
 */

namespace App\Service;

use App\Entity\Contacts;
use App\Repository\ContactsRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;

/**
 * Class ContactsService.
 */
class ContactsService implements ContactsServiceInterface
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 5;

    /**
     * Constructor.
     *
     * @param ContactsRepository $contactsRepository Contacts repository
     * @param PaginatorInterface $paginator          Paginator
     */
    public function __construct(
        private readonly ContactsRepository $contactsRepository,
        private readonly PaginatorInterface $paginator
    ) {
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->contactsRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Contacts $contacts Contacts entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Contacts $contacts): void
    {
        if (null == $contacts->getId()) {
            $contacts->setCreatedAt(new \DateTimeImmutable());
        }
        $contacts->setUpdatedAt(new \DateTimeImmutable());

        $this->contactsRepository->save($contacts);
    }

    /**
     * Delete entity.
     *
     * @param Contacts $contacts Contacts entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Contacts $contacts): void
    {
        $this->contactsRepository->delete($contacts);
    }
}
