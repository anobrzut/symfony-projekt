<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista.
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Service\TagServiceInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class TagsDataTransformer.
 *
 * This class transforms a collection of Tag entities into a comma-separated string and vice versa.
 */
class TagsDataTransformer implements DataTransformerInterface
{
    /**
     * Constructor.
     *
     * @param TagServiceInterface $tagService Service used to handle tag operations
     */
    public function __construct(private readonly TagServiceInterface $tagService)
    {
    }

    /**
     * Transforms a collection of Tag entities into a comma-separated string of tag titles.
     *
     * @param Collection|Tag[] $value Collection of Tag entities
     *
     * @return string A comma-separated string of tag titles
     */
    public function transform($value): string
    {
        if ($value->isEmpty()) {
            return '';
        }

        $tagTitles = [];

        foreach ($value as $tag) {
            $tagTitles[] = $tag->getTitle();
        }

        return implode(', ', $tagTitles);
    }

    /**
     * Transforms a comma-separated string of tag titles into an array of Tag entities.
     *
     * @param string $value A comma-separated string of tag titles
     *
     * @return Tag[] An array of Tag entities
     */
    public function reverseTransform($value): array
    {
        $tagTitles = explode(',', $value);
        $tags = [];

        foreach ($tagTitles as $tagTitle) {
            if ('' !== trim($tagTitle)) {
                $tag = $this->tagService->findOneByTitle(trim(strtolower($tagTitle)));
                if (!$tag instanceof Tag) {
                    $tag = new Tag();
                    $tag->setTitle(trim($tagTitle));

                    $this->tagService->save($tag);
                }
                $tags[] = $tag;
            }
        }

        return $tags;
    }
}
