<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace eZ\Publish\API\Repository\Exceptions;

use Exception;

class InvalidVariationException extends InvalidArgumentException
{
    public function __construct($variationName, $variationType, $code = 0, Exception $previous = null)
    {
        parent::__construct("Invalid variation '$variationName' for $variationType", $code, $previous);
    }
}
