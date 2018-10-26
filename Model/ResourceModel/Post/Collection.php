<?php
namespace Tutorial\Blog\Model\ResourceModel\Post;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'post_id';

    protected function _construct()
    {
        $this->_init('Tutorial\Blog\Model\Post', 'Tutorial\Blog\Model\ResourceModel\Post');
    }
}
