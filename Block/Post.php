<?php
namespace Tutorial\Blog\Block;

class Post extends \Magento\Framework\View\Element\Template implements  \Magento\Framework\DataObject\IdentityInterface
{
    /**
     * @var \Tutorial\Blog\Model\Post
     */
    protected $post;
    /**
     * @var \Tutorial\Blog\Model\PostFactory
     */
    protected $postFactory;

    /**
     * Post constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Tutorial\Blog\Model\Post $post
     * @param \Tutorial\Blog\Model\PostFactory $postFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Tutorial\Blog\Model\Post $post,
        \Tutorial\Blog\Model\PostFactory $postFactory,
        array $data = []
    )
    {
        $this->post = $post;
        $this->postFactory = $postFactory;
        parent::__construct($context, $data);
    }

    public function getPost()
    {
        // Check if posts has already been defined
        // makes our block nice and re-usable! We could
        // pass the 'posts' data to this block, with a collection
        // that has been filtered differently!
        if (!$this->hasData('post')) {
            if ($this->getPostId()) {
                /** @var \Tutorial\Blog\Model\Post $post */
                $post = $this->postFactory->create();
            } else {
                $post = $this->post;
            }
            $this->setData('post', $post);
        }
        return $this->getData('post');
    }

    /**
     * @return array|string[]
     */
    public function getIdentities()
    {
        return [\Tutorial\Blog\Model\Post::CACHE_TAG . '_' . $this->getPost()->getId()];
    }

}
