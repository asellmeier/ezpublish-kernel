<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace eZ\Publish\Core\QueryType\BuildIn;

use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\MatchNone;

final class SiblingsQueryType extends AbstractLocationQueryType
{
    public static function getName(): string
    {
        return 'eZ:Siblings';
    }

    protected function getQueryFilter(array $parameters): Criterion
    {
        $location = $this->resolveLocation($parameters);

        if ($location === null) {
            return new MatchNone();
        }

        return Criterion\Sibling::fromLocation($location);
    }
}
