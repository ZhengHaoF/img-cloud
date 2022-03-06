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
Route::post('upload', 'index/upload');
Route::post('userRegistered', 'index/userRegistered');
Route::post('userLogin', 'index/userLogin');//用户登录
Route::post('userCheck', 'index/userCheck');//uuid验证
Route::post('getUserImgList', 'index/getUserImgList');//获取用户文件列表
Route::post('MakeThumbnail', 'index/MakeThumbnail');
Route::post('delImage', 'index/delImage');
