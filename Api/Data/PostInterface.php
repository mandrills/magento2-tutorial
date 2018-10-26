<?php
namespace Tutorial\Blog\Api\Data;
interface PostInterface
{
    const POST_ID = 'post_id';
    const URL_KEY = 'url_key';
    const TITLE = 'title';
    const CONTENT = 'content';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const PUBLISHED_AT = 'published_at';
    const IS_ACTIVE = 'is_active';

    /**
     * @return int|null
     */
    public function getId();

    /**
     * @return string
     */
    public function getUrlKey();

    /**
     * @return string|null
     */
    public function getTitle();

    /**
     * @return string|null
     */
    public function getContent();

    /**
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * @return string|null
     */
    public function getPublishedAt();

    /**
     * @return boolean|null
     */
    public function isActive();

    /**
     * @param $id
     * @return $this
     */
    public function setId($id);

    /**
     * @param string $url_key
     * @return $this
     */
    public function setUrlKey($url_key);

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content);

    /**
     * @param string $created_at
     * @return $this
     */
    public function setCreatedAt($created_at);

    /**
     * @param string $updated_at
     * @return $this
     */
    public function setUpdatedAt($updated_at);

    /**
     * @param string $published_at
     * @return $this
     */
    public function setPublishedAt($published_at);

    /**
     * @param int|bool $is_active
     * @return $this
     */
    public function setIsActive($is_active);

}