<?php
namespace Tutorial\Blog\Block;

use Tutorial\Blog\Api\Data\PostInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Tutorial\Blog\Model\ResourceModel\Post\Collection as PostCollection;

class Posts extends \Magento\Framework\View\Element\Template implements IdentityInterface
{
    /**
     * @var \Tutorial\Blog\Model\ResourceModel\Post\CollectionFactory
     */
    protected $_postCollectionFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Tutorial\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory,
        array $data = [])
    {
        $this->_postCollectionFactory = $postCollectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return \Tutorial\Blog\Model\ResourceModel\Post\Collection
     */
    public function getPosts(){
        if (!$this->hasData('posts')) {
            $posts = $this->_postCollectionFactory
                ->create()
                ->addFilter('is_active', 1)
                ->addOrder(
                    PostInterface::CREATED_AT,
                    PostCollection::SORT_ORDER_DESC
                );
            $this->setData('posts', $posts);
        }
        return $this->getData('posts');
    }

    /**
     * @return array|string[]
     */
    public function getIdentities()
    {
        return [\Tutorial\Blog\Model\Post::CACHE_TAG.'_'.'list'];
    }

}
