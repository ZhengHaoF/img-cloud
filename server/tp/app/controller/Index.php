<?php

namespace app\controller;

use app\BaseController;
use Redis;
use RedisException;
use think\db\exception\DbException;
use think\exception\ErrorException;
use think\facade\App;
use think\facade\Db;
use app\Request;
use think\facade\Filesystem;
use think\Image;
use think\response\Json;

class Index extends BaseController
{

    public function index(): string
    {
        return '<style>*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei",serif; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1><img alt="" src="./logo.svg" style="width: 300px"> </h1><p> imgCloud <br/><span style="font-size:30px;">运行环境：ThinkPHP ' . App::version() . '</span></p><span style="font-size:25px;">v1.0.0-beta</span><div><a href="https://gitee.com/ZHFHZ/img-cloud" target="_blank">开源地址</a></div></div> ';
    }


    public function upload(Request $request): Json
    {
        // 获取表单上传文件
        $file = $request->file();
        $uuid = $request->param("uuid");
        $token = $request->param("token");
        $fileSize = 1024 * 1024 * 10;
        try {
            validate(['image' => 'fileSize:' . $fileSize . '|fileExt:jpg,png,bmp,heif'])
                ->check($file);
            $file = $request->file("image");
            $file_size = $file->getSize();
            $savename = Filesystem::disk('public')->putFile('pic', $file);
            $res = $this->privateUserCheck($uuid, $token);
            $fileInfo = pathinfo($savename); //图片数据
            if ($uuid != null && $res["status"] == 200) {
                //生成缩略图
                /*
                 *
                    {
                     "dirname": "pic/20220224",
                      "basename": "476771bc8e6321352302e92608cbf9e2.png",
                      "extension": "png",
                      "filename": "476771bc8e6321352302e92608cbf9e2"
                    }
                 * */
                $image = Image::open($file);
                $image->thumb(300, 300)->save('thumb/' . pathinfo($savename)["basename"]);
                Db::table("img_allimgs")->insert([
                    'uid' => $res["uid"],
                    'dirname' => $fileInfo['dirname'],
                    'basename' => $fileInfo['basename'],
                    'extension' => $fileInfo['extension'],
                    'filename' => $fileInfo['filename'],
                    'size' => $file_size,
                    "thumb" => "thumb"
                ]);
                return json(array("imgUrl" => $savename, "msg" => "用户上传"), 200);
            }
            Db::table("img_allimgs")->insert([
                'uid' => "0",
                'dirname' => $fileInfo['dirname'],
                'basename' => $fileInfo['basename'],
                'extension' => $fileInfo['extension'],
                'filename' => $fileInfo['filename'],
                'size' => $file_size,
                "thumb" => "thumb"
            ]);
            return json(array("imgUrl" => $savename, "msg" => "游客上传"), 200);
        } catch (\think\exception\ValidateException $e) {
            return json(array("msg" => $e->getMessage()), 500);
        }
    }

    public function userRegistered(Request $request): Json
    {
        //用户注册
        $username = $request->param('username');
        $password = $request->param('pwd');
        $email = $request->param('email');
        if (Db::table("img_users")->where("username", $username)->count() === 0) {
            $data = [
                'username' => $username,
                'password' => md5($password),
                'email' => $email,
                'regdate' => date('Y-m-d H:i:s'),
            ];
            if (Db::table("img_users")->insert($data) === 1) {
                return json(array("msg" => "注册成功"), 200);
            }
        }
        return json(array("msg" => "用户名已存在"), 500);
    }

    public function userLogin(Request $request): Json
    {
        //用户登录

        try {
            $redis = $this->initRedis(); //初始化Redis
        } catch (RedisException $e) {
            return json(array("msg" => "Redis服务运行错误，请联系管理员检查"), 500);
        }

        $username = $request->param('username');
        $password = $request->param('pwd');
        $res = Db::table("img_users")->where("username", $username)->where("password", md5($password))->find();
        if ($res !== null) {
            if ($res['status'] == "false") {
                return \json(array("msg" => "您已被禁止登录，请联系管理员解决"), 403);
            }
            $token = md5($username . time()) . $this->randStr(8); //生成token
            //设置 redis 字符串数据
            $uuid = $username . "_" . md5(time()); //用户标识
            $redis->lPush($uuid, $res['group']);
            $redis->lPush($uuid, $token);
            $redis->lPush($uuid, $res['uid']);
            $redis->expire($uuid, 172800); //缓存2天
            return json(array("msg" => "登录成功", "uuid" => $uuid, "token" => $token, "uid" => $res['uid'], "userGroup" => $res['group']), 200);
        } else {
            return json(array("msg" => "用户名或密码错误"), 403);
        }

    }

    public function userCheck(Request $request): Json
    {
        $uuid = $request->param("uuid");
        $token = $request->param("token");
        try {
            $res = $this->privateUserCheck($uuid, $token);
            if ($res["status"] == 200) {
                return json(array("msg" => $res["msg"], "uid" => $res["uid"], "userGroup" => $res['userGroup']), 200);
            } else {
                return json(array("msg" => $res["msg"]), 403);
            }

        } catch (RedisException $e) {
            return json(array("msg" => "Redis服务运行错误，请联系管理员检查"), 500);
        }

    }

    /**
     * @throws RedisException
     */
    private function privateUserCheck($uuid, $token): array
    {
        $redis = $this->initRedis(); //初始化Redis
        //验证用户登录
        if ($redis->exists($uuid) === 1 && $redis->lIndex($uuid, 1) === $token) {
            return array("status" => 200, "msg" => "服务器验证通过", "uid" => $redis->lIndex($uuid, 0), "userGroup" => $redis->lIndex($uuid, 2)); //第一个是uid
        } else {
            return array("status" => 403, "msg" => "服务器验证未通过");
        }
    }

    public function getUserImgList(Request $request)
    {
        //获取用户图片列表
        $uuid = $request->param("uuid");
        $token = $request->param("token");
        $page = $request->param("page");
        $res = $this->privateUserCheck($uuid, $token);
        if ($res["status"] == 200) {
            return json(Db::table("img_allimgs")->where("uid", $res["uid"])->paginate([
                'list_rows' => "30",
                'page' => "$page",
            ]));
        }
        return \json(array("msg" => "用户验证失败"), 403);
    }

    public function getAdminImgList(Request $request)
    {
        //获取所有图片列表（管理员用）
        $uuid = $request->param("uuid");
        $token = $request->param("token");
        $page = $request->param("page");
        $res = $this->privateUserCheck($uuid, $token);
        if ($res["status"] == 200) {
            if ($res['userGroup'] != "admin") {
                return \json(array("msg" => "权限不足"), 403);
            }
            return json(Db::table("img_allimgs")->paginate([
                'list_rows' => "30",
                'page' => "$page",
            ]));
        }
        return \json(array("msg" => "用户验证失败"), 403);
    }

    public function delImage(Request $request)
    {
        //删除图片
        $uuid = $request->param("uuid");
        $token = $request->param("token");
        $filename = $request->param("filename");
        $res = $this->privateUserCheck($uuid, $token);
        if ($res["status"] == 200) {
            $r = Db::table("img_allimgs")->where("filename", $filename)->where("uid", $res['uid'])->find();
            if ($r != null) {
                if (unlink("storage/" . $r["dirname"] . "/" . $r["basename"]) & unlink($r["thumb"] . "/" . $r["basename"])) {
                    Db::table("img_allimgs")->where("filename", $filename)->delete();
                    return \json(array("msg" => "删除成功"));
                }
            } else {
                return \json(array("msg" => "权限不足"), 403);
            }
            return \json(array("msg" => "删除失败"), 500);
        } else {
            return \json(array("msg" => "禁止访问"), 403);
        }

    }

    public function delAdminImage(Request $request)
    {
        //删除图片(管理员用)
        $uuid = $request->param("uuid");
        $token = $request->param("token");
        $filename = $request->param("filename");
        $res = $this->privateUserCheck($uuid, $token);
        if ($res["status"] == 200) {
            if ($res['userGroup'] != "admin") {
                return \json(array("msg" => "权限不足"), 403);
            }
            $r = Db::table("img_allimgs")->where("filename", $filename)->find();
            if (sizeof($r) > 0) {
                if (unlink("storage/" . $r["dirname"] . "/" . $r["basename"]) & unlink($r["thumb"] . "/" . $r["basename"])) {
                    Db::table("img_allimgs")->where("filename", $filename)->delete();
                    return \json(array("msg" => "删除成功"));
                }
            }
            return \json(array("msg" => "删除失败"), 500);
        } else {
            return \json(array("msg" => "禁止访问"), 403);
        }

    }


    /**
     * @throws RedisException
     */
    private function initRedis()
    {
        //连接本地的 Redis 服务
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->ping();
        return $redis;
    }

    private function randStr($n): string
    {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return $randomString;

    }

    public function getServerInfo()
    {
        $userCount = Db::table("img_users")->count();
        $imgCount = Db::table("img_allimgs")->count();
        $dataCount = Db::table("img_allimgs")->sum("size"); //单位B
        return \json(array(
            "userCount" => $userCount,
            "imgCount" => $imgCount,
            "dataCount" => $dataCount
        ));
    }

    public function getUserList(Request $request)
    {
        //获取用户列表
        $uuid = $request->param("uuid");
        $token = $request->param("token");
        $uname = explode("_", $uuid)[0];
        $res = $this->privateUserCheck($uuid, $token);

        if ($res['status'] == 200) {
            if ($res['userGroup'] != "admin") {
                return \json(array("msg" => "权限不足"), 403);
            }
            $users = Db::table("img_users")->select()->toArray();
            $data = array();
            for ($i = 0; $i < sizeof($users); $i++) {
                $uid = $users[$i]['uid'];
                $username = $users[$i]['username'];
                $email = $users[$i]['email'];
                $regdate = $users[$i]['regdate'];
                $group = $users[$i]['group'];
                $status = $users[$i]['status'];
                $userImgCount = Db::table("img_allimgs")->where("uid", $uid)->count();
                $userImgDataCount = Db::table("img_allimgs")->where("uid", $uid)->sum("size");
                $data[$i] = array(
                    "uid" => $uid,
                    "username" => $username,
                    "email" => $email,
                    "regdate" => $regdate,
                    "group" => $group,
                    "userImgCount" => $userImgCount,
                    "userImgDataCount" => $userImgDataCount,
                    "userStatus" => $status
                );
            }

            return \json($data);
        }

        return json(array("msg"=>"错误"));
    }

    public function updateUserInfo(Request $request)
    {
        //更新用户数据
        $uuid = $request->param("uuid");
        $token = $request->param("token");
        $uid = $request->param("uid");
        $userStatus = $request->param("userStatus");
        $res = $this->privateUserCheck($uuid, $token);
        if ($res["status"] == 200 && $res['userGroup'] == "admin") {
            if ($res['uid'] == $uid) {
                return \json(array("msg" => "不能禁用自己"), 500);
            }
            $data = [
                "status" => $userStatus
            ];
            try {
                Db::table("img_users")->where("uid", $uid)->update($data);
            } catch (DbException $e) {
                return \json(array("msg" => "操作失败"), 500);
            }
            if ($userStatus == "true") {
                return \json(array("msg" => "已启用"));
            } else {
                return \json(array("msg" => "已禁用"));
            }
        } else {
            return \json(array("status" => 403, "msg" => "权限不足"), 403);
        }
    }

    public function imgInstallCheck(Request $request): Json
    {
        //安装检查
        $redis = extension_loaded("redis")?"true":"false";

        $php_v = substr(PHP_VERSION,0,3);

        $check = $redis && $php_v>7.2;

        return \json(array(
            "check"=>$check,
            "data"=>array(
                array("name"=>"Redis扩展","status"=>$redis?"true":"false"),
                array("name"=>"PHP版本","status"=>$php_v>7.2?"true":"false")
            )
            ));
    }
    public function imgInstall(Request $request)
    {

        try {
            //文件存在
            fopen(root_path() . "install_lock.txt","r");
            return \json(array("msg"=>"安装文件已经存在如需重新安装，请把tp目录下的install_lock.txt文件删除，并手动重置数据库"),500);
        }catch (ErrorException $err){
            //文件不存在
        }



        $admin_name = $request->param("admin_name");
        $admin_pwd = md5($request->param("admin_pwd"));
        $admin_email = $request->param("admin_email");


        $sql = [
            "SET NAMES utf8mb4;",
            "SET FOREIGN_KEY_CHECKS = 0;",

            "DROP TABLE IF EXISTS `img_allimgs`;",


            "CREATE TABLE `img_allimgs`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL,
  `dirname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '路径',
  `basename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '基础文件名（文件名）',
  `extension` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '格式',
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件名（单纯的文件名）',
  `size` int(20) NULL DEFAULT NULL COMMENT '文件大小',
  `thumb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '缩略图路径',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;",

            "DROP TABLE IF EXISTS `img_users`;",


            "CREATE TABLE `img_users`  (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户名',
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '密码',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户邮箱',
  `regdate` date NULL DEFAULT NULL COMMENT '注册时间',
  `group` set('user','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'user' COMMENT '用户组',
  `status` set('true','false') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'true',
  PRIMARY KEY (`uid`, `username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;",


            "SET FOREIGN_KEY_CHECKS = 1;"

        ];
        $con = @mysqli_connect(env("DATABASE_HOSTNAME"), env("DATABASE_USERNAME"), env("DATABASE_PASSWORD"),env("DATABASE"));
        if ($con){
            foreach ($sql as $item){
                Db::query($item);
            }
            Db::table("img_users")->insert([
                "username"=>$admin_name,
                "password"=>$admin_pwd,
                "email"=>$admin_email,
                "regdate"=>date('Y-m-d H:i:s'),
                "group"=>"admin",
                "status"=>"true"
            ]);
        }else{
            return \json(500,array("msg"=>"操作失败,数据库未创建或其他错误"));
        }

        $lock_file = fopen(root_path() . "install_lock.txt", "w");
        fwrite($lock_file, "安装成功后自动生成，请勿删除!!");
        return \json(array("msg"=>"安装成功"));

    }

    public function getWebInfo() {
        try {
            $redis = $this->initRedis(); //初始化Redis
        } catch (RedisException $e) {
            return json(array("msg" => "Redis服务运行错误，请联系管理员检查"), 500);
        }

        if($redis->exists("web_config") != 1){
            $web_config_file = file_get_contents("../public/web_config.json");
            $redis->set("web_config", $web_config_file);
            $redis->expire("web_config", 172800); //缓存2天
        }

        return \json(json_decode($redis->get("web_config")));



    }

    public function setWebInfo(Request $request){
        try {
            $redis = $this->initRedis(); //初始化Redis
        } catch (RedisException $e) {
            return json(array("msg" => "Redis服务运行错误，请联系管理员检查"), 500);
        }

        $web_name = $request->param("web_name");
        $web_text = $request->param("web_text");
        $web_logo = $request->param("web_logo");
        $web_copy_right = $request->param("web_copy_right");
        $uuid = $request->param("uuid");
        $token = $request->param("token");
        $check = $this->privateUserCheck($uuid,$token);
        if($check['status'] == 200 && $check['userGroup'] == "admin"){
            $data = [
                "web_name" => $web_name,
                "web_text" => $web_text,
                "web_logo" => $web_logo,
                "web_copy_right" => $web_copy_right
            ];

            $web_config_file = fopen("../public/web_config.json","w");
            fwrite($web_config_file, json_encode($data,JSON_UNESCAPED_UNICODE));
            fclose($web_config_file);
            $redis->set('web_config',json_encode($data,JSON_UNESCAPED_UNICODE));
            return json(array("msg" =>"更新成功"));
        }
        return json(array("msg" =>"用户未登录或权限不足"),403);
    }



}
