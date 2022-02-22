<?php
namespace app\controller;

use app\BaseController;
use Redis;
use RedisException;
use think\facade\Db;
use app\Request;
use \think\response\Json;

class Index extends BaseController
{

    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V' . \think\facade\App::version() . '<br/><span style="font-size:30px;">14载初心不改 - 你值得信赖的PHP框架</span></p><span style="font-size:25px;">[ V6.0 版本由 <a href="https://www.yisu.com/" target="yisu">亿速云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ee9b1aa918103c4fc"></think>';
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }

    public function upload(Request $request){
        // 获取表单上传文件
        $files = $request->file();
        $uuid = $request->param("uuid");
        $uid = $request->param("uid");
        $token = $request->param("token");

        try {
            validate(['image'=>'fileExt:jpg,png'])
                ->check($files);
            $imgUrl = array();
            foreach($files as $file) {
                $savename = \think\facade\Filesystem::disk('public')->putFile( 'pic', $file);
                if($uuid != null && $this->privateUserCheck($uuid,$token)['status']==200){
                    Db::table("img_allimgs")->insert(['uid'=>$uid,"storage_location"=>$savename]);
                    return json(array("status"=>200,"imgUrl"=>$savename,"msg"=>"用户上传"));
                }
                Db::table("img_allimgs")->insert(['uid'=>"0","storage_location"=>$savename]);
                return json(array("status"=>200,"imgUrl"=>$savename,"msg"=>"游客上传"));
            }
        } catch (\think\exception\ValidateException $e) {
            return $e->getMessage();
        }
    }

    public function userRegistered(Request $request): Json
    {
        //用户注册
        $username = $request->param('username');
        $password = $request->param('pwd');
        $email = $request->param('email');
        if (Db::table("img_users")->where("username",$username)->count()===0){
            $data = [
                'username' => $username,
                'password' => md5($password),
                'email'=>$email,
                'regdate'=>date('Y-m-d H:i:s'),
            ];
            if (Db::table("img_users")->insert($data) === 1){
                return json(array("status"=>200,"msg"=>"注册成功"));
            }
        }
            return json(array("status"=>500,"msg"=>"用户名已存在"));
    }
    public function userLogin(Request $request): Json
    {

        try {
            $redis = $this->initRedis(); //初始化Redis
        } catch (RedisException $e) {
            return json(array("status"=>500,"msg"=>"Redis服务运行错误，请联系管理员检查"));
        }
        if($redis)
        $username = $request->param('username');
        $password = $request->param('pwd');
        $res = Db::table("img_users")->where("username",$username)->where("password",md5($password))->find();
        if($res!=null){
            $token = md5($username . time()). $this->randStr(8); //生成token
            //设置 redis 字符串数据
            $uuid = $username . "_" . md5(time()); //用户标识
            $redis->set($uuid,$token);
            $redis->expire("$uuid",60); //测试用10秒
            return json(array("status"=>200,"msg"=>"登录成功","uuid"=>$uuid,"token"=>$token,"uid"=>$res['uid']));
        }else{
            return json(array("status"=>403,"msg"=>"用户名或密码错误"));
        }
    }

    public function userCheck(Request $request): Json
    {
        $uuid = $request->param("uuid");
        $token = $request->param("token");
        try {
            return json($this->privateUserCheck($uuid, $token));
        } catch (RedisException $e) {
            return json(array("status"=>500,"msg"=>"Redis服务运行错误，请联系管理员检查"));
        }

    }

    /**
     * @throws RedisException
     */
    private function privateUserCheck($uuid,$token): array
    {

        $redis = $this->initRedis(); //初始化Redis
        //验证用户登录
        if ($redis->exists($uuid) === 1 && $redis->get($uuid) === $token){
            return array("status"=>200,"msg"=>"服务器验证通过");
        }else{
            return array("status"=>403,"msg"=>"服务器验证未通过");
        }
    }

    /**
     * @throws RedisException
     */
    private function initRedis(){
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

}
