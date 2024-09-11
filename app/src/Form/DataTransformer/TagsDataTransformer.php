<?php

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Service\TagServiceInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class TagsDataTransformer.
 */
class TagsDataTransformer implements DataTransformerInterface
{
    public function __construct(private readonly TagServiceInterface $tagService)
    {
    }

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

    public function reverseTransform($value): array
    {
        $tagTitles = explode(',', $value);
        $tags = [];

        foreach ($tagTitles as $tagTitle) {
            if ('' !== trim($tagTitle)) {
                $tag = $this->tagService->findOneByTitle(trim(strtolower($tagTitle)));
                if (null === $tag) {
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
