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

class Entry extends AbstractRepository
{
    /**
     * Retrieve the entry identified by its id
     *
     * @param  integer                    $id The entry id
     * @return \PhraseanetSDK\Entity\Feed
     * @throws RuntimeException
     */
    public function findById($id)
    {
        $response = $this->query('GET', sprintf('feeds/entry/%d/', $id));

        if (true !== $response->hasProperty('entry')) {
            throw new RuntimeException('Missing "entry" property in response content');
        }

        return EntityHydrator::hydrate('feedEntry', $response->getProperty('entry'), $this->em);
    }

    /**
     * Find all entries that belongs to the feed provided in parameters
     *
     * @param  integer                                      $feedId      The feed id
     * @param  integer                                      $offsetStart The start offset
     * @param  integer                                      $perPage     The number of entries
     * @return \Doctrine\Common\Collections\ArrayCollection
     * @throws RuntimeException
     */
    public function findByFeed($feedId, $offsetStart = 0, $perPage = 5)
    {
        $response = $this->query('GET', sprintf('feeds/%d/content/', $feedId), array(
            'offset_start' => $offsetStart,
            'per_page'     => $perPage
        ));

        if (true !== $response->hasProperty('entries')) {
            throw new RuntimeException('Missing "entries" property in response content');
        }

        $entries = new ArrayCollection();

        foreach ($response->getProperty('entries') as $entryData) {
            $entries->add(EntityHydrator::hydrate('feedEntry', $entryData, $this->em));
        }

        return $entries;
    }
    /**
     * Find entries in the all available rss feed
     *
     * @param  integer                                      $offsetStart The start offset
     * @param  integer                                      $perPage     The number of entries
     * @param  array                                        $feed        The feed id's to look for
     * @return \Doctrine\Common\Collections\ArrayCollection
     * @throws RuntimeException
     */

    /**
     * rename to find
     * */
    public function findInAggregatedFeed($offsetStart = 0, $perPage = 5, array $feeds = array())
    {
        $response = $this->query('GET', 'feeds/content/', array(
            'offset_start' => $offsetStart,
            'per_page'     => $perPage,
            'feeds'        => $feeds
            ));

        if (true !== $response->hasProperty('entries')) {
            throw new RuntimeException('Missing "entries" property in response content');
        }

        $entries = new ArrayCollection();

        foreach ($response->getProperty('entries') as $entryData) {
            $entries->add(EntityHydrator::hydrate('feedEntry', $entryData, $this->em));
        }

        return $entries;
    }
}
