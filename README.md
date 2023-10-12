## 环境需求

- Swoole >= 4.6.x 并关闭 `Short Name`
- PHP >= 8.0 并开启以下扩展：
  - mbstring
  - json
  - pdo
  - openssl
  - redis
  - pcntl
- Mysql >= 5.7
- Redis >= 4.0
- Git >= 2.x


## 下载项目

- 项目下载，请确保已经安装了 `Composer`
```shell
git clone https://github.com/jenawant/msproadmin && cd msproadmin
composer install
```

## 项目安装

打开终端，执行安装命令，按照提示，一步步完成`.env`文件的配置
```shell
php bin/hyperf.php mspro:install
```

待提示以下信息后
```shell
Reset the ".env" file. Please restart the service before running 
the installation command to continue the installation.
```

再次执行安装命令，执行Migrates数据迁移文件和SQL数据填充，完成安装。
```shell
php bin/hyperf.php mspro:install
```

## 初衷

我也是名PHPer，前后端都懂点，在为项目寻找新脚手架时，机缘巧合看到了MineAdmin，基于Hyperf的管理后台，与我之前用的基于ThinkPHP的架构比较相似，符合预期，但是前端我的技术栈是React+AntD Pro+UmiJs，然后又1:1写了套前端部分，后端部分基于项目需求，也有不少调整，例如：数据存储部分，关键数据表实现自动索引+分表处理；数据导出部分，改用了异步队列形式以应对大数据；代码生成部分，控制器中增加了用于前端下拉列表的无分页输出，Mapper中增加了多模型关系的条件限定等；计划任务日志部分，优化了无需入库的判断；邮件服务部分，引入了hyperf-ext/mail以实现批量异步发送；还有其他一些改动，都涉及到核心引用，所以干脆就自建一套，方便后续更新迭代。

## 鸣谢

> 以下排名不分先后

[Hyperf 一款高性能企业级协程框架](https://hyperf.io/)

[MineAdmin 一套完备的后台管理系统](https://www.mineadmin.com/)

[AntDesign Pro 蚂蚁集团出品的企业级设计系统](https://pro.ant.design/)

[Swoole PHP协程框架](https://www.swoole.com)

[React](https://react.dev/)

[UmiJS](https://umijs.org/)

[Jetbrains 生产力工具](https://www.jetbrains.com/)

