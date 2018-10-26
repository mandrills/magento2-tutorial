<?php
namespace Tutorial\Blog\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Post extends AbstractDb
{
    protected $date;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        ?string $connectionName = null)
    {
        $this->date = $date;
        parent::__construct($context, $connectionName);
    }

    protected function _construct()
    {
        $this->_init('tutorial_blog_post','post_id');
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @throws LocalizedException
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if (!$this->isValidPostUrlKey($object)) {
            throw new LocalizedException(__('The post URL key contains capital letters or disallowed symbols.'));
        }
        if ($this->isNumericPostUrlKey($object)) {
            throw new LocalizedException(__('The post URL key cannot be made of only numbers.'));
        }

        if ($object->isObjectNew() && !$object->hasCreatedAt()) {
            $object->setCreatedAt($this->date->gmtDate());
        }

        $object->setUpdatedAt($this->date->gmtDate());

        return parent::_beforeSave($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param mixed $value
     * @param string $field
     * @return $this
     */
    public function load(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {
        if (!is_numeric($value) && is_null($field)) {
            $field = 'url_key';
        }

        return parent::load($object, $value, $field);
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {

            $select->where(
                'is_active = ?',
                1
            )->limit(
                1
            );
        }

        return $select;
    }

    /**
     * @param string $url_key
     * @param int $isActive
     * @return \Magento\Framework\DB\Select
     * @throws LocalizedException
     */
    protected function _getLoadByUrlKeySelect($url_key, $isActive = null)
    {
        $select = $this->getConnection()->select()->from(
            ['bp' => $this->getMainTable()]
        )->where(
            'bp.url_key = ?',
            $url_key
        );

        if (!is_null($isActive)) {
            $select->where('bp.is_active = ?', $isActive);
        }

        return $select;
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return false|int
     */
    protected function isValidPostUrlKey(\Magento\Framework\Model\AbstractModel $object)
    {
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $object->getData('url_key'));
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return false|int
     */
    protected function isNumericPostUrlKey(\Magento\Framework\Model\AbstractModel $object)
    {
        return preg_match('/^[0-9]+$/', $object->getData('url_key'));
    }

    /**
     * @param $url_key
     * @return int
     * @throws LocalizedException
     */
    public function checkUrlKey($url_key)
    {
        $select = $this->_getLoadByUrlKeySelect($url_key, 1);
        $select->reset(\Zend_Db_Select::COLUMNS)->columns('bp.post_id')->limit(1);

        return $this->getConnection()->fetchOne($select);

    }




}
