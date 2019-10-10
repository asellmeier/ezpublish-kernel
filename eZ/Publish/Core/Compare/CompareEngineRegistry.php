<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace eZ\Publish\Core\Compare;

use eZ\Publish\API\Repository\CompareEngine;

class CompareEngineRegistry
{
    /** @var \eZ\Publish\API\Repository\CompareEngine[] */
    protected $engines = [];

    /**
     * @param \eZ\Publish\API\Repository\CompareEngine[] $engines
     */
    public function __construct(array $engines = [])
    {
        foreach ($engines as $supportedType => $engine) {
            $this->registerEngine($supportedType, $engine);
        }
    }

    public function registerEngine(string $supportedType, CompareEngine $engine): void
    {
        $this->engines[$supportedType] = $engine;
    }

    public function getEngineForType(string $supportedType): CompareEngine
    {
        return $this->engines[$supportedType];
    }
}
