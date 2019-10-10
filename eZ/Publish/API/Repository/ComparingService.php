<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace eZ\Publish\API\Repository;

use eZ\Publish\API\Repository\Values\Content\VersionDiff\VersionDiff;
use eZ\Publish\API\Repository\Values\Content\VersionInfo;

interface ComparingService
{
    public function compareVersions(
        VersionInfo $version,
        VersionInfo $versionToCompare,
        ?string $languageCode = null
    ): VersionDiff;
}
