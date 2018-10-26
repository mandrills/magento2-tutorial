<?php

namespace Tutorial\Blog\Controller;

class Router  implements \Magento\Framework\App\RouterInterface
{
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;
    /**
     * @var \Tutorial\Blog\Model\PostFactory
     */
    protected $postFactory;

    /**
     * Router constructor.
     * @param \Tutorial\Blog\Model\PostFactory $postFactory
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     */
    public function __construct(
        \Tutorial\Blog\Model\PostFactory $postFactory,
        \Magento\Framework\App\ActionFactory $actionFactory
    )
    {
        $this->postFactory = $postFactory;
        $this->actionFactory = $actionFactory;
    }

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return boolean
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $url_key = trim($request->getPathInfo(), '/blog/');

        var_dump($url_key);
        $url_key = rtrim($url_key, '/');

        var_dump($url_key);
        exit;

        /** @var \Tutorial\Blog\Model\Post $post */
        $post = $this->postFactory->create();
        $post_id = $post->checkUrlKey($url_key);
        if (!$post_id) {
            return null;
        }

        $request->setModuleName('blog')
            ->setControllerName('view')
            ->setActionName('index')
            ->setParam('post_id', $post_id);

        $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $url_key);

        return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class);

    }

}