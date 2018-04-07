<?php
if( empty(Route::entity()) || empty($_SESSION['user']) ){
    header("Location: ".Route::url_file()."?login=logintime");exit;
}