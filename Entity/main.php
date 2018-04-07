<?php
require_once AppPathEntity.'enpty.php';

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
    <div id="bar">
        <div id="language">
            <div class="top_button" style="float: right;"><?php echo Language::get('language')?></div>
            <div class="top_button" style="float: right;"><?php echo Language::get('settings')?></div>
        </div>
        <div id="server_list">
            <div id="server">PMGO</div>
            <div class="top_button top_buttons" onclick=""><?php echo Language::get('serveradd')?></div>
            <div class="top_button top_buttons" onclick=""><?php echo Language::get('export')?></div>
            <div class="top_button top_buttons" onclick=""><?php echo Language::get('import')?></div>
        </div>
    </div>
    <div class="width_line"></div>
    
    <div id="base_list">
        <?php
        $base_list    = Route::baselist();
        if(!empty($base_list)){
            foreach($base_list as $vs=>$rs){
        ?>
            <div class="base_sev">
            <div class="base_names">
                <div class="base_name icos" id="<?php echo $vs?>" onclick="menu(this)"><?php echo $rs['name']?></div>
                <div class="ico_re"></div>
            </div>
            </div>
        <?php
            }
        }
        ?>
        <div class="base_sev">
            <div class="base_names">
                <div class="base_name icos" id="s_1" onclick="menu(this)">服务器1</div>
                <div class="ico_re"></div>
            </div>
            <div class="base_tr" id="tr_s_1" style="display:none;">
                <div class="base_names">
                    <div class="base_name icob" id="b_1" onclick="menu(this)">admin</div>
                    <div class="ico_re"></div>
                </div>
                <div class="base_tr" id="tr_b_1" style="display:none;">
                    <div class="base_names">
                        <div class="base_name icoc" id="c_1" onclick="menu(this)">集合(20)</div>
                        <div class="ico_re"></div>
                    </div>
                    <div class="base_tr" id="tr_c_1" style="display:none;">
                        <div class="base_td icoc1">kc_admin(20)</div>
                        <div class="base_td icoc1">kc_admin</div>
                        <div class="base_td icoc1">kc_admin</div>
                    </div>
                    <div class="base_names">
                        <div class="base_name icof" id="f_1" onclick="menu(this)">函数(0)</div>
                        <div class="ico_re"></div>
                    </div>
                    <div class="base_tr" id="tr_f_1" style="display:none;">
                        <div class="base_td icof1">kc_admin</div>
                        <div class="base_td icof1">kc_admin</div>
                        <div class="base_td icof1">kc_admin</div>
                    </div>
                    <div class="base_names">
                        <div class="base_name icov" id="v_1" onclick="menu(this)">视图(0)</div>
                        <div class="ico_re"></div>
                    </div>
                    <div class="base_tr" id="tr_v_1" style="display:none;">
                        <div class="base_td icov1">kc_admin</div>
                        <div class="base_td icov1">kc_admin</div>
                        <div class="base_td icov1">kc_admin</div>
                    </div>
                    <div class="base_names">
                        <div class="base_name icou" id="u_1" onclick="menu(this)">用户(30)</div>
                    <div class="ico_re"></div>
                    </div>
                    <div class="base_tr" id="tr_u_1" style="display:none;">
                        <div class="base_td icou1">kc_admin</div>
                        <div class="base_td icou1">kc_admin</div>
                        <div class="base_td icou1">kc_admin</div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <div id="coll_list">
        
    </div>
</body>
</html>