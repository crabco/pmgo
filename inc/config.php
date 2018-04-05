<?php
class config{
    //登录管理系统的帐号密码
    public static function ini(){
        return [
            'username'=>'admin',        //登录帐号
            'password'=>'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec',       //登录密码
            'language'=>'cn',           //程序默认语言,当然，用户可以重新选择
            'main'=>'/default.php'      //程序入口文件,如果是支持rewrite则直接写本程序的跟路径，例如 /pmgo
            ];
    }
}