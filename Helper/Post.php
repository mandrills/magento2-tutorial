<?php

namespace Tutorial\Blog\Helper;

class Post extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Tutorial\Blog\Model\Post
     */
    protected $post;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Post constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Tutorial\Blog\Model\Post $post
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Tutorial\Blog\Model\Post $post,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->post = $post;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @param \Magento\Framework\App\Action\Action $action
     * @param null $postId
     * @return bool|\Magento\Framework\View\Result\Page
     */
    public function prepareResultPost(\Magento\Framework\App\Action\Action $action, $postId = null)
    {
        if ($postId !== null && $postId !== $this->post->getId()) {
            $delimiterPosition = strrpos($postId, '|');
            if ($delimiterPosition) {
                $postId = substr($postId, 0, $delimiterPosition);
            }

            if (!$this->post->load($postId)) {
                return false;
            }
        }

        if (!$this->post->getId()) {
            return false;
        }

        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        // We can add our own custom page handles for layout easily.
        $resultPage->addHandle('blog_post_view');

        // This will generate a layout handle like: blog_post_view_id_1
        // giving us a unique handle to target specific blog posts if we wish to.
        $resultPage->addPageLayoutHandles(['id' => $this->post->getId()]);

        // Magento is event driven after all, lets remember to dispatch our own, to help people
        // who might want to add additional functionality, or filter the posts somehow!

        $this->_eventManager->dispatch(
            'tutorial_blog_post_render',
            ['post' => $this->post, 'controller_action' => $action]
        );

        return $resultPage;

    }
}