<?php



if ($bx === 'create_certificate') {
    $tc = val_rq('tc');
    $yb = val_rq('yb', null);
    $row0 = db_row('select * from W2_CERTIFICATE_VOTE_I2(:iu ,:yb , :tc)'
            , [':iu' => $iu, ':yb' => $yb, ':tc' => $tc], DB_POBEDIM);
    echo htm_redirect1('/auth/user.php?ax=' . $ax . '&bx=0&ic=' . $row0['ID_CERTIFICATE'] . '&yb=' . $yb);
    exit;
}




if ($bx === 'upd_vote') {

    $f = '';
    $v = str_replace(' ', ' ', val_rq('v'));
    switch (val_rq('n')) {
        case 't': $f = 'TEXT_VOTE';
            break;
        //case 'l': $f = 'LEVEL_VOTE'; break;
        case 'c': $f = 'ID_CERTIFICATE';
            if (empty($v)) {
                $v = null;
            } else {
                if (!is_integer($v)) {
                    echo 'ERR: Ошибка в номере сертификата, допустимы только цифры';
                    exit;
                }
            }
            break;
        //case 'e': $f = 'EMAIL_VOTE'; break;
    }

    if ($f === 'TEXT_VOTE') {
        $r = db_row(//'update v2_vote set '.$f.'=:v  where ID_VOTE=:iv'
                'update v2_vote set ' . $f . '=:v  where ID_VOTE=:iv'
                , [':v' => $v, ':iv' => val_rq('iv')], DB_POBEDIM);
    }
    echo $r['ERR'];

    exit;
}


/*

$date_pf = va($fu,'date_pf'); // дата последнего изменнения пользовательского профиля
if (empty($date_pf)) {
    $date_pf = 0;
}

if ($date_pf < '20190101') {
    $fu['date_pf'] = date('Ymd');
    $_SESSION['fu']['date_pf'] = $fu['date_pf'];
    file_put_contents($fu['file'], ini_from_array($fu));
    unset($_SESSION['rx_pf']);
}

*/


/*

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
*/



//---------------- begin $bx == 'update'-----------------------------------------------
if ($ax === 'basic' && $bx === 'update') {

    if (empty($fu['email_notify_confirmcode_out'])) {
        $fu['email_notify_confirmcode_out'] = rand();
        $fu['email_notify_confirmcode_in'] = -1;
    }
    $fu['nu'] = val_rq('nu');

    if (strpos($fu['nu'], 'user') !== false) {
        $s = trim($fu['last_name'] . ' ' . $fu['first_name']);
        if (!empty($s)) {
            $fu['nu'] = $s;
        }
    }

    $en = val_rq('email_notify');
    if ($en !== $fu['email_notify']) {
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


    $lspeciality = val_rq('lspeciality'); // специальности пользователя
    $fu['lspeciality'] = $lspeciality;

    unset($r);
    $r = db_row('update or insert into w2_user (u, name_u,  LIST_SPECIALITY_U)'
            . ' values (:iu,:nu,:ls)'
            , [':iu' => $iu, ':nu' => $fu['nu']
        , ':mu' => $fu['email_notify_confirmed']
        , ':ls' => $lspeciality
            ]
            , DB_POBEDIM);
    unset($r);
    
    

    //

    $city_u = trim(val_rq('city_u'));
    if ($city_u != va($fu,'city_u')) {
        $fu['city_u'] = $city_u;
    }

    $s = val_rq('url_photo_nu');
    if (!empty($s)) {
        $fu['url_photo_nu'] = $s;
    }


    $fu['nu1'] = val_rq('nu1');
    $fu['nu2'] = val_rq('nu2');
    $fu['nu3'] = val_rq('nu3');
    $fu['nu'] = $fu['nu1'] .' '.$fu['nu2'] .' '.$fu['nu3'];

    
    $fu['gender_u'] = val_rq('gender_u');
    $fu['date_birthday'] = val_rq('date_birthday');
    $fu['place_birthday'] = val_rq('place_birthday');
    
    
    $fu['birthday_u'] = val_rq('birthday_u');
    $fu['address_u'] = val_rq('address_u');

    $fu['info1_u_public'] = val_rq('info1_u_public');
    $fu['info2_u_public'] = val_rq('info2_u_public');
    $fu['url_u_public'] = val_rq('url_u_public');

    file_put_contents($fu['file'], ini_from_array($fu));
    $_SESSION['fu'] = $fu;

    $r = new db(DB_POBEDIM, 'select * from U2_CERTIFICATE_IU1(:u,:ic,:tv, :n1c,:n2c,:n3c,:dbc,:bpc, :ec, :cc)'
            ,[':u'=>$u,':ic'=>null,':tv'=>null, ':n1c'=>$fu['nu1'],':n2c'=>$fu['nu2'],':n3c'=>$fu['nu3']
                ,':dbc'=> fmtDDMMYYYY( $fu['date_birthday'])
                ,':bpc'=>$fu['place_birthday'] 
                ,':ec'=>$fu['email_notify_confirmed'] ,':cc'=>val_rq('info1_u_public') ]);
    
        
    if ( empty($fu['nu1']) 
            ||empty($fu['nu3']) 
            ||empty($fu['date_birthday']) 
            ||empty($fu['place_birthday'])
            ||empty($fu['email_notify_confirmed']) ) {
        $ix = 'Рекомендуем заполнить важные данные для работы системы голосований';
    }
        
}


