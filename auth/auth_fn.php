<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once( $dir0 . '/ut.php');

function login_social_nw($u_social_nw, $user_info){

$sui = $user_info[$u_social_nw];
if (empty($sui)) return '';

$cf = get_f_user($sui,$u_social_nw);
$is_new = ($cf === false);    
if ($is_new) {
    $cf = $user_info;
    gen_iu($cf);
    $cf[$u_social_nw] = $sui;
}

    
switch($u_social_nw){
    case 'user_facebook':
        $cf['user_facebook'] = $user_info["id"];
        $cf['url_picture_u'] = $user_info['picture']['data']['url'];
        $cf['w_picture_u'] = $user_info['picture']['data']['width'];
        $cf['h_picture_u'] = $user_info['picture']['data']['height'];
        $cf['url_picture_u'] = $user_info['picture']['data']['url'];
        $cf['url_photo_nu'] = $user_info['picture']['data']['url'];
        $cf['w_picture_u'] = $user_info['picture']['data']['width'];
        $cf['h_picture_u'] = $user_info['picture']['data']['height'];
        $cf['nu1'] = va($user_info,"first_name"); 
        $cf['nu2'] = va($user_info,"second_name"); 
        $cf['nu3'] = va($user_info,"last_name"); 
        $cf['nu'] = va($user_info,"last_name") . ' ' . va($user_info,"first_name");
        unset($r);$r = new db(DB_USER, 'update or insert into W0_U_LOGIN_FB(u,LOGIN_FB_U,TEXT_LOGIN) values (:u,:s,:t)'
                                        , [':u'=> $cf['iu'], ':s'=>$sui,':t'=>ini_from_array($cf)] );    
        break;    
    
    case 'user_vkontakte':
        $cf['user_vkontakte'] = $user_info["user_id"]; 
        $cf['nu'] = va($cf,"last_name") . ' ' .  va($cf,"first_name");
        $cf['url_photo_nu'] =  va($cf,'photo');
  
        if (empty(va($cf,'nu1'))) { $cf['nu1'] = va($cf,"first_name"); }
        if (empty(va($cf,'nu3'))) { $cf['nu3'] = va($cf,"last_name"); }

        unset($r); $r = new db(DB_USER, 'update or insert into W0_U_LOGIN_VK(u,LOGIN_VK_U,TEXT_LOGIN) values (:u,:s,:t)'
                    , [':u'=> $cf['iu'], ':s'=>$sui,':t'=>ini_from_array($cf)] );    
        break;
        
        
    case "user_odnoklassniki":
        if (empty(va($cf,'nu1'))) $cf['nu1'] = va($cf,"first_name"); 
        if (empty(va($cf,'nu3'))) $cf['nu3'] = va($cf,"last_name"); 
        if (empty(va($cf,'birthday_u'))) $cf['birthday_u'] = va($cf,"birthday"); 
        if (empty(va($cf,'gender_u'))) $cf['gender_u'] = va($cf,"gender"); 
        $cf['nu'] = va($cf,"last_name") . ' ' . va($cf,"first_name");
        $cf['url_photo_nu'] =  va($cf,'pic_3');
        unset($r);  $r = new db(DB_USER, 'update or insert into W0_U_LOGIN_OK(u,LOGIN_OK_U,TEXT_LOGIN) values (:u,:s,:t)'
                    , [':u'=> $cf['iu'], ':s'=>$sui,':t'=>ini_from_array($cf)] );    
            
        break;        
        
}
    
    $nf = 'c:/tmp/u/' . str_replace(':', '-', $_SERVER['REMOTE_ADDR']) . '.ini';
    $xf = file_ini_read($nf);
    $cf['base_url'] = $xf['base_url'];
    $cf['rx'] = $xf['rx'];

    logOn($cf);

    $url = f_host( $cf['rx'] );
    $body = '';

    return '<html><head><meta http-equiv="refresh" content="0;url=' . $url . '"></head><body>' . $body . '</body></html>';
}