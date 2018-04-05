<?php

/**
 * Mongodb操作类,仅支持单例模式
 */
use MongoDB\Driver\Command;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Exception;

class Base{
    protected static $me;
    protected static $Error;
    protected static $DataBase;
    
    
    //检测是否安装了PHP的MONGODB插件
    public static function ExistsMongo(){
        return class_exists('Mongodb\Driver\Manager',false);
    }
    
    public static function ExistsConnection(){
        return !is_null(static::$DataBase);
    }

    //连接数据库
    public static function Connection( $URL,$Option=array() ){
        
        if( !static::ExistsMongo() ){
            return false;
        }
        
        try{
            static::$DataBase       = new Manager($URL,$Option);
        }catch(Exception $e) {
            static::$DataBase       = null;
            static::$Error          = $e->getMessage();
            return false;
        }
        
        return true;
    }
    
    //显示所有库列表
    public static function listDtatBase(){
        if( is_null(static::$DataBase) ){
            static::$Error  = 'Not Open Mongodb!';
            return [];
        }
        
        $Comm   = new Command( ['listDatabases'=>1] );
        $Bson   = static::$DataBase->executeCommand('admin',$Comm);
        $Bson->setTypeMap( ['root' => 'array', 'document' => 'array','array'=>'array'] );
        return $Bson->toArray()[0]['databases'];
        
    }
    
    //删除一个库文件
    public static function dorpDataBase( $BaseName ){
        return false;
    }
    
    //选择一个库
    public static function useBase( $BaseName ){
        if( is_null(static::$me) ){static::$me = new static;}
        
        return static::$me;
    }
    
    //显示当前库的用户
    public static function userList(){
        
        return [];
    }
    
    //添加/修改用户
    public static function userAdd(){
        return false;
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
    
    public static function GetError(){
        return static::$Error;
    }
}

