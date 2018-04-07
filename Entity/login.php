<?php
$LoginOff   = ( Base::ExistsMongo() )? false : true;

if( !empty($_POST) && $LoginOff==false ){
    $username   = md5($_POST['username']);
    $password   = hash('sha512', $_POST['password']);
    $User       = config::ini();
    if( $username!=md5($User['username'])||$password!=$User['password'] ){
        header("Location: ". Route::page_go( ["login"=>"loginerr"] ) );
        exit;
    }else{
        $_SESSION['user']       = $User['username'];
        $_SESSION['language']   = Route::language();
        header("Location: ".Route::page_go(['entity'=>'main']));exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>PMGO</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href='upload/pmgo.css' rel='stylesheet' type='text/css' />
<script type="text/javascript" src="upload/jquery.min.js"></script>
<script type="text/javascript" src="upload/pmgo.js"></script>
</head>
<body>
    <form name="login" method="post" action="">
    <div id="login_tab">
        <div id="login_title"><?php echo Language::get('nologin');?></div>
        <div id="login_tr">
            <div id="login_name"><?php echo Language::get('user');?></div>
            <div id="login_input"><input id="username" name="username" type="text" <?php if($LoginOff){echo 'disabled';}?> /></div>
        </div>
        <div id="login_tr">
            <div id="login_name"><?php echo Language::get('pass');?></div>
            <div id="login_input"><input id="password" name="password" type="password" <?php if($LoginOff){echo 'disabled';}?> /></div>
        </div>
        <div id="login_tr">
            <button type="submit" id="login_submit" <?php if($LoginOff){echo 'disabled';}?>><?php echo Language::get('login')?></button>
        </div>
        <div class="width_line"></div>
        <div id="login_exp" title="<?php echo Language::get('loginexp');?>">
            <span id="notice"><?php echo Language::get('notice');?></span>
            <?php
            if( !empty($_GET['login']) ){
                echo Language::get($_GET['login']);
            }elseif( !$LoginOff ){
                echo Language::get('loginexp');
            }else{
                echo Language::get('notmongodb');
            }
            ?>
        </div>
    </div>
    </form>
</body>
</html>