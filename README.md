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
2. [设置模型和基本模型](#设置模型和基本模型)
3. [设置数据库和迁移](#设置数据库和迁移)
4. [前端控制器、块、布局和试图](#前端控制器、块、布局和试图)
5. [后台控制器、块、用户界面、布局和试图](#后台控制器、块、用户界面、布局和试图)
6. [单元测试](#单元测试)


## 模块创建步骤

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


### 基本模块设置

### 设置模型和基本模型

### 设置数据库和迁移

### 后台控制器、块、用户界面、布局和试图

### 单元测试

