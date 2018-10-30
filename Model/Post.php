<?php
namespace Tutorial\Blog\Model;

use Tutorial\Blog\Api\Data\PostInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Post extends \Magento\Framework\Model\AbstractModel implements PostInterface, IdentityInterface
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    const CACHE_TAG = 'blog_post';

    protected $_cacheTag = 'blog_post';

    protected $_eventPrefix = 'blog_post';

    protected function _construct()
    {
        $this->_init('Tutorial\Blog\Model\ResourceModel\Post');
    }

    /**
     * @return array|string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [
          self::STATUS_ENABLED => __('Enabled'),
          self::STATUS_DISABLED => __('Disabled')
        ];
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::POST_ID);
    }

    /**
     * @return null|string
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @return null|string
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * @return null|string
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @return null|string
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @return bool|null
     */
    public function isActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * @param mixed $id
     * @return PostInterface|Post
     */
    public function setId($id)
    {
        return $this->setData(self::POST_ID, $id);
    }

    /**
     * @param string $title
     * @return PostInterface|Post
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @param string $content
     * @return mixed|PostInterface
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * @param string $created_at
     * @return PostInterface|Post
     */
    public function setCreatedAt($created_at)
    {
        return $this->setData(self::CREATED_AT, $created_at);
    }

    /**
     * @param string $updated_at
     * @return PostInterface|Post
     */
    public function setUpdatedAt($updated_at)
    {
        return $this->setData(self::UPDATED_AT, $updated_at);
    }

    /**
     * @param bool|int $is_active
     * @return PostInterface|Post
     */
    public function setIsActive($is_active)
    {
        return $this->setData(self::IS_ACTIVE, $is_active);
    }

}
