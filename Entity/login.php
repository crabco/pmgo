<?php
if( Route::load_base()===false ){
    Response::debug('连接数据库失败:'.Language::get( Route::GetError() ))->html();
}
Base::baseUse('kcloud')->collUse('kc_test');
Response::debug( ['index'=>Base::indexesList()] );
Response::debug( ['BaseErr'=>Base::GetError()] );
Response::success([])->html();