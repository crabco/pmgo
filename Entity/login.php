<?php
phpinfo();
if( Route::load_base()===false ){
    Response::debug('连接数据库失败:'.Language::get( Route::GetError() ))->html();
}