<?php
if( !empty($_POST) ){
    $username   = $_POST['username'];
    $password   = $_POST['password'];
    
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
            <div id="login_input"><input id="username" name="username" type="text" /></div>
        </div>
        <div id="login_tr">
            <div id="login_name"><?php echo Language::get('pass');?></div>
            <div id="login_input"><input id="password" name="password" type="password" /></div>
        </div>
        <div id="login_tr">
            <button type="submit" id="login_submit"><?php echo Language::get('login')?></button>
        </div>
        <div id="login_line"></div>
        <div id="login_exp" title="<?php echo Language::get('loginexp');?>">
            <span id="notice"><?php echo Language::get('notice');?></span>
            <?php echo Language::get('loginexp');?>
        </div>
    </div>
    </form>
</body>
</html>