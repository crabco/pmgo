<?php
class Base{
    
    protected $Err;
    protected static $Error;
    protected static $DataBase;
    
    
    public static function GetError(){
        return static::$Error;
    }
    
    
    //检测是否安装了PHP的MONGODB插件
    public static function ExistsMongo(){
        return class_exists('Mongodb\Driver\Manager',false);
    }
    
    //是否已经打开数据库
    public static function ExistsConnection(){
        return ( empty(self::$DataBase) )? false : true;
    }
    
    
    //连接数据库
    public static function Connection( $URL,$Option=array() ){
        
        if( !static::ExistsMongo() ){
            static::$Error  = 'Not Mongodb Driver';
            return false;
        }
        
        try{
            static::$DataBase      = new Mongodb\Driver\Manager($URL,$Option);
            print_r(static::$DataBase);
        }catch (\MongoDB\Driver\Exception $e) {
            static::$DataBase   = null;
            static::$Error      = $e->getMessage();
            return false;
        }
        return true;
    }
    
    //显示所有库列表
    public static function ShowDataBase(){
        if( !static::ExistsConnection() ){
            static::$Error  = 'Not Open Mongodb!';
            return false;
        }
        $filter     = ['api_total' => ['$gt' => 0]];
        $options    = [
            'projection' => ['_id' => 0],
            'sort' => ['_id' => -1],
        ];
        
        // 查询数据
        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = static::$DataBase->executeQuery('leancloud.kc_api_log', $query);
//
//        foreach ($cursor as $document) {
//            print_r($document);
//        }
    }
    
    
    
    
}

