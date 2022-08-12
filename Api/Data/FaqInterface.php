<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Api\Data;

/**
 * Interface FaqInterface
 * @package MageCloud\Faq\Api\Data
 */
interface FaqInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const FAQ_ID        = 'faq_id';
    const TITLE         = 'title';
    const CONTENT       = 'content';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME   = 'update_time';
    const IS_ACTIVE     = 'is_active';
    const SORT_ORDER    = 'sort_order';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Get content
     *
     * @return string|null
     */
    public function getContent();

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime();

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Get sort order
     *
     * @return string|null
     */
    public function getSortOrder();

    /**
     * Set ID
     *
     * @param int $id
     * @return FaqInterface
     */
    public function setId($id);

    /**
     * Set title
     *
     * @param string $title
     * @return FaqInterface
     */
    public function setTitle($title);

    /**
     * Set content
     *
     * @param string $content
     * @return FaqInterface
     */
    public function setContent($content);

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return FaqInterface
     */
    public function setCreationTime($creationTime);

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return FaqInterface
     */
    public function setUpdateTime($updateTime);

    /**
     * Set is active
     *
     * @param bool|int $isActive
     * @return FaqInterface
     */
    public function setIsActive($isActive);

    /**
     * Set sort order
     *
     * @param string $sortOrder
     * @return FaqInterface
     */
    public function setSortOrder($sortOrder);
}
