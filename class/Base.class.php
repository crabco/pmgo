<?php

/**
 * Mongodb操作类,仅支持单例模式
 */
use MongoDB\Driver\Manager;
use MongoDB\Driver\Command;
use MongoDB\Driver\Query;
use MongoDB\Driver\Exception;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\WriteConcern;

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
    public static function baseList(){
        if( is_null(static::$Conn) ){
            static::$Error  = 'Not Open Mongodb!';
            return [];
        }
        if( !$Bson = static::command('admin', ['listDatabases'=>1] ) ){
            return [];
        }
        $Bson->setTypeMap( ['root' => 'array', 'document' => 'array','array'=>'array'] );
        
        return $Bson->toArray();
    }
    
    //删除一个库文件,返回仅表示执行状态，不表示删除状态，如果需要查看是否删除成功，需要再次对比库列表
    public static function baseDrop( $BaseName ){
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
    public static function baseUse( $BaseName ){
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
    public static function userDrop(string $user ){
        $cmd    = ['dropUser'=>$user];
        try{
            if( !$Bson=static::command(static::$DataBase, $cmd) ){
                return false;
            }
        }catch(RuntimeException $e){
            static::$Error  = $e->getMessage();
            return false;
        }
        return true;
    }
    //显示Functions
    public static function funList(){
        if( !$Bson=static::query( static::$DataBase.".system.js",[]) ){
            return [];
        }
        $Bson->setTypeMap( ['root' => 'array', 'document' => 'array','array'=>'array'] );
        return $Bson->toArray();
    }
    
    //添加/修改Functions
    public static function funAdd(){
        return true;
    }
    
    //移除Functions
    public static function funDrop($funName){
        return true;
    }
    
    //显示集合列表
    public static function collList(){
        if( empty(static::$DataBase) ){
            static::$Error  = 'Please choose database';
            return false;
        }
        $cmd    = ['listCollections'=>1];
        try{
            $Bson   = static::command(static::$DataBase, $cmd);
        } catch (Exception $e) {
            static::$Error  = $e->getMessage();
            return false;
        }
        $Bson->setTypeMap( ['root' => 'array', 'document' => 'array','array'=>'array'] );
        return $Bson->toArray();
    }
    //选择一个集合
    public static function collUse( $CollName ){
        if( empty(static::$DataBase) ){
            static::$Error  = 'Please choose database';
            return false;
        }
        if( is_null(static::$me) ){static::$me = new static;}
        static::$Collection = $CollName;
        return static::$me;
    }
    /**
     * 创建一个集合
     * @param string $collName 集合或者视图的名称
     * @param bool $autoIndexId 指定false禁用该_id字段上的索引自动创建 。
     * @param bool $MaxLimit 是否限制集合的数据上限，如果限制，则系统会将超出部分写入记录尾部覆盖过期数据
     * @param int $MaxBeyt 限制表的字节大小限制，优先
     * @param int $MaxSize 限制表的记录总数限制，滞后
     */
    public static function collAdd($collName,$autoIndexId=true,$MaxLimit=false,$MaxBeyt=0,$MaxSize=0){
        if( empty(static::$DataBase) ){
            static::$Error  = 'Please choose database';
            return false;
        }
        $cmd    = ['create'=>$collName,'autoIndexId'=>$autoIndexId];
        if( $MaxLimit==true ){
            $cmd['capped']  = true;
            $cmd['size']    = $MaxBeyt;
            $cmd['max']     = $MaxSize;
        }
        try{
            $Bson=static::command(static::$DataBase, $cmd);
        } catch (RuntimeException $e){
            static::$Error  = $e->getMessage();
            return false;
        }
        return true;
    }
    //修改一个集合名称
    public static function collReName($collName,$collNameNew,$dropTarget=false){
        if( empty(static::$DataBase) ){
            static::$Error  = 'Please choose database';
            return false;
        }
        $cmd    = ['renameCollection'=>static::$DataBase.".".$collName,'to'=>static::$DataBase.".".$collNameNew,'dropTarget'=>$dropTarget];
        try{
            $Bson=static::command('admin', $cmd);
        } catch (RuntimeException $e){
            static::$Error  = $e->getMessage();
            return false;
        }
        return true;
    }
    //删除一个集合
    public static function collDrop($collName=null){
        if( $collName==null ){$collName=static::$Collection;}
        if( empty(static::$DataBase)||empty($collName) ){
            static::$Error  = 'Please choose database Or Collections';
            return false;
        }
        
        $cmd    = ['drop'=>$collName];
        try{
            $Bson=static::command(static::$DataBase, $cmd);
        } catch (RuntimeException $e){
            static::$Error  = $e->getMessage();
            return false;
        }
        return true;
    }
    //清空一个集合
    public static function collClear($collName){
        if( empty(static::$DataBase) ){
            static::$Error  = 'Please choose database';
            return false;
        }
        static::collUse($collName);
        
        $bulk    = new BulkWrite(['ordered' => true]);
        $bulk->delete([]);
        $opt     = new WriteConcern(WriteConcern::MAJORITY, 1000);
        try{
            $Bson = static::bulk($bulk,$opt);
        } catch (RuntimeException $e){
            static::$Error  = $e->getMessage();
            return false;
        }
        return true;
    }
    
    //显示所有索引
    public static function indexesList($collName=null){
        if( $collName==null ){$collName=static::$Collection;}
        if( empty(static::$DataBase)||empty($collName) ){
            static::$Error  = 'Please choose database Or Collections';
            return false;
        }
        $cmd    = ['listIndexes'=>$collName];
        if( !$Bson = static::command(static::$DataBase,$cmd ) ){
            return false;
        }
        $Bson->setTypeMap( ['root' => 'array', 'document' => 'array','array'=>'array'] );
        return $Bson->toArray();
    }
    //创建一个索引
    public static function indexesAdd($collName=null){
        if( $collName==null ){$collName=static::$Collection;}
        if( empty(static::$DataBase)||empty($collName) ){
            static::$Error  = 'Please choose database Or Collections';
            return false;
        }
        
        
    }
    //重建索引
    public static function indexesRe(){
        
    }
    //删除一个索引
    public static function indexesDrop(){
        
    }
    //创建一条数据
    public static function bulkAdd(){
        
    }
    //修改一条数据
    public static function bulkUpdate(){
        
    }
    //删除一条数据
    public static function bulkDelete(){
        
    }
    
    //条件查询数据
    public static function whereQuery(){
        
    }
    //条件删除数据
    public static function whereDelete(){
        
    }
    //条件修改数据
    public static function whereUpdate(){
        
    }
    
    //最终执行命令，批量
    protected static function bulk(BulkWrite $bulk,$opt){
        if( empty(static::$DataBase)||empty(static::$Collection) ){
            static::$Error  = '请选择集合或者库';
            return false;
        }
        try{
            $Bson   = static::$Conn->executeBulkWrite( static::$DataBase.'.'.static::$Collection,$bulk,$opt );
        } catch (BulkWriteException $e){
            static::$Error      = $e->getMessage();
            return false;
        }
        return $Bson;
    }
    
    //最终执行命令
    //MONGODB支持的管理命令请参考 https://docs.mongodb.com/manual/reference/command/nav-administration/
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

