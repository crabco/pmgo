<?php
if( Route::load_base()===false ){
    Response::debug('连接数据库失败:'.Language::get( Route::GetError() ))->html();
}
Response::debug(Base::useBase('admin')->userList());

Response::debug( ['createuser'=>Base::userAdd('adm', 'adm',[])] );

Response::success([])->html();