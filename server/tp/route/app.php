<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('hello/:name', 'index/hello');
Route::post('upload', 'index/upload');//上传图片
Route::post('userRegistered', 'index/userRegistered');//用户登录验证
Route::post('userLogin', 'index/userLogin');//用户登录
Route::post('userCheck', 'index/userCheck');//uuid验证
Route::post('getUserImgList', 'index/getUserImgList');//获取用户文件列表
Route::post('MakeThumbnail', 'index/MakeThumbnail');
Route::post('delImage', 'index/delImage');//删除图片
Route::get('getServerInfo', 'index/getServerInfo');//获取存储信息
Route::post('getUserList', 'index/getUserList');//获取用户列表
Route::post('updateUserInfo', 'index/updateUserInfo'); //更新用户信息
Route::post('getAdminImgList', 'index/getAdminImgList');//获取所有图片列表（管理员用）
Route::post('delAdminImage', 'index/delAdminImage');//删除图片（管理员用）
Route::post('imgInstall', 'index/imgInstall');//删除图片（管理员用）

