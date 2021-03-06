<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Cache;

use Guzzle\Plugin\Cache\SkipRevalidation;
use Guzzle\Plugin\Cache\DenyRevalidation;
use PhraseanetSDK\Exception\RuntimeException;

class RevalidationFactory
{
    public function create($type)
    {
        switch (strtolower($type)) {
            case 'skip':
                return new SkipRevalidation();
            case 'deny':
                return new DenyRevalidation();
            default:
                throw new RuntimeException(sprintf('Unknown revalidation type %s, available are `skip`, `deny`.', $type));
        }
    }
}
