<?php

$photo_rotate = val_rq('photo_rotate',0);
if ( $photo_rotate != va($fu,'photo_rotate') ) { 
    $fu['photo_rotate'] = $photo_rotate;
    $dir_photo_u = "$dir0/w/u_photo/$u";
    $aa = directoryToArray($dir_photo_u, false);
    foreach ($aa as $f)
    if ($f !== 'photo.png')
    {
        $nf = "$dir_photo_u/$f";    
        @unlink($nf);
    }
}


//---------------- begin $bx == 'update'-----------------------------------------------

if (empty($fu['email_notify_confirmcode_out'])) {
    $fu['email_notify_confirmcode_out'] = rand();
    $fu['email_notify_confirmcode_in'] = -1;
}

$fu['nu'] = val_rq('nu');

$en = val_rq('email_notify');
if ($en !== va($fu,'email_notify')) {
    $fu['email_notify'] = $en;
    $fu['email_notify_confirmcode_out'] = rand();
}

$cci = val_rq('email_notify_confirmcode_in');
if ($fu['email_notify_confirmcode_out'] == $cci) {
    $fu['email_notify_confirmed'] = $fu['email_notify'];

    $u = current_iu();
    $r = new db(DB_USER,'update w0_user set mail_u=:mu, pw_u=:pu where u=:u'
            ,[':mu'=>$fu['email_notify_confirmed'],':pu'=>$cci,':u'=>$u]);

    $fu['email_notify_confirmcode_in'] = $cci;
}

$fu['city_u'] = trim(val_rq('city_u'));
$fu['lspeciality'] = val_rq('lspeciality'); // специальности пользователя
$fu['url_photo_u'] = val_rq('url_photo_u');

$fu['nu1'] = val_rq('nu1');
$fu['nu2'] = val_rq('nu2');
$fu['nu3'] = val_rq('nu3');

$fu['gender_u'] = val_rq('gender_u');
$fu['date_birthday'] = val_rq('date_birthday');
$fu['place_birthday'] = val_rq('place_birthday');

$fu['birthday_u'] = val_rq('birthday_u');
$fu['address_u'] = val_rq('address_u');

$fu['contacts_u'] = val_rq('contacts_u');
$fu['info_u'] = val_rq('info_u');
$fu['web_u'] = val_rq('web_u');

//file_put_contents($fu['file'], ini_from_array($fu));
$_SESSION['fu'] = $fu;


set_f_user($fu);
       