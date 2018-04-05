<?php
class Route extends Home{
    
    protected static $me;
    
    //需要加载的文件路径
    protected static $entity;
    //加载的数据库配置名称
    protected static $base;
    //加载的语言
    protected static $language;
    //数据传输方式
    protected static $method;
    
    
    public function __construct() {
        
        $Home   = config::ini()['main'];
        if( substr($_SERVER['PHP_SELF'],0,strlen($Home))!=$Home ){
            header("Location: {$Home}");
            exit;
        }
        
        $Path                    = explode("/",substr($_SERVER['PHP_SELF'],strlen($Home)));
        static::$entity         = ( !empty($Path[1]) )? $Path[1] : "login";
        static::$base           = ( !empty($Path[2]) )? $Path[2] : "default";
        static::$language       = ( !empty($Path[3]) )? $Path[3] : config::ini()['language'];
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
    public static function language(){
        return static::$language;
    }
    public static function method(){
        return static::$method;
    }
    
    
    //通过选择的数据库连接信息打开数据库连接
    public static function load_base(){
        
        //如果数据库已经被连接
        if( Base::ExistsConnection() )return true;
        
        if( !is_file(AppPathInc."database.php") ){
            static::$Error    = 4006;
            return false;
        }
        
        //如果变量为空
        if( empty(static::$base) ){
            static::$Error    = 4008;
            return false;
        }
        
        include_once AppPathInc."database.php";
        
        if( empty($database) || empty($database[static::$base]) ){
            static::$Error    = 4006;
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
