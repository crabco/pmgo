<?php

/**
 * Mongodb操作类,仅支持单例模式
 */
use MongoDB\Driver\Manager;
use MongoDB\Driver\Command;
use MongoDB\Driver\Query;
use MongoDB\Driver\Exception;

class Base{
    protected static $me;
    protected static $Error;
    protected static $Conn;
    protected static $DataBase;
    protected static $Collection;

    

    //检测是否安装了PHP的MONGODB插件
    public static function ExistsMongo(){
        return class_exists('Mongodb\Driver\Manager',false);
    }
    
    public static function ExistsConnection(){
        return !is_null(static::$Conn);
    }

    //连接数据库
    public static function Connection( $URL,$Option=array() ){
        if( !static::ExistsMongo() ){
            return false;
        }
        try{
            static::$Conn       = new Manager($URL,$Option);
        }catch(Exception $e) {
            static::$Conn       = null;
            static::$Error          = $e->getMessage();
            return false;
        }
        return true;
    }
    
    //显示所有库列表
    public static function listDtatBase(){
        if( is_null(static::$Conn) ){
            static::$Error  = 'Not Open Mongodb!';
            return [];
        }
        
        if( !$Bson = static::execute('admin', ['listDatabases'=>1] ) ){
            return [];
        }
        $Bson->setTypeMap( ['root' => 'array', 'document' => 'array','array'=>'array'] );
        
        return $Bson->toArray();
    }
    
    //删除一个库文件,返回仅表示执行状态，不表示删除状态，如果需要查看是否删除成功，需要再次对比库列表
    public static function dorpDataBase( $BaseName ){
        if( in_array($BaseName,['admin','local']) ){
            static::$Error  = 'Not allowed to be deleted.';
            return false;
        }
        if( !$Bson=static::command($BaseName, ['dropDatabase'=>1]) ){
            return false;
        }else{
            return true;
        }
    }
    
    //选择一个库
    public static function useBase( $BaseName ){
        if( is_null(static::$me) ){static::$me = new static;}
        static::$DataBase   = $BaseName;
        return static::$me;
    }
    
    //显示当前库的用户
    public static function userList(){
        if( !$Bson=static::query( static::$DataBase.".system.users",[]) ){
            return [];
        }
        $Bson->setTypeMap( ['root' => 'array', 'document' => 'array','array'=>'array'] );
        return $Bson->toArray();
    }
    
    //添加/修改用户
    public static function userAdd(string $user,string $pass,array $roles){
        $User   = ['createUser'=>$user,'pwd'=>$pass,'roles'=>$roles];
        
        
        try{
            if( !$Bson=static::command(static::$DataBase, $User) ){
                return false;
            }
        }catch(RuntimeException $e){
            static::$Error  = $e->getMessage();
            return false;
        }
        return true;
    }
    
    //移除用户
    public static function userDorp(){
        
    }
    //显示Functions
    //添加/修改Functions
    //移除Functions
    
    
    //选择一个集合
    public static function useCollections( $CollName ){
        if( is_null(static::$me) ){static::$me = new static;}
        static::$Collection = $CollName;
        return static::$me;
    }
    //创建一个集合
    //修改一个集合
    //删除一个集合
    //清空一个集合
    
    //显示所有索引
    //创建一个索引
    //修改一个索引
    //删除一个索引
    
    //创建一条数据
    //修改一条数据
    //删除一条数据
    
    //条件查询数据
    //条件删除数据
    //条件修改数据
    
    //导出数据（根据传入参数自动分割文件）
    
    //导入数据（根据文件名自动批量导入）
    
    
    //最终执行命令
    protected static function command(string $db,array $cmd){
        try{
            $Bson               = static::$Conn->executeCommand($db,new Command( $cmd ));
        }catch(Exception $e) {
            static::$Error      = $e->getMessage();
            return false;
        }
        return $Bson;
    }
    //最终执行查询
    protected static function query(string $db,array $cmd){
        try{
            $Bson               = static::$Conn->executeQuery($db,new Query( $cmd ));
        }catch(Exception $e) {
            static::$Error      = $e->getMessage();
            return false;
        }
        return $Bson;
    }

    public static function GetError(){
        return static::$Error;
    }
}

