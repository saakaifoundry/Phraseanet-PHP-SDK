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

class DataboxCollection extends AbstractRepository
{
    /**
     * Find all collection in the provided databox
     *
     * @param  integer          $databoxId the databox id
     * @return ArrayCollection
     * @throws RuntimeException
     */
    public function findByDatabox($databoxId)
    {
        $response = $this->query('GET', sprintf('databoxes/%d/collections/', $databoxId));

        if (true !== $response->hasProperty('collections')) {
            throw new RuntimeException('Missing "collections" property in response content');
        }

        $databoxCollections = new ArrayCollection();

        foreach ($response->getProperty('collections') as $databoxCollectionDatas) {
            $databoxCollections->add(EntityHydrator::hydrate('databoxCollection', $databoxCollectionDatas, $this->em));
        }

        return $databoxCollections;
    }
}
