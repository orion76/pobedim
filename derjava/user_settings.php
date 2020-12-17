<?php

$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);

require_once $dir0 . '/ut.php';
require_once $dir0 . '/html.php';
require_once $dir0 . '/derjava/fn.php';
require_once $dir0 . '/derjava/user_fn.php';

require_once $dir0 . '/thumb.php';
require_once $dir0 . '/mailer.php';
require_once $dir0 . '/derjava/wall_fn.php';

$ax = val_rq('ax', '');
$bx = val_rq('bx','lk');
$rx = '';

$ix = val_rq('ix',''); // info on header

$u = current_iu();
$fu = $_SESSION['fu'];


if ($ax === 'set_photo') {
    $fu['url_photo_u'] = val_rq('f');
    $fu['url_photo_nu'] = '';
    $fu['url_photo_n'] = '';
    $fu['photo_u'] = '';
    $fu['photo_rotate'] = 0;
    $ax = '';
    $bx = '';
    $_SESSION['fu'] = $fu;
    
    $dir_photo_u = "$dir0/w/u_photo/$u";
    $aa = directoryToArray($dir_photo_u, false);
    foreach ($aa as $f){
        $nf = "$dir_photo_u/$f";    
        @unlink($nf);
    }
    set_f_user($fu);
    echo htm_redirect1('/derjava/user_settings.php');
    exit;
}

if ($ax === 'send_confirmcode') {
    $nm = $fu['email_notify'];
    if (strpos($nm, '@') === false) {
        echo 'Ошибка в адресе почты [' . $nm . '], нет символа "@", операция не выполнена';
    } else {

        $m = new Mailer();
        $m->init();
        $r = $m->Send_utf8('confirm mail', 'код подтверждения (confirm-code): ' . $fu['email_notify_confirmcode_out'], $fu['email_notify']);
        unset($m);
        echo 'Проверьте почту ' . $fu['email_notify'] . ', включая папку "spam".';
    }
    exit;
}


if ($ax === 'update') {
    include_once $dir0 . '/derjava/user_settings_update_include.php';
    echo htm_redirect1('/derjava/user_settings.php');
    exit;
}


if ( va_empty( $fu,'nu1') 
        ||va_empty($fu,'nu3')
        ||va_empty($fu,'date_birthday') 
        ||va_empty($fu,'place_birthday')
        ||va_empty($fu,'email_notify_confirmed') ) {
    $ix = 'Рекомендуем заполнить важные данные для работы системы голосований';
}
   
    

$ht = new html('/derjava/user_settings.html');
$menu= menu_user($ht, '');



$r0 = new db(DB_POBEDIM, 'select * from v2_user u where u.u=:u ',[':u'=>$u]
        ,function($row,$pp){
            $r = new db(DB_POBEDIM, 'select * from w2_user_s3(0,:u) ',$pp);
            $row['CNT_VECHE1'] = $r->row('CNT_VECHE1');
            $row['TS_UPDATED'] = $r->row('TS_UPDATED');
            $row['CITY_URLENCODED'] = urlencode($row['CITY_U']);
            user_geo_get($row);
            return $row;
        }
        );


$data0 = $r0->rows;

$data0[0]['PHOTO_ROTATE'] = va($fu,'photo_rotate',0);

if (empty($r0->row('NAME1_CERTIFICATE'))) $data0[0]['NAME1_CERTIFICATE'] = va($fu,'nu1');
if (empty($r0->row('NAME2_CERTIFICATE'))) $data0[0]['NAME2_CERTIFICATE'] = va($fu,'nu2');
if (empty($r0->row('NAME3_CERTIFICATE'))) $data0[0]['NAME3_CERTIFICATE'] = va($fu,'nu3');
if (empty($r0->row('BIRTHDAY_U'))) $data0[0]['BIRTHDAY_U'] = va($fu,'birthday_u');

if (!empty($fu['birthday_u']) && empty($fu['date_birthday']) ) 
{ 
    if (  !empty(fmtDDMMYYYY( va($fu,'birthday_u'))) ) { $fu["date_birthday"] = fmtDDMMYYYY($fu['birthday_u']); }
}

if (!empty($r0->row('SEX_CERTIFICATE'))) $fu['gender_u'] = $r0->row('SEX_CERTIFICATE'); 

$g = va($fu,'gender_u');
if ( $g=='m') {$data0[0]['S_MALE'] = 'selected="selected"';} else {$data0[0]['S_MALE'] = '';}
if ( $g=='f') {$data0[0]['S_FEMALE'] = 'selected="selected"';}else {$data0[0]['S_FEMALE'] = '';}


if (empty($r0->row('CITY_U'))) $data0[0]['CITY_U'] = va($fu,'city_u');

if (empty($data0[0]['CITY_U'])) $data0[0]['CITY_U'] = $r0->row('CITY_HOST');


$data0[0]['IMGSRC_USER'] = tag_user_imgsrc($u, $fu);

if (empty($r0->row('LIST_SPECIALITY_U')))  $data0[0]['LIST_SPECIALITY_U']=va($fu,'lspeciality');
if (empty($r0->row('D_BIRTH_CERTIFICATE')))  $data0[0]['D_BIRTH_CERTIFICATE']=va($fu,'date_birthday');
if (empty($r0->row('PLACE_BIRTH_CERTIFICATE')))  $data0[0]['PLACE_BIRTH_CERTIFICATE']=va($fu,'place_birthday');


if (empty($r0->row('CONTACT_CERTIFICATE')))  $data0[0]['CONTACT_CERTIFICATE']=va($fu,'contacts_u');
$data0[0]['INFO_U']=va($fu,'info_u');
$data0[0]['WEB_U']=va($fu,'web_u');

$data0[0]['VISIBLE_MAIL_NOTIFY'] = null;
$data0[0]['VISIBLE_MAIL_CONFIRM'] = null;

$data0[0]['EMAIL_NOTIFY'] = va($fu,'email_notify');
$data0[0]['EMAIL_NOTIFY0']='текущий ящик: '.$r0->row('EMAIL_CERTIFICATE');

if (!empty(va($fu,'email_notify'))) {
    if (va($fu,'email_notify_confirmed') !== va($fu,'email_notify')) {
        $data0[0]['VISIBLE_MAIL_NOTIFY'] = 1;
        if (va($fu,'email_notify_confirmcode_in') !== va($fu,'email_notify_confirmcode_out')){
          $data0[0]['VISIBLE_MAIL_CONFIRM'] = 1;
        }
    } else {
        $data0[0]['EMAIL_NOTIFY']=$r0->row('EMAIL_CERTIFICATE');
        $data0[0]['EMAIL_NOTIFY0']='';
    }
}
   
    
$r31 = new db(DB_POBEDIM,'select * from W2_USER_KIND_L(:u)',[':u'=>$u]
            ,function($row){ 
                $row['CAN_EDIT_KIND_USER'] = iif($row['ID_KIND_USER'] > 20 && $row['ID_KIND_USER'] <80,1,null);
                $row['CHECKED_DO_IGNORE_POLL'] = iif($row['DO_IGNORE_POLL'] == '1', ' checked ');
                $row['CHECKED_DO_IGNORE_CAST'] = iif($row['DO_IGNORE_CAST'] == '1', ' checked ');
                $row['CHECKED_DO_DISABLE_MSG'] = iif($row['DO_DISABLE_MSG'] == '1', ' checked ');
                return $row;
            });
$ht->data('data31', $r31->rows);


//-------------------- WALL ---------------------------------------    
    
$m = val_rq('m');
$r8 = null;
$r8 =  getWall5($data0, $u, $u, '0', '0','/derjava/user_settings.php?u='.$u , null, null,$m, null ,'/derjava/user_settings.php?u='.$u, val_rq('sxw') );
$ht->data('data8', $r8);
    

$ht->data('data0', $data0);
echo $ht->build('', true);
