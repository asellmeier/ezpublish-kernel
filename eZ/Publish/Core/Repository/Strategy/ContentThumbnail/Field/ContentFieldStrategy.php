<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace eZ\Publish\Core\Repository\Strategy\ContentThumbnail\Field;

use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\API\Repository\Values\Content\Thumbnail;
use eZ\Publish\Core\Base\Exceptions\NotFoundException;
use eZ\Publish\SPI\Repository\Strategy\ContentThumbnail\Field\ThumbnailStrategy;
use Traversable;

final class ContentFieldStrategy implements ThumbnailStrategy
{
    /** @var \eZ\Publish\SPI\Repository\Strategy\ContentThumbnail\Field\ThumbnailStrategy[][] */
    private $strategies = [];

    /**
     * @param \eZ\Publish\SPI\Repository\Strategy\ContentThumbnail\Field\ThumbnailStrategy[]|Traversable $strategies
     */
    public function __construct(Traversable $strategies)
    {
        foreach ($strategies as $strategy) {
            $fieldTypeIdentifier = $strategy->getFieldTypeIdentifier();

            // Ignore non-direct fieldtype related strategies (ie. higher level factories or chain strategies like this one)
            if ($fieldTypeIdentifier !== null) {
                $this->addStrategy($fieldTypeIdentifier, $strategy);
            }
        }
    }

    public function getFieldTypeIdentifier(): ?string
    {
        return null;
    }

    /**
     * @throws \eZ\Publish\Core\Base\Exceptions\NotFoundException
     */
    public function getThumbnail(Field $field): ?Thumbnail
    {
        if (!$this->hasStrategy($field->fieldTypeIdentifier)) {
            throw new NotFoundException('Field\ThumbnailStrategy', $field->fieldTypeIdentifier);
        }

        $fieldStrategies = $this->strategies[$field->fieldTypeIdentifier];

        foreach ($fieldStrategies as $fieldStrategy) {
            $thumbnail = $fieldStrategy->getThumbnail($field);

            if ($thumbnail !== null) {
                return $thumbnail;
            }
        }

        return null;
    }

    public function hasStrategy(string $fieldTypeIdentifier): bool
    {
        return !empty($this->strategies[$fieldTypeIdentifier]);
    }

    public function addStrategy(string $fieldTypeIdentifier, ThumbnailStrategy $thumbnailStrategy): void
    {
        $this->strategies[$fieldTypeIdentifier][] = $thumbnailStrategy;
    }

    public function setStrategies(array $thumbnailStrategies): void
    {
        foreach ($thumbnailStrategies as $fieldTypeIdentifier => $thumbnailStrategy) {
            $this->addStrategy($fieldTypeIdentifier, $thumbnailStrategy);
        }
    }
}
