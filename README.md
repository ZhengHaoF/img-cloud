# imgCloud

#### 介绍
imgCloud图床，一个基于Vue-Cli和ThinkPHP的前后端分离图床。

#### 软件架构
前端使用Vue-Cli,后端使用ThinkPHP，前后端分离


#### 安装教程
#### （一）事前准备
imgCloud前后端分离，后续将会推出多服务器支持
1. 您需要两个域名（我们以tu.zhfblog.top和api.tu.zhfblog.top为例）
2. 您需要先确保安装了node.JS和npm，现在node.JS自带npm，在命令提示符中输入npm -v查看npm是否安装
3. 在您的电脑上输入命令 `git clone https://gitee.com/ZHFHZ/img-cloud.git` 下载此项目，如您没有安装Git，则下载zip文件并解压
#### （二）部署后端
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

#### （三）编译前端
1. 使用命令提示符定位到刚刚下载的`img-cloud`文件夹中的`img-cloud-web-vue3`文件夹，
3. 修改`src\store`文件夹下的`index.js`文件如下（末尾要带上”/“）：
```
dataServer:"当前数据服务器/", 
storageServer:"当前存储服务器/"
```
4. 修改完成后输入`npm install`，会自动安装所需要的库，如果安装缓慢，您可以自行百度`cnpm`使用教程
5. 安装完成后，输入`npm run build`编译前端文件
6. 在服务器上新建前端网站
7. 上传刚刚编译好的文件，文件文件在`img-cloud-web-vue3`的`dist`文件夹下


#### 使用说明
演示地址：http://tu.zhfblog.top/


#### 参与贡献
咸鱼郑某


#### 感谢以下开源项目

[Vue-Cli](https://cli.vuejs.org/zh/)

[Vue](https://cn.vuejs.org/)

[Vue-Route](https://router.vuejs.org/zh/)

[Vuex](https://vuex.vuejs.org/zh/index.html)

[element-plus](https://element-plus.gitee.io/zh-CN/)

[Axios](https://www.axios-http.cn/)

[ThinkPHP](https://www.thinkphp.cn/)


