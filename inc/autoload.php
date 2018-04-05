<?php
//app根目录
define('AppPathRoot',   substr(dirname(__FILE__),-3));
//app配置目录（本目录需要写权限）
define('AppPathInc',    AppPathRoot."/inc");
//app公开目录
define('AppPathPublic', AppPathRoot."/public/");
//app下载目录(本目录需要写权限)
define('AppPathDown',   AppPathRoot."/public/upload/");
//app的类文件目录
define('AppPathClass',  AppPathRoot."/class/");

require_once (AppPathClass."Response.class.php");

//自动加载部分
spl_autoload_register(function($className){
    
    if( is_file(AppPathClass."{$className}.class.php") ){
        include_once AppPathClass."{$className}.class.php";
    }else{
        Response::error(0,'致命错误！')->html();
    }
    
});
