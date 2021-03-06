<?php

namespace PhraseanetSDK\Tests;

use PhraseanetSDK\Entity\Feed;
use PhraseanetSDK\Entity\FeedEntry;
use PhraseanetSDK\Entity\FeedEntryItem;
use PhraseanetSDK\Entity\Record;
use PhraseanetSDK\Entity\Subdef;
use PhraseanetSDK\Entity\Permalink;
use PhraseanetSDK\EntityHydrator;
use PhraseanetSDK\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;

class EntityHydratorTest extends \PHPUnit_Framework_TestCase
{
    private function getOneFeed()
    {
        $json = '{
                "id": 3,
                "title": "hellow world",
                "subtitle": "what\'s up",
                "total_entries": 4,
                "icon": "good bye",
                "created_on": "2011-07-20T18:45:20+02:00",
                "updated_on": "2011-07-20T18:45:20+02:00"
            }';

        return json_decode($json);
    }

    private function getOneFeedEntry()
    {
        $json = '{
                "author_email": "legoff@alchemy.fr",
                "author_name": "legoff@alchemy.fr",
                "created_on": "2011-11-04T14:39:47+01:00",
                "updated_on": "2011-11-04T14:39:47+01:00",
                "title": "My Entry Test",
                "subtitle": "My Entry subtitle",
                "items": [
                    {
                        "item_id": 23430,
                        "record": {
                            "databox_id": 1,
                            "record_id": 15,
                            "mime_type": "image/jpeg",
                            "title": "eos53_04hdr.jpg",
                            "original_name": "eos53_04hdr.jpg",
                            "updated_on": "2011-10-20T13:00:04+02:00",
                            "created_on": "2011-10-20T12:59:54+02:00",
                            "collection_id": 1,
                            "sha256": "694dbf5bf78009d5f0f16a8505ea71612f6256e5892fa441064b24cc664bf3cd",
                            "thumbnail": {
                                "permalink": {
                                    "created_on": "2011-10-20T13:00:04+02:00",
                                    "id": 22,
                                    "is_activated": true,
                                    "label": "eos53_04hdrjpg",
                                    "last_modified": "2011-10-20T13:00:04+02:00",
                                    "page_URL": "http://dev.phrasea.net/permalink/v1/eos53_04hdrjpg/1/15/9Xxw2Ghv/thumbnail/view/",
                                    "URL": "http://dev.phrasea.net/permalink/v1/eos53_04hdrjpg/1/15/9Xxw2Ghv/thumbnail/"
                                },
                                "height": 131,
                                "width": 200,
                                "file_size": 6411,
                                "player_type": "IMAGE",
                                "mime_type": "image/jpeg"
                            },
                            "technical_informations": [{
                                "name" : "bits",
                                "value": "8"
                            }, {
                                "name" : "Orientation",
                                "value": "1"
                            }, {
                                "name" : "channels",
                                "value": "3"
                            }],
                            "phrasea_type": "image",
                            "uuid": "7c6ef16c-52d4-4fda-aaf9-bd73c4e38205"
                        }
                    },
                    {
                        "item_id": 23431,
                        "record": {
                            "databox_id": 1,
                            "record_id": 16,
                            "mime_type": "image/jpeg",
                            "title": "eos53_7267_magg_meno.jpg",
                            "original_name": "eos53_7267_magg_meno.jpg",
                            "updated_on": "2011-10-20T13:00:02+02:00",
                            "created_on": "2011-10-20T12:59:56+02:00",
                            "collection_id": 1,
                            "sha256": "958d662a0833a0a1bc0007def0cc9007246a0a53985352e0f7325e45b00a5783",
                            "thumbnail": {
                                "permalink": {
                                    "created_on": "2011-10-20T13:00:02+02:00",
                                    "id": 20,
                                    "is_activated": true,
                                    "label": "eos53_7267_magg_menojpg",
                                    "last_modified": "2011-10-20T13:00:02+02:00",
                                    "page_URL": "http://dev.phrasea.net/permalink/v1/eos53_7267_magg_menojpg/1/16/D7zF5vGG/thumbnail/view/",
                                    "URL": "http://dev.phrasea.net/permalink/v1/eos53_7267_magg_menojpg/1/16/D7zF5vGG/thumbnail/"
                                },
                                "height": 133,
                                "width": 200,
                                "file_size": 6662,
                                "player_type": "IMAGE",
                                "mime_type": "image/jpeg"
                            },
                            "technical_informations": [{
                                "name" : "bits",
                                "value": "8"
                            }, {
                                "name" : "Orientation",
                                "value": "1"
                            }, {
                                "name" : "channels",
                                "value": "3"
                            }],
                            "phrasea_type": "image",
                            "uuid": "383a153b-f2e5-44a4-a71e-7d2c63a129d3"
                        }
                    }
                ]
            }';

        return json_decode($json);
    }

    public function testHydrate()
    {
        $client = $this->getMockBuilder('PhraseanetSDK\Http\APIGuzzleAdapter')
            ->disableOriginalConstructor()
            ->getMock();

        $em = new EntityManager($client);

        $feed = EntityHydrator::hydrate('feed', $this->getOneFeed(), $em);

        $this->assertEquals(3, $feed->getId());
        $this->assertEquals('hellow world', $feed->getTitle());
        $this->assertEquals('good bye', $feed->getIcon());
        $this->assertEquals('what\'s up', $feed->getSubTitle());
        $this->assertEquals(4, $feed->getTotalEntries());
        $this->assertEquals('2011-07-20T18:45:20+02:00', $feed->getCreatedOn()->format(\DateTime::ATOM));
        $this->assertEquals('2011-07-20T18:45:20+02:00', $feed->getUpdatedOn()->format(\DateTime::ATOM));

        $entry = EntityHydrator::hydrate('feedEntry', $this->getOneFeedEntry(), $em);

        /* @var $entry \PhraseanetSDK\Entity\Entry */
        $this->assertEquals('legoff@alchemy.fr', $entry->getAuthorEmail());
        $this->assertEquals('legoff@alchemy.fr', $entry->getAuthorName());
        $this->assertEquals('2011-11-04T14:39:47+01:00', $entry->getCreatedOn()->format(\DateTime::ATOM));
        $this->assertEquals('2011-11-04T14:39:47+01:00', $entry->getUpdatedOn()->format(\DateTime::ATOM));
        $this->assertEquals('My Entry subtitle', $entry->getSubtitle());
        $this->assertEquals('My Entry Test', $entry->getTitle());
        $items = $entry->getItems();
        $this->assertTrue($items instanceof ArrayCollection);
        $this->assertEquals(2, $items->count());
        foreach ($items as $item) {
            $this->assertTrue($item instanceof FeedEntryItem);
            $record = $item->getRecord();
            $this->assertTrue($record instanceof Record);
            $thumbnail = $record->getThumbnail();
            $this->assertTrue($thumbnail instanceof Subdef);
            $permalink = $thumbnail->getPermalink();
            $this->assertTrue($permalink instanceof Permalink);
        }
    }
}
