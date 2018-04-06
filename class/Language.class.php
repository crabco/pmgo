<?php
class Language extends Home{
    
    protected static $language;
    
    public static function get($code){
        if( empty(static::$language) ){
            $File   = AppPathLanguage.Route::language().".php";
            if( !is_file($File) ){ $File = AppPathLanguage."cn.php"; }
            
            if( !is_file($File) ){
                header("status: 400");
                echo json_encode(['status'=>false,'error'=>'语言文件加载失败']);
                exit;
            }
            
            include_once $File;
            static::$language = $language;
            unset($language);
        }
        
        return ( isset(static::$language[$code]) )? static::$language[$code] : $code;
    }
}
