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
        if ($object->isObjectNew() && !$object->hasCreatedAt()) {
            $object->setCreatedAt($this->date->gmtDate());
        }

        $object->setUpdatedAt($this->date->gmtDate());

        return parent::_beforeSave($object);
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

}
