<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Annotation\ApiField as ApiField;
use PhraseanetSDK\Annotation\ApiRelation as ApiRelation;

class Feed
{
    /**
     * @ApiField(bind_to="id", type="int")
     */
    protected $id;
    /**
     * @ApiField(bind_to="title", type="string")
     */
    protected $title;
    /**
     * @ApiField(bind_to="icon", type="string")
     */
    protected $icon;
    /**
     * @ApiField(bind_to="subtitle", type="string")
     */
    protected $subTitle;
    /**
     * @ApiField(bind_to="total_entries", type="int")
     */
    protected $totalEntries;
    /**
     * @ApiField(bind_to="created_on", type="date")
     */
    protected $createdOn;
    /**
     * @ApiField(bind_to="updated_on", type="date")
     */
    protected $updatedOn;
    /**
     * @ApiField(bind_to="entries", type="relation", virtual="1")
     * @ApiRelation(type="one_to_many", target_entity="FeedEntry")
     */
    protected $entries;
    /**
     * @ApiField(bind_to="public", type="boolean")
     */
    protected $public;
    /**
     * @ApiField(bind_to="readonly", type="boolean")
     */
    protected $readonly;
    /**
     * @ApiField(bind_to="deletable", type="boolean")
     */
    protected $deletable;

    /**
     * The feed id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * The feed title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * The feed icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * The feed subtitle
     *
     * @return string
     */
    public function getSubTitle()
    {
        return $this->subTitle;
    }

    public function setSubTitle($subTitle)
    {
        $this->subTitle = $subTitle;
    }

    /**
     * Get the total entries of the feed
     *
     * @return integer
     */
    public function getTotalEntries()
    {
        return $this->totalEntries;
    }

    public function setTotalEntries($totalEntries)
    {
        $this->totalEntries = $totalEntries;
    }

    /**
     * Creation date
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    public function setCreatedOn(\DateTime $createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * Last updated date
     *
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    public function setUpdatedOn(\DateTime $updatedOn)
    {
        $this->updatedOn = $updatedOn;
    }

    public function getEntries($offset, $perPage)
    {
        return $this->entries;
    }

    public function setEntries(ArrayCollection $entries)
    {
        $this->entries = $entries;
    }

    /**
     * Tell whether the feed is public or not
     *
     * @return Boolean
     */
    public function isPublic()
    {
        return $this->public;
    }

    public function setPublic($public)
    {
        $this->public = $public;
    }

    /**
     * Tell whether the feed is a read only feed
     *
     * @return Boolean
     */
    public function isReadonly()
    {
        return $this->readonly;
    }

    public function setReadonly($readonly)
    {
        $this->readonly = $readonly;
    }

    /**
     * Tell whether the feed is deletable
     *
     * @return Boolean
     */
    public function isDeletable()
    {
        return $this->deletable;
    }

    public function setDeletable($deletable)
    {
        $this->deletable = $deletable;
    }
}
