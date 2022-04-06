# imgCloud



[TOC]



## 介绍
imgCloud图床，一个基于Vue-Cli和ThinkPHP的前后端分离图床。

## 软件架构
前端使用Vue-Cli,后端使用ThinkPHP，前后端分离

## 安装教程（新版）


### （一）事前准备
imgCloud前后端分离，后续将会推出多服务器支持
1. 您需要两个域名（我们以tu.zhfblog.top和api.tu.zhfblog.top为例）
2. 您需要先确保安装了node.JS和npm，现在node.JS自带npm，在命令提示符中输入npm -v查看npm是否安装
3. 在您的电脑上输入命令 `git clone https://gitee.com/ZHFHZ/img-cloud.git` 下载此项目，如您没有安装Git，则下载zip文件并解压

### （二）部署后端
1. 本程序后端采用ThinkPHP，建议使用PHP7.4环境部署
2. 打开服务器（以宝塔面板为例）
3. 新建数据库，命名为imgCloud或其他
4. 下载编译好的文件：[下载发行版](https://gitee.com/ZHFHZ/img-cloud/releases/)
5. 其中有两个文件夹web与server，web为前端，server为后端
6. 在后端域名网站中上传server中的tp文件夹
7. 修改tp目录中`.env`文件的内容，其中
```
DATABASE = 数据库名
USERNAME = 数据库用户名
PASSWORD = 数据库密码
```
3. 访问后端域名，如无报错，则安装成功


### （三）前端安装
1. 前端文件位于压缩包下的`web`目录中
2. 修改目录中的`config.js`文件，其中：
```
    dataServer:"当前数据服务器/",
    storageServer:"当前存储服务器/"
```
存储服务器和数据服务器都填写后端地址，其他不用修改（末尾要带上”/“）：
3. 上传`web`目录下的所有文件到前端网站中
4. 访问域名，查看是否安装成功


### （四）创建超级管理员
1. 前后端部署完成后，您需要访问`前端域名/#/install`创建超级管理员



## 安装教程（旧版） -- 从源码编译安装
### （一）事前准备
imgCloud前后端分离，后续将会推出多服务器支持
1. 您需要两个域名（我们以tu.zhfblog.top和api.tu.zhfblog.top为例）
2. 您需要先确保安装了node.JS和npm，现在node.JS自带npm，在命令提示符中输入npm -v查看npm是否安装
3. 在您的电脑上输入命令 `git clone https://gitee.com/ZHFHZ/img-cloud.git` 下载此项目，如您没有安装Git，则下载zip文件并解压
### （二）部署后端
1. 本程序后端采用ThinkPHP，建议使用PHP7.4环境部署
2. 打开服务器（以宝塔面板为例）
3. 新建数据库，导入imgCloud.sql文件
4. 新建网站api.tu.zhfblog.top（此处替换成您的后端域名）
5. 上传server中的tp文件夹（建议整个文件夹都上传）
6. 网站文件夹位置选择`tp\public`文件夹
7. 修改tp文件夹中的`.example.env`的内容，其中：
```
DATABASE = 数据库名
USERNAME = 数据库用户名
PASSWORD = 数据库密码
```
并把文件名改为`.env`

8. 访问后端，查看是否正确配置完成。

### （三）编译前端
1. 使用命令提示符定位到刚刚下载的`img-cloud`文件夹中的`img-cloud-web-vue3`文件夹，
3. 修改`public`文件夹下的`config.js`文件如下（末尾要带上”/“）：
```
dataServer:"当前数据服务器/", 
storageServer:"当前存储服务器/"
```
4. 修改完成后输入`npm install`，会自动安装所需要的库，如果安装缓慢，您可以自行百度`cnpm`使用教程
5. 安装完成后，输入`npm run build`编译前端文件
6. 在服务器上新建前端网站
7. 上传刚刚编译好的文件，文件文件在`img-cloud-web-vue3`的`dist`文件夹下


## 使用说明
演示地址：http://tu.zhfblog.top/

已经部署好的后端接口文档：https://easydoc.net/s/63476661

用户上传的文件在后端`public\storage`文件夹下，缩略图在`public\thumb`文件夹下


### Q & A 问答
**Q：安装完成后`#/install`路径还是能被访问，会不会造成危险**

A：无需担心！安装完成后，程序自动会在后端`tp`目录下生成`install_lock.txt`文件，如果检测到该文件存在，则会拒绝一切创建超级管理员的命令。

**Q：如何审查用户图片**

A：当前仅有超级管理员可以审查用户图片，后续会接入大厂鉴黄接口

**Q：关于后续开发进度**

A：代码将会一直维护下去，建议您进群了解开发进度

**Q：关于开源以及商用**

A：程序将永久开源，支持任何人进行二次开发。目前程序还在开发阶段，现有版本为1.0.0-beta，不建议进行商业使用。




## 更新日志

### 2022.04.06 v1.0.0-beat
1. v1.0.0-beat 版本发布
2. 更新代码逻辑，部署前不用次次编译了。
3. 支持直接部署和从源码编译两种安装方式，建议直接部署。
4. 新增安装模块，安装配置更简单。

### 2022.03.30
1. 新增图片管理与用户管理功能.

### 2022.03.28
1. 优化操作逻辑，减少页面重新加载，提升用户体验。
2. 增加用户组功能，添加管理员后台，开始开发管理员后台模块。

### 2022.03.06
1. 优化操作逻辑，解决了用户查看图片时，会向服务器请求所有文件，造成服务器流量浪费的问题。

### 2022.02.25
1. 项目正式发布 V1.0
## 参与贡献
咸鱼郑某

项目正在加紧更新，可以加群提出你想要的功能，取百家之所长，云盘将永久免费开源。

QQ群：857994945


#### 感谢以下开源项目

[Vue-Cli](https://cli.vuejs.org/zh/)

[Vue](https://cn.vuejs.org/)

[Vue-Route](https://router.vuejs.org/zh/)

[Vuex](https://vuex.vuejs.org/zh/index.html)

[element-plus](https://element-plus.gitee.io/zh-CN/)

[Axios](https://www.axios-http.cn/)

[ThinkPHP](https://www.thinkphp.cn/)

