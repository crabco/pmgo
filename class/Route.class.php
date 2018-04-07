<?php
class Route extends Home{
    
    protected static $me;
    //需要加载的文件路径
    protected static $entity;
    //加载的数据库配置名称
    protected static $base;
    //所有的数据库配置名称
    protected static $baselist  = [];
    //加载的语言
    protected static $language;
    //数据传输方式
    protected static $method;
    //当前主路径地址
    protected static $url_file;
    //当前带路由的访问地址
    protected static $url_route;




    public function __construct() {
        
        $Home   = config::ini()['main'];
        static::$entity         = ( !empty($_GET['e']) )?       $_GET['e'] : "login";
        static::$base           = ( !empty($_GET['b']) )?       $_GET['b'] : "";
        static::$language       = ( !empty($_GET['l']) )?       $_GET['l'] : config::ini()['language'];
        static::$url_file       = $Home;
        
        //加载系统的数据库配置
        if( is_file(AppPathInc."database.php") ){
            include_once AppPathInc."database.php";
            static::$baselist = $database;
            unset($database);
        }
    }
    
    //开始加载Entity文件
    public function load(){
        $Entity = AppPathEntity.static::$entity.".php";
        if( !is_file($Entity) ){
            Response::error(4001,$Entity)->html();
        }
        include_once $Entity;
    }
    
    
    public static function entity(){
        return static::$entity;
    }
    public static function base(){
        return static::$base;
    }
    public static function baselist(){
        return static::$baselist;
    }
    public static function language(){
        return static::$language;
    }
    public static function method(){
        return static::$method;
    }
    public static function url_file(){
        return static::$url_file;
    }
    public static function url_route(){
        return static::$url_route;
    }
    
    public static function page_go( $param ){
        $url    = static::$url_file."?";
        $url   .= ( !isset($param['entity']) )?     "&e=".static::$entity : "&e={$param['entity']}"; 
        $url   .= ( !isset($param['base']) )?       "&b=".static::$base : "&b={$param['base']}"; 
        $url   .= ( !isset($param['language']) )?   "&l=".static::$language : "&l={$param['language']}"; 
        
        unset($param['page'],$param['base'],$param['language']);
        
        if( !empty($param) ){
            foreach($param as $vs=>$rs){
                $url .= "&{$vs}={$rs}";
            }
        }
        return $url;
    }

    

    //通过选择的数据库连接信息打开数据库连接
    public static function load_base(){
        
        //如果数据库已经被连接
        if( Base::ExistsConnection() )return true;
        
        if( empty(static::$baselist) || empty($database[static::$base]) ){
            static::$Error    = 4006;
            return false;
        }
        
        //如果变量为空
        if( empty(static::$base) ){
            static::$Error    = 4008;
            return false;
        }
        
        $BaseInfo   = $database[static::$base];
        $BaseURL    = '';
        $BaseOption = [];
        
        //如果使用套接字,host表示sock文件地址
        if( $BaseInfo['type']=='sock' ){
            $BaseURL    = "mongodb://{$BaseInfo['host']}";
        }
        
        //如果使用普通连接
        if( $BaseInfo['type']=="standalone" ){
            $BaseURL    = "mongodb://";
            $BaseURL   .= ( !empty($BaseInfo['user'])&&!empty($BaseInfo['pass']) )? "{$BaseInfo['user']}:{$BaseInfo['pass']}@" : "";
            $BaseURL   .= "{$BaseInfo['host']}:{$BaseInfo['port']}";
            $BaseURL   .= ( !empty($BaseInfo['base']) )? "/{$BaseInfo['base']}":"";
        }
        
        //如果使用分片服务器
        if( $BaseInfo['type']=="replica" ){
            $BaseURL    = "mongodb://";
            $BaseURL   .= ( !empty($BaseInfo['user'])&&!empty($BaseInfo['pass']) )? "{$BaseInfo['user']}:{$BaseInfo['pass']}@" : "";
            $BaseURL   .= "{$BaseInfo['host']}:{$BaseInfo['port']}";
            $BaseURL   .= ( !empty($BaseInfo['base']) )? "/{$BaseInfo['base']}":"";
            $BaseOption = ['replicaSet'=>$BaseInfo['replica']];
        }
        
        if( !Base::Connection($BaseURL,$BaseOption) ){
            static::$Error  = Base::GetError();
            return false;
        }
        
        return true;
    }

        //开始执行的主入口
    public static function run(){
        if( empty(static::$me) ){static::$me = new static;}
        static::$me->load();
    }
}
