# Magento II 博客模块教程
一个简单的 Magento II 博客模块
## 简介
Magento PHP 开发者需要快速学习如何在平台上构建我们的客户商店并构建模块。这个是个简单的博客模块，将通过这个模块学习如何创建一个完整的 Magento 2 模块，后台管理和单元测试。

通过这个博客学习，你能轻松的完成以下操作：

- 创建可通过 Composer 安装的模块
- 创建控制器并了解重写系统的工作原理
- 块、布局和模板如何工作
- 创建模型并与数据库交互
- 设置后台创建、编辑和删除操作的管理界面
- 创建单元测试 （待补充）

## 这个模块能做什么？

最终模块将是一个非常基本的博客，你将能够通过管理员创建博客文章，包括编辑和删除他们。然后从前端你将能够查看所有博客文章的列表，并能单独查看每个文章。这些操作能够涵盖构建 Magento 扩展的所有必要元素。

1. [基本模块设置](#基本模块设置)
2. [设置模型和资源模型](#设置模型和资源模型)
3. [设置数据库和迁移](#设置数据库和迁移)
4. [前端控制器、块、布局和试图](#前端控制器、块、布局和试图)
5. [后台控制器、块、用户界面、布局和试图](#后台控制器、块、用户界面、布局和试图)
6. [单元测试](#单元测试)


## 模块创建步骤

### 基本模块设置


设置我们模块的基本结构:

    etc/module.xml
    registration.php
    composer.json
    
在根目录中，我们创建一个 `composer.json` 文件，它看起来像这样：

```
{
    "name": "mandrills/magento2-tutorial",
    "description": "A simple blog module for magento 2.2.6",
    "type": "magento2-module",
    "version": "1.0.0",
    "license": [
        "OSL-3.0",
        "AFL-3.0"
    ],
    "authors": [
        {
            "name": "Andy D Xing",
            "email": "andydxing@objectivasoftware.com"
        }
    ],
    "require": {
        "php": "~7.1.*",
        "magento/magento-composer-installer": "*"
    },
    "extra": {
        "map": [
            [
                "*",
                "Tutorial/Blog"
            ]
        ]
    }
}
```
Comoposer文件这里不解释太多，不太了解的同学请查看它的[官方文档](https://getcomposer.org/doc/00-intro.md)。这里主要说下文件里 *type* 和 *extra* 字段。

`"type": "magento2-module"`  定义我们repo的类型为magento2模块

`"extra": {
   "map": [
      [
         "*",
         "Tutorial/Blog"
      ]
   ]
}` 定义composer如何安装这个模块。 翻译过来就是所有文件都应该在Tutorial/Blog文件夹中，这就意味着我们的模块将被安装到`app/code/Tutorial/Blog`。

如果想了解更多有关Composer相关的文章，请访问[Alan Kent的博客](https://alankent.me/2014/08/03/creating-a-magento-2-composer-module/)。

首先你先创建etc/module.xml文件

```
<?xml version="1.0"?>
    <config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
        <module name="Tutorial_Blog" setup_version="1.0.0" />
    </config>
```

接着我们需要在模块的根目录中创建registration.php文件，Magento中使用它来注册模块。
```
<?php
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Tutorial_Blog',
    __DIR__
);
```
现在一个基本的模块已经形成，但是它在Magento中还不能运行。要使模块正常使用，就需要启用它并升级数据库，像这样：

```
bin/magento module:status # 展示每个模块的状态：关闭/开启
bin/magento module:enable Tutorial_Blog # 开启Tutorial_Blog模块
bin/magento setup:upgrade # 升级数据库，保存当前模块的版本以及需要安装的表
bin/magento module:status # 确认当前模块状态
```
如果不使用composer安装的话，到项目下创建app/code目录（如果目录不存在需要创建），然后将代码复制到app/code/Tutorial/Blog目录结构中即可。

> app
>> code
>>> Tutorial
>>>> Blog
>>>>> registration.php

>>>>> etc
>>>>>> module.xml

### 设置模型和资源模型

让我们进入今天的主题，知识点主要涵盖：
- [x] 模型
- [x] 资源模型

Magento有很多自己的惯例，今天就开始慢慢涵盖进来，第一个就是使用PHP接口。博客模块只需要一个数据表就可以了，并且被命名为tutorial_blog_post，使用命名空间所以就不必担心冲突产生。 我们的模型就成为Post,所以在创建模型之前我们要创建一个接口，这个接口我们称之为数据接口。

如果你要了解更多关于如何使用接口，可以移步至devdocs.magento.com查看Magento官方文档。

- [服务合同](https://devdocs.magento.com/guides/v2.0/extension-dev-guide/service-contracts/service-contracts.html)

- [服务合同-数据接口](https://devdocs.magento.com/guides/v2.2/extension-dev-guide/service-contracts/design-patterns.html#data-interfaces)

在构建数据接口之前，先把博客数据表`post`使用的列罗列出来：

- post_id - post表主键ID
- url_key - 唯一 url_key,可以自定义创建post路由
- title - post标题
- content - post内容
- created_at - 创建时间
- updated_at - 更新时间
- published_at - 发布时间
- is_active - post是否开启标记

现在我们开始创建`Api/Data/PostInterface`数据接口，代码如下：

```
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
```
这个接口定义了所有的getter和setter，这个以后和模型交互时使用。接着创建我们的模型Model/Post.php文件:

```
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
     * @param string $url_key
     * @return boolean
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function checkUrlKey($url_key)
    {
        return $this->_getResource()->checkUrlKey($url_key);
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
     * @return string
     */
    public function getUrlKey()
    {
        return $this->getData(self::URL_KEY);
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
     * @return null|string
     */
    public function getPublishedAt()
    {
        return $this->getData(self::PUBLISHED_AT);
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
     * @param string $url_key
     * @return PostInterface|Post
     */
    public function setUrlKey($url_key)
    {
        return $this->setData(self::URL_KEY, $url_key);
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
     * @param string $published_at
     * @return PostInterface|Post
     */
    public function setPublishedAt($published_at)
    {
        return $this->setData(self::PUBLISHED_AT, $published_at);
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
```
正如你看到的一样，Post模型实现了PostInterface中所有的方法,同时我们也实现了第二个接口`Magento\Framework\DataObject\IdentityInterface`。这个接口被用于模型创建更新删除操作后缓存的刷新，以及在前端模型的渲染。只需要实现`getIdentities()`方法即可，这将返回这个模型的唯一ID号，这就是该模型的缓存。

现在是时候创建我们的资源模型`Model/ResourceModel/Post.php`了。

```
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

```

最后，我们需要资源模型集合，主要用来过滤模型和获取模型集合 `Model/ResourceModel/Post/Collection.php`。

```
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
```



### 设置数据库和迁移

### 后台控制器、块、用户界面、布局和试图

### 单元测试

