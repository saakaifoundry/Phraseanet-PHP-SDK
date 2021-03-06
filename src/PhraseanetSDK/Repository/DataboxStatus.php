<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Repository;

use PhraseanetSDK\Exception\RuntimeException;
use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\EntityHydrator;

class DataboxStatus extends AbstractRepository
{
    /**
     * The status of the desired databox
     *
     * @param  integer          $databoxId the databox id
     * @return ArrayCollection
     * @throws RuntimeException
     */
    public function findByDatabox($databoxId)
    {
        $response = $this->query('GET', sprintf('databoxes/%d/status/', $databoxId));

        if (true !== $response->hasProperty('status')) {
            throw new RuntimeException('Missing "status" property in response content');
        }

        $databoxStatusCollection = new ArrayCollection();

        foreach ($response->getProperty('status') as $databoxStatusData) {
            $databoxStatusCollection->add(EntityHydrator::hydrate('databoxStatus', $databoxStatusData, $this->em));
        }

        return $databoxStatusCollection;
    }
}
