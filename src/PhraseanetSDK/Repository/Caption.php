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

class Caption extends AbstractRepository
{
    /**
     * Find All the caption metadata for the provided record
     *
     * @param  integer          $databoxId The record databox id
     * @param  integer          $recordId  The record id
     * @return ArrayCollection
     * @throws RuntimeException
     */
    public function findByRecord($databoxId, $recordId)
    {
        $response = $this->query('GET', sprintf('records/%d/%d/caption/', $databoxId, $recordId));

        $caption = new ArrayCollection();

        if (true !== $response->hasProperty('caption_metadatas')) {
            throw new RuntimeException('Missing "caption_metadatas" property in response content');
        }

        foreach ($response->getProperty('caption_metadatas') as $metaData) {
            $caption->add(EntityHydrator::hydrate('recordCaption', $metaData, $this->em));
        }

        return $caption;
    }
}
